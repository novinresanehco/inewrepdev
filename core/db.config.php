<?php
error_reporting('1');
date_default_timezone_set('asia/Tehran');
header("Content-Type: text/html; charset=utf-8");
mb_internal_encoding('UTF-8');

if (!defined('news_security')) {
    die("You are not allowed to access this page directly!");
}

define('DS', DIRECTORY_SEPARATOR);

$dbconfig = array(
    'hostname' => 'localhost',
    'username' => 'sepehra8_asdadsepeho234rfdshop',
    'password' => '{9DgTvBkZMa(',
    'perfix' => '',
    'database' => 'sepehra8_gregdf5155oldshodbname',
    'reseller' => 1,
);

$perfix = $dbconfig['perfix'];

//Select database
$postlinksvars = array(
    "%postid%" => "([0-9]+)",
    "%sid%" => "([0-9]+)",
    "%sname%" => "(.*)",
    "%sslug%" => "(.*)",
    "%posttitle%" => "(.*)",
    "%postslug%" => "(.*)",
    "%postdday%" => "([0-9]+)",
    "%postdmonth%" => "([0-9]+)",
    "%postyear%" => "([0-9]+)"
);

$subcatlinksvars = array(
    "%id%" => "([0-9]+)",
    "%name%" => "(.*)",
    "%slug%" => "(.*)"
);

$pagelinksvars = array(
    "%id%" => "([0-9]+)",
    "%name%" => "(.*)"
);

$tagslinksvars = array(
    "%name%" => "(.*)"
);

$d = new dbclass();
$d->mysql($dbconfig['hostname'], $dbconfig['username'], $dbconfig['password'], $dbconfig['database']);

$configq = $d->Query("SELECT * FROM $perfix.config");
while ($configd = $d->fetch($configq)) {
    $config[$configd['name']] = $configd['value'];
}
unset($configd, $configq);

define('admin_dir', dirname(__FILE__) . DS . '..' . DS . 'core' . DS);
define('theme_dir', dirname(__FILE__) . DS . '..' . DS . 'theme' . DS);
define('core_theme_url', $config['site'] . 'theme/core/' . $config['theme'] . '/');
define('core_theme_dir', dirname(__FILE__) . DS . '..' . DS . 'theme' . DS . 'core' . DS);
define('current_theme_dir', core_theme_dir . $config['theme'] . DS);
define('admin_theme_dir', dirname(__FILE__) . DS . '..' . DS . 'theme' . DS . 'admin' . DS);
define('plugins_dir', dirname(__FILE__) . DS . '..' . DS . 'plugins' . DS);
define('files_dir', dirname(__FILE__) . DS . '..' . DS . 'files' . DS);
define('tmp_dir', dirname(__FILE__) . DS . '..' . DS . 'files' . DS . 'tmp' . DS);

function safeinturl($url)
{
    return $url;
}

if (!defined('rss')) {
    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    require($dir . "farsi.php");
    include($dir . "jdf.php");
    define('cutime', jdate('l j F Y'));
}

define('copyright', "samaneh cms");
$Themedir = __DIR__ . "/../theme/core/" . $config['theme'] . '/';
define('ThemeDir', $Themedir);
define('rootDir', __DIR__ . '/../');