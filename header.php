<?php
if (!defined("head")) {
    die("samaneh::Wrong Access");
}

function writeDebugLog($message, $data = null) {
    $logMsg = date('[Y-m-d H:i:s] ') . $message;
    if ($data !== null) {
        $logMsg .= ': ' . print_r($data, true);
    }
    error_log($logMsg);
}

writeDebugLog("=== REQUEST START ===");
writeDebugLog("REQUEST_URI", $_SERVER['REQUEST_URI']);

@session_start();

// Debug settings
$debug_log = __DIR__ . '/debug.log';
if (isset($_GET['debug_full'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('error_log', $debug_log);
    writeDebugLog("Full debug enabled");
}

// Core definitions
define('currentpage', 'core');
define('pageid', '11');
define('news_security', true);

// Required files
require('pfc1/phpfastcache.php');
include("core/db.php");
include("core/db.config.php");
include("core/theme.php");
include("core/function.php");
$cache = phpFastCache();

// Site status check
if ($config['disable'] == '1') {
    require("core/html.php");
    $html = new html();
    $html->msg($lang["sitedisabled"]);
    $html->printout(true);
}

// Host validation and redirect
$config['host'] = parse_url($config['site']);
$config['host'] = $config['host']['host'];
if ($_SERVER['SERVER_NAME'] !== $config['host']) {
    $url = str_ireplace($_SERVER['SERVER_NAME'], $config['host'], $_SERVER['REQUEST_URI']);
    if (strpos($url, $config['host']) === false) {
        $url = $config['site'];
    }
    header("Location: $url");
    exit('redirect:' . $url);
}

function safemini(&$value, $key) {
    $value = (preg_match('/^text_/', $key)) ? safe($value, 1) : safe($value);
    return $value;
}

require("core/user.php");

// IP Ban check
$ip = safe(getRealIpAddr());
if ($d->getrows("SELECT `ip` FROM `bann` WHERE `ip`='$ip' LIMIT 1", true) > 0) {
    $msg = $d->GetRowValue("mess", "SELECT `mess` FROM `bann` WHERE `ip`='$ip' LIMIT 1", true);
    if (!isset($html)) {
        require_once("core/html.php");
        $html = new html();
    }
    $html->msg($msg, 'error');
    $html->printout(true);
    header("Location: banned.php");
    die($msg);
}

$Themedir = __DIR__ . "/theme/core/" . $config['theme'] . '/';

// User authentication
$user = new user();
if ($login = $user->checklogin()) {
    $info = $user->info;
    $permissions = $user->permission();
}

// Pagination settings
if (isset($_POST['number']) && is_numeric(@$_POST['number']) && isset($_POST['type']) && !isset($_GET['reset'])) {
    $_COOKIE['number'] = $RPP = ($_POST['number'] > 100) ? 100 : $_POST['number'];
    $_COOKIE['type'] = $type = ($_POST['type'] == 'ASC') ? 'ASC' : 'DESC';
} else {
    $_COOKIE['number'] = $RPP = (!isset($_COOKIE['number']) || !is_numeric(@$_COOKIE['number']) || isset($_GET['reset'])) ? $config['num'] : $_COOKIE['number'];
    $_COOKIE['type'] = $type = (@$_COOKIE['type'] == 'ASC' || isset($_GET['reset'])) ? 'ASC' : 'DESC';
}

setcookie('number', $RPP);
setcookie('type', $type);

$CurrentPage = (!isset($_GET['page']) || !is_numeric(@$_GET['page']) || (abs(@$_GET['page']) == 0)) ? 1 : abs($_GET['page']);
$From = ($CurrentPage - 1) * $RPP;

// URL processing
$requested_uri = trim($_SERVER['REQUEST_URI'], '/');
$requested_uri = urldecode($requested_uri);
$fulllink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$fulllink = !empty($fulllink) ? trim($fulllink, "/") : '';
$_SERVER['REQUEST_URI'] = $requested_uri;

// Reset GET parameters and process URL patterns
$_GET = array();
if (!empty($fulllink) && $fulllink != 'index.php') {
    $patterns = $d->Query("SELECT * FROM `redirects`");
    while ($pattern = $d->fetch($patterns)) {
        if (preg_match("#" . $pattern['pattern'] . "#U", $fulllink)) {
            $url = preg_replace("#" . $pattern['pattern'] . "#", $pattern["url"], $fulllink);
            parse_str($url, $_GET);
        }
    }
}

// Initialize template system
$tpl = new samaneh();

// === TEMPLATE SELECTION LOGIC === //
// Default template
$pageTheme = 'first.htm';
$single_post = false;

// Process URL and determine template
if (preg_match('#^page/(\d+).*?$#', $requested_uri, $matches)) {
    $pageId = (int)$matches[1];
    $customTemplate = "extra_{$pageId}.htm";
    
    writeDebugLog("Checking page template", [
        'page_id' => $pageId,
        'template' => $customTemplate,
        'path' => $Themedir . $customTemplate,
        'exists' => file_exists($Themedir . $customTemplate)
    ]);
    
    if (file_exists($Themedir . $customTemplate)) {
        $pageTheme = $customTemplate;
        writeDebugLog("Using custom page template", ['template' => $pageTheme]);
    }
} 
// Handle post URLs
elseif (preg_match('#^post/(\d+).*?$#', $requested_uri, $matches)) {
    $_GET['id'] = (int)$matches[1];
    $_GET['type'] = 'post';
    $_GET['single'] = '1';
    $_SESSION['display_mode'] = 'single';
    $single_post = true;
    
    $post_query = "SELECT * FROM `data` WHERE `id` = '" . (int)$_GET['id'] . "' AND `show` = '1' LIMIT 1";
    $post_data = $d->Query($post_query);
    $post = $d->fetch($post_data);

    if (!$post) {
        header("HTTP/1.0 404 Not Found");
        include($Themedir . '404.htm');
        exit();
    }

    // Update hit counter
    $d->Query("UPDATE `data` SET hits = hits + 1 WHERE id = '" . (int)$_GET['id'] . "'");

    // Process post content
    $text = $post['text'];
    if (!empty($post['full'])) {
        $text .= '<br>' . $post['full']; 
    }

    $date = mytime("Y-m-d", $post['date'], $config['dzone']);
    $date = explode("-", $date);
    
    // Set post data for template
    $tpl->block('mp', [
        'title' => $post['title'],
        'subject' => $cats[$post['cat_id']],
        'id' => $post['id'],
        'link' => get_post_link([
            "%postid%" => $post['id'],
            "%subjectid%" => $post['cat_id'],
            "%sname%" => $cats[$post['cat_id']],
            "%slug%" => $cats[$post['cat_id']],
            "%posttitle%" => $post['title'],
            "%postslug%" => $post['entitle'],
            "%postdday%" => $date[2],
            "%postdmonth%" => $date[1],
            "%postyear%" => $date[0]
        ]),
        'sub_link' => get_subcat_link([
            "%id%" => $post['cat_id'],
            "%name%" => $cats[$post['cat_id']],
            "%slug%" => $cats[$post['cat_id']]
        ]),
        'sub_id' => $post['cat_id'],
        'body' => $text,
        'date' => mytime($config['dtype'], $post['date'], $config['dzone']),
        'author' => $authors[$post['author']],
        'entitle' => $post['entitle'],
        'timage' => $post['timage'],
        'numhits' => $post['hits']
    ]);

    $tpl->assign([
        'sitetitle' => $post['title'] . ' - ' . $config['sitetitle'],
        'sitedescription' => !empty($post['description']) ? $post['description'] : $config['desc'],
        'sitekeywords' => !empty($post['keywords']) ? $post['keywords'] : $config['keys']
    ]);

    $pageTheme = 'single.htm';
} 
// Handle category URLs
elseif (preg_match('#^cat/(\d+).*?\.php#', $requested_uri, $matches)) {
    $_GET['plugins'] = 'cat';
    $_GET['catid'] = (int)$matches[1];
    $_SESSION['display_mode'] = 'category';
    
    $cat_posts = $d->Query("SELECT * FROM `data` WHERE cat_id = '" . (int)$_GET['catid'] . "' AND `show` = '1' ORDER BY id $type LIMIT $From, $RPP");
    
    while ($post = $d->fetch($cat_posts)) {
        $date = mytime("Y-m-d", $post['date'], $config['dzone']);
        $date = explode("-", $date);
        
        $tpl->block('mp', [
            'title' => $post['title'],
            'subject' => $cats[$post['cat_id']],
            'id' => $post['id'],
            'link' => get_post_link([
                "%postid%" => $post['id'],
                "%subjectid%" => $post['cat_id'],
                "%sname%" => $cats[$post['cat_id']],
                "%slug%" => $cats[$post['cat_id']],
                "%posttitle%" => $post['title'],
                "%postslug%" => $post['entitle'],
                "%postdday%" => $date[2],
                "%postdmonth%" => $date[1],
                "%postyear%" => $date[0]
            ]),
            'sub_id' => $post['cat_id'],
            'body' => $post['text'],
            'date' => mytime($config['dtype'], $post['date'], $config['dzone']),
            'author' => $authors[$post['author']],
            'timage' => $post['timage']
        ]);
    }

    $cat_template = 'cat_' . $_GET['catid'] . '.htm';
    if (file_exists($Themedir . $cat_template)) {
        $pageTheme = $cat_template;
    } elseif (file_exists($Themedir . 'cat.htm')) {
        $pageTheme = 'cat.htm';
    }
} 
// Handle list mode (homepage)
else {
    $_SESSION['display_mode'] = 'list';
    
    $posts_query = "SELECT * FROM `data` WHERE `show` = '1' ORDER BY id $type LIMIT $From, $RPP";
    $posts_data = $d->Query($posts_query);
    
    while ($post = $d->fetch($posts_data)) {
        $date = mytime("Y-m-d", $post['date'], $config['dzone']);
        $date = explode("-", $date);
        
        $tpl->block('mp', [
            'title' => $post['title'],
            'subject' => $cats[$post['cat_id']],
            'id' => $post['id'],
            'link' => get_post_link([
                "%postid%" => $post['id'],
                "%subjectid%" => $post['cat_id'],
                "%sname%" => $cats[$post['cat_id']],
                "%slug%" => $cats[$post['cat_id']],
                "%posttitle%" => $post['title'],
                "%postslug%" => $post['entitle'],
                "%postdday%" => $date[2],
                "%postdmonth%" => $date[1],
                "%postyear%" => $date[0]
            ]),
            'sub_id' => $post['cat_id'],
            'body' => $post['text'],
            'date' => mytime($config['dtype'], $post['date'], $config['dzone']),
            'author' => $authors[$post['author']],
            'timage' => $post['timage']
        ]);
    }
}

// Final template verification and loading
if (!file_exists($Themedir . $pageTheme)) {
    header("HTTP/1.0 404 Not Found");
    include($Themedir . '404.htm');
    exit();
}

writeDebugLog("Final template selection", [
    'theme' => $pageTheme,
    'path' => $Themedir . $pageTheme,
    'exists' => file_exists($Themedir . $pageTheme)
]);

// Load blocks if needed
if (!defined('noblock')) {
    require("core/blocks.php");
}

// Load the template
$tpl->load($Themedir . $pageTheme);

// Asset optimization
$headercss = [
    $config['site'] . 'theme/core/' . $config['theme'] . '/assets/bundles/dynamic-8CZdJEE8s.css',
    $config['site'] . 'theme/core/' . $config['theme'] . '/assets/fonts/bakhload.css',
    $config['site'] . 'theme/core/' . $config['theme'] . '/assets/css-cmp/template-client-portal.css'
];

$headerjs = [
    $config['site'] . 'theme/core/' . $config['theme'] . '/assets/bundles/dynamic-gLIWt.js'
];

// Process CSS
foreach ($headercss as $css) {
    $tpl->assign('additional_css', "<link rel='stylesheet' href='$css'>");
}

// Process JS
foreach ($headerjs as $js) {
    $tpl->assign('additional_js', "<script src='$js'></script>");
}

// Set common template variables
$tpl->assign([
    'siteurl' => $config['site'],
    'sitename' => $config['sitename'],
    'sitetitle' => $config['sitetitle'],
    'sitedescription' => $config['desc'],
    'sitekeywords' => $config['keys'],
    'currentpage' => $currentpage,
    'pageid' => $pageid,
    'todayfarsi' => cutime,
    'ip' => $ip,
    'site' => $config['site']
]);

// User-specific template variables
if ($login) {
    $tpl->assign([
        'user_logged' => true,
        'username' => $info['username'],
        'user_id' => $info['id'],
        'user_email' => $info['email'],
        'user_permissions' => $permissions
    ]);
}

// Process reseller info
$reseller_info = $cache->get('reseller_info');
if(!is_array($cache->get('reseller_info'))) {
    $reseller = file_get_contents('http://irancms.com/fa/portal/get_reseller_info/?reseller=' . $dbconfig['reseller']);
    $reseller = json_decode($reseller, true);
    if(is_array($reseller)) {
        $cache->set('reseller_info', $reseller, 3600);
    }
}
if(!is_array($reseller_info)) {
    $reseller_info = array('url' => '', 'reseller' => '');
}

$tpl->assign([
    'reseller' => $reseller_info['reseller'],
    'resellerurl' => $reseller_info['url'],
    'resellername' => $reseller_info['reseller'],
]);

foreach($config as $key => $value) {
    $config[$key] = str_replace(
        ['[reseller]', '[resellerurl]', '[resellername]'],
        [$reseller_info['reseller'], $reseller_info['url'], $reseller_info['reseller']],
        $config[$key]
    );
}

// Special case for member URLs
if(preg_match("%member%", $_SERVER['REQUEST_URI'])) {
    $tpl->load($Themedir . 'ssingle.htm');
}

// Clean inputs
array_walk($_POST, 'safemini');
array_walk($_GET, 'safemini');

// Start output buffering
ob_start();

// Error handling setup
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    writeDebugLog("PHP Error", [
        'errno' => $errno,
        'error' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]);
    
    switch ($errno) {
        case E_USER_ERROR:
            ob_end_clean();
            require_once("core/html.php");
            $html = new html();
            $html->msg("Critical Error: $errstr", 'error');
            $html->printout(true);
            exit(1);
            break;
        case E_USER_WARNING:
            writeDebugLog("Warning: $errstr");
            break;
        case E_USER_NOTICE:
            writeDebugLog("Notice: $errstr");
            break;
    }
    return true;
});

// Handle uncaught exceptions
set_exception_handler(function($exception) {
    writeDebugLog("Uncaught Exception", [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    ob_end_clean();
    require_once("core/html.php");
    $html = new html();
    $html->msg("System Error: " . $exception->getMessage(), 'error');
    $html->printout(true);
});

// Shutdown function
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && $error['type'] === E_ERROR) {
        writeDebugLog("Fatal Error", $error);
        ob_end_clean();
        require_once("core/html.php");
        $html = new html();
        $html->msg("Fatal Error: " . $error['message'], 'error');
        $html->printout(true);
    }
    writeDebugLog("=== REQUEST END ===\n");
});