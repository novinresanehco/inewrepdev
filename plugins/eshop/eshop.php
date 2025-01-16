<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    exit;
}
define( 'privateKey', 'sample private keyFGHJKL$%^&*(CVBNKM$%^&*( ' . $_SERVER['SERVER_ADDR'] );
$eshop = new eshop;
$tpl->assign( 'eshopCategories', $eshop->categoryList() );
$tpl->assign( 'recenteproducts', $eshop->recenteproducts() );
$tpl->assign( 'listeProducts', $eshop->listeproducts() );
$tpl->assign( 'sideBareBasket', $eshop->sideBareBasket() );
$tpl->assign( 'besteproducts', $eshop->besteproducts() );
$tpl->assign( 'esideBarStatus', $eshop->esideBarStatus() );
$eshop->proccessSearches();
if ( isset( $_GET['eshopDownload'] ) && is_numeric( $_GET['eshopDownload'] ) )
{
    $eshop->download();
}
if ( isset( $_POST['payeBasket'] ) )
{
    $eshop->prePayment();
}
if ( isset( $_GET['ebasket'] ) )
{
    $eshop->viewBasket();
}
else
if ( !empty( $_GET['showeBasketResult'] ) )
{
    $eshop->showeBasketResult();
}
else
if ( isset( $_GET['eproductID'] ) && is_numeric( $_GET['eproductID'] ) )
{
    $action = (isset( $_GET['action'] ) ) ? $_GET['action'] : '';

    switch ( $action )
    {
        case 'addToBasket':
            $eshop->addTobasket( $_GET['eproductID'] );
            $eshop->showProduct( $_GET['eproductID'] );

            break;

        default:
            //show product page
            $eshop->showProduct( $_GET['eproductID'] );
            break;
    }
}

//send sms to admin after new payment
//search
class eshop
{

    //download
    function download()
    {
        global $config, $show_posts;
        $show_posts = false;
        $error      = true;
        if ( isset( $_GET['eshopDownload'] ) && is_numeric( $_GET['eshopDownload'] ) )
        {
            if ( empty( $_GET['key'] ) OR empty( $_GET['time'] ) )
            {
                $itpl = new samaneh();
                $itpl->load( 'plugins/eshop/download.html' );
                $itpl->assign( array(
                    'message' => true,
                    'msg'     => 'اطلاعات ارسالی ناقص است.',
                ) );
            }
            else
            {
                //validate link address
                $key = $this->generateKey( $_GET['eshopDownload'], $_GET['time'] );
                if ( $key != $_GET['key'] )
                {
                    $itpl = new samaneh();
                    $itpl->load( 'plugins/eshop/download.html' );
                    $itpl->assign( array(
                        'message' => true,
                        'msg'     => 'اطلاعات ارسالی ناقص است یا آدرس آی پی تغییر کرده است..',
                    ) );
                }
                else
                {
                    //make sure time is not expired !
                    $time = time();
                    if ( $time < $_GET['time'] - (48 * 3600) )
                    {
                        $itpl->assign( array(
                            'message' => true,
                            'msg'     => 'مدت زمان مجاز دانلود لینک منقضی شده است. در صورت نیاز به دانلود فایل با پشتیبانی سایت تماس بگیرید.',
                        ) );
                    }
                    else
                    {
                        //get product
                        global $d;
                        $productID = ( int ) $_GET['eshopDownload'];
                        $product   = $d->Query( "SELECT * FROM `eproducts` WHERE `productID`='$productID' LIMIT 1" );
                        if ( headers_sent() OR $d->getRows( $product ) !== 1 )
                        {
                            $itpl = new samaneh();
                            $itpl->load( 'plugins/eshop/download.html' );
                            $itpl->assign( array(
                                'message' => true,
                                'msg'     => 'خطا در دانلود فایل. لطفا با پشتیبانی سایت تماس بگیرید.',
                            ) );
                        }
                        else
                        {
                            $product = $d->fetch( $product );
                            if ( empty( $product['link'] ) )
                            {
                                $itpl = new samaneh();
                                $itpl->load( 'plugins/eshop/download.html' );
                                $itpl->assign( array(
                                    'message' => true,
                                    'msg'     => 'خطا در دانلود فایل. لطفا با پشتیبانی سایت تماس بگیرید.',
                                ) );
                            }
                            else
                            {
                                //read and download file !
                                $error    = false;
                                set_time_limit( 0 );
                                $download = new ResumeDownload( $product['link'] );
                                $download->process();
                            }
                        }
                    }
                }
            }
        }
        if ( $error )
        {
            global $tpl;
            $tpl->block( 'mp', array(
                'body'     => $itpl->dontshowit(),
                'link'     => $config['site'],
                'sub_link' => $config['site'],
                'subject'  => 'دانلود فایل'
            ) );
        }
    }

    var $BasketStatuses = array( 'بررسی نشده', 'در حال بررسی', 'تایید اولیه', 'بسته بندی محصول', 'ارسال شده', 'برگشت خورده', 'انصراف' );

    //proccess search forms
    function proccessSearches()
    {
        global $d, $tpl, $config;
        $searches = $d->Query( "SELECT * FROM `eshopsearch`" );
        while ( $data     = $d->fetch( $searches ) )
        {
            $itpl   = new samaneh();
            $itpl->load( 'plugins/eshop/search.html' );
            $itpl->assign( 'theme_url', core_theme_url );
            $fields = json_decode( base64_decode( $data['fields'] ) );
            if ( is_array( $fields ) )
            {
                foreach ( $fields as $field )
                {
                    if ( $field != 'price' AND $field != 'title' )
                    {
                        //get field from db
                        $field = $d->Query( "SELECT `fieldID`,`title` FROM `ecategory_fields` WHERE `fieldID`='$field' LIMIT 1" );
                        if ( $d->getRows( $field ) !== 1 )
                        {
                            $field = '';
                        }
                        else
                        {
                            $field = $d->fetch( $field );
                            $title = $field['title'];
                            $field = $field['fieldID'];
                        }
                    }
                    else
                    {
                        $title = ( $field == 'price' ) ? 'قیمت' : 'عنوان';
                    }
                    $itpl->assign( 'url', $config['site'] );
                    $itpl->assign( 'categoryID', $data['categoryID'] );
                    if ( !empty( $field ) )
                    {
                        $itpl->block( 'fields', array( 'fieldID' => trim( $field ), 'title' => trim( $title ) ) );
                    }
                }
            }
            $tpl->assign( 'esearch_' . $data['searchID'], $itpl->dontshowit() );
        }
    }

    function showProduct( $productID )
    {
        if ( !is_numeric( $productID ) )
        {
            header( "Location: $config[site]" );
            exit;
        }
        $productID   = intval( $productID );
        global $show_posts, $d, $tpl, $config, $single_post;
        $single_post = true;
        $show_posts  = false;
        if ( file_exists( ThemeDir . 'eshop-single.htm' ) )
        {
            $tpl->load( ThemeDir . 'eshop-single.htm' );
        }
        $product = $d->Query( "SELECT * FROM `eproducts` WHERE `productID`='$productID' LIMIT 1" );
        if ( $d->getRows( $product ) !== 1 )
        {
            header( "Location: $config[site]" );
            exit;
        }
        $product      = $d->fetch( $product );
        $itpl         = new samaneh();
        $itpl->load( 'plugins/eshop/singleProduct.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        //product images
        $images       = $d->Query( "SELECT `imageUrl` FROM `eproduct_images` WHERE `productID`='$product[productID]'" );
        $primaryImage = '';
        $imgData      = $d->fetch( $images );
        $primaryImage = $imgData['imageUrl'];


        $category = $d->Query( "SELECT `title` FROM `eproduct_categories` WHERE `categoryID`='$product[category]' LIMIT 1" );
        $category = $d->fetch( $category );
        //$categoryFields = $category['fields'];
        $category = $category['title'];
        $bData    = array(
            'title'       => $product['title'],
            'description' => stripcslashes( htmlspecialchars_decode( $product['description'] ) ),
            'price'       => $product['price'],
            'fprice'      => number_format( $product['price'] ),
            'category'    => $category,
            'productID'   => $product['productID'],
            'url'         => $this->getProductUrl( $product['productID'] )
        );
        if ( !empty( $primaryImage ) )
        {
            $bData['primaryImage']    = $primaryImage;
            $bData['hasPrimaryImage'] = true;
        }
        $itpl->block( 'eproducts', $bData );
        while ( $imgData = $d->fetch( $images ) )
        {
            $itpl->block( 'productImages_' . $product['productID'], array(
                'image' => $imgData['imageUrl']
            ) );
        }
        $fieldsDB = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$product[category]'" );
        while ( $field    = $d->fetch( $fieldsDB ) )
        {
            $Fdata = $d->Query( "SELECT `value` FROM `eproduct_fields` WHERE `productID`='$product[productID]' AND `fieldID`='$field[fieldID]' LIMIT 1" );
            $Fdata = $d->fetch( $Fdata );
            $value = $Fdata['value'];
            if ( !empty( $value ) )
            {
                $itpl->block( 'productFields_' . $product['productID'], array(
                    'fieldTitle' => $field['title'],
                    'name'       => $field['name'],
                    'value'      => $value
                ) );
            }
        }

        $tpl->block( 'mp', array(
            'body'     => $itpl->dontshowit(),
            'link'     => $config['site'],
            'sub_link' => $config['site'],
            'subject'  => $category
        ) );
    }

    //recent Sidebar
    function recenteproducts()
    {
        global $d;
        $itpl  = new samaneh();
        $itpl->load( 'plugins/eshop/recenteProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $query = "SELECT * FROM `eproducts` WHERE `delete`=0 LIMIT 10";
        $query = $d->Query( $query );
        while ( $data  = $d->fetch( $query ) )
        {
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
			$images       = $d->Query( "SELECT `imageUrl` FROM `eproduct_images` WHERE `productID`='$data[productID]'" );
            $primaryImage = '';
            $imgData      = $d->fetch( $images );
            $primaryImage = $imgData['imageUrl'];
			
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'recenteproducts', $bData );
        }
        return $itpl->dontshowit();
    }

    //best eproducts list
    function besteproducts()
    {
        global $d;
        $itpl  = new samaneh();
        $itpl->load( 'plugins/eshop/recenteProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $query = "select `eproducts`.*, count(*) from `ebasket_items` inner join `eproducts` on eproducts.productID=ebasket_items.productID group by eproducts.productID order by count(*) desc;";
        $query = $d->Query( $query );
        while ( $data  = $d->fetch( $query ) )
        {
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'recenteproducts', $bData );
        }
        return $itpl->dontshowit();
    }

//product lists
    function listeproducts()
    {
        global $d;
        $itpl = new samaneh();
        $itpl->load( 'plugins/eshop/eshopProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        //list based on current category or list recents
        if ( isset( $_GET['eshopCategory'] ) && is_numeric( $_GET['eshopCategory'] ) )
        {
            $eshopCategory = ( int ) $_GET['eshopCategory'];
            $query         = "SELECT * FROM `eproducts` WHERE `delete`=0 AND `category`='$eshopCategory'";
            $queryCount    = "SELECT COUNT(*) as total FROM `eproducts` WHERE `delete`=0 AND `category`='$eshopCategory'";
        }
        else
        {
            $query      = "SELECT * FROM `eproducts` WHERE `delete`=0";
            $queryCount = "SELECT COUNT(*) as total FROM `eproducts` WHERE `delete`=0";
        }
        if ( isset( $_GET ) && is_array( $_GET ) )
        {
            foreach ( $_GET as $key => $value )
            {
                if ( !empty( $value ) && substr( $key, 0, 7 ) == 'esearch_' )
                {
                    $value = safe( $value );
                    $key   = substr( $key, 7 );
                    if ( $key == 'price' OR $key == 'title' )
                    {
                        if ( $key == 'price' )
                        {
                            $query .= " AND `$key` >= '$value' ";
                            $queryCount .= " AND `$key` >= '$value' ";
                        }
                        else
                        {
                            $query .= " AND `$key` LIKE '%$value%' ";
                            $queryCount .= " AND `$key` LIKE '%$value%' ";
                        }
                    }
                    else if ( is_numeric( $key ) )
                    {
                        $query .= " AND `productID` IN ( SELECT `productID` FROM `eproduct_fields` WHERE `fieldID`='$key'  AND `value` LIKE '%$value%' ) ";
                        $queryCount .= " AND `productID` IN ( SELECT `productID` FROM `eproduct_fields` WHERE `fieldID`='$key'  AND `value` LIKE '%$value%' ) ";
                    }
                }
            }
        }
        $rpp   = 20;
        $total = $d->Query( $queryCount );
        $total = $d->fetch( $total );
        $total = $total['total'];
        $pages = ceil( $total / $rpp );
        $start = 0;
        $page  = 1;
        if ( isset( $_GET['page'] ) && is_numeric( $_GET['page'] ) && $_GET['page'] > 0 )
        {
            $page  = $_GET['page'];
            $start = ($_GET['page'] - 1) * $rpp;
        }
        $query .= " LIMIT $start,$rpp";
        $eproducts      = $d->Query( $query );
        $categories     = array();
        $categoryFields = array();
        while ( $data           = $d->fetch( $eproducts ) )
        {
            //product images
            $images       = $d->Query( "SELECT `imageUrl` FROM `eproduct_images` WHERE `productID`='$data[productID]'" );
            $primaryImage = '';
            $imgData      = $d->fetch( $images );
            $primaryImage = $imgData['imageUrl'];


            if ( empty( $categories[$data['category']] ) )
            {
                $categories[$data['category']]     = $d->Query( "SELECT `title` FROM `eproduct_categories` WHERE `categoryID`='$data[category]' LIMIT 1" );
                $categories[$data['category']]     = $d->fetch( $categories[$data['category']] );
                $categoryFields[$data['category']] = $d->fetch( $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$data[category]'" ) );
                $categories[$data['category']]     = $categories[$data['category']]['title'];
            }
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'category'    => $categories[$data['category']],
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'eproducts', $bData );
            while ( $imgData = $d->fetch( $images ) )
            {
                $itpl->block( 'productImages_' . $data['productID'], array(
                    'image' => $imgData['imageUrl']
                ) );
            }

            $fieldsDB = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$data[category]'" );
            while ( $field    = $d->fetch( $fieldsDB ) )
            {
                $Fdata = $d->Query( "SELECT `value` FROM `eproduct_fields` WHERE `productID`='$data[productID]' AND `fieldID`='$field[fieldID]' LIMIT 1" );
                $Fdata = $d->fetch( $Fdata );
                $value = $Fdata['value'];
                if ( !empty( $value ) )
                {
                    $itpl->block( 'productFields_' . $data['productID'], array(
                        'fieldTitle' => $field['title'],
                        'name'       => $field['name'],
                        'value'      => $value
                    ) );
                }
            }

            /*
              $tmp           = nl2br( trim( $categoryFields[$data['category']] ) );
              $tmp           = explode( '<br />', $tmp );
              //$productFields = json_decode( $data['productValues'], true );
              foreach ( $tmp as $name )
              {
              if ( !empty( $name ) )
              {
              $mname = md5( $name );
              $value = (isset( $productFields[$mname] )) ? $productFields[$mname] : '';
              if ( !empty( $value ) )
              {
              $itpl->block( 'productFields_' . $data['productID'], array(
              'title' => $name,
              'name'  => $mname,
              'value' => $value
              ) );
              }
              }
              }
             */
        }
        //list pages
        $url = $this->curPageURL();
        for ( $i = 1; $i <= $pages; $i++ )
        {
            $itpl->block( 'pages', array( 'page' => $i, 'active' => ($i === $page) ? ' active' : ' ', 'url' => $this->appendQS( $url, 'page', $i ) ) );
        }
        return $itpl->dontshowit();
    }

//category List
    function categoryList()
    {
        global $d;
        $itpl       = new samaneh();
        $itpl->load( 'plugins/eshop/eshopSideBarCategories.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $categories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `parentID`=0" );
        while ( $data       = $d->fetch( $categories ) )
        {
            $ddd           = array(
                'categoryID'   => $data['categoryID'],
                'title'        => $data['title'],
                'description'  => $data['description'],
                'productCount' => $data['productCount'],
                'image'        => $data['image'],
            );
            $url           = $this->curPageURL();
            $url           = preg_replace( '#\??eshopCategory=[0-9]+#iUs', '', $url );
            $url           = preg_replace( '#\??productID=[0-9]+#iUs', '', $url );
            $ddd['url']    = $this->appendQs( $url, 'eshopCategory', $data['categoryID'] );
            $subCategories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `parentID`='$data[categoryID]'" );
            if ( $d->fetch( $subCategories ) > 0 )
            {
                $ddd['hasSub'] = true;
            }

            $itpl->block( 'categories', $ddd );
            while ( $idata = $d->fetch( $categories ) )
            {
                $itpl->block( 'subCategories_' . $idata['categoryID'], array(
                    'subcategoryID'   => $idata['categoryID'],
                    'subtitle'        => $idata['title'],
                    'subdescription'  => $idata['description'],
                    'subproductCount' => $idata['productCount'],
                    'subimage'        => $idata['image'],
                    'subur;'          => $this->appendQs( $url, 'eshopCategory', $idata['categoryID'] )
                ) );
            }
        }
        return $itpl->dontshowit();
    }

    function isValidProductID( $productID )
    {
        if ( is_numeric( $productID ) && $productID > 0 )
        {
            global $d;
            $product = $d->Query( "SELECT `productID` FROM `eproducts` WHERE `delete`=0 AND `productID`='$productID' LIMIT 1" );
            return ( $d->getRows( $product ) === 1);
        }
        return false;
    }

    //get Bassket ID
    function getBasketID()
    {
        global $d;
        $basketID = null;
        if ( !empty( $_SESSION['ebasketID'] ) && is_numeric( $_SESSION['ebasketID'] ) )
        {
            $basketID = $_SESSION['ebasketID'];
        }
        else
        {
            //if user is logged in ~> get last unpayed basket !
            $user = new user();
            if ( $user->checklogin() )
            {
                $info = $user->info();
                $tmp  = $d->Query( "SELECT `basketID` FROM `ebasket` WHERE `status`=0 AND `userID`='$info[u_id]' LIMIT 1" );
                if ( $d->getRows( $tmp ) === 1 )
                {
                    $tmp                   = $d->fetch( $tmp );
                    $basketID              = $tmp['basketID'];
                    $_SESSION['ebasketID'] = $basketID;
                }
            }
        }
        return $basketID;
    }

    //addTobasket
    function addTobasket( $productID )
    {
        global $d;
        //make sure product ID is valid
        if ( $this->isValidProductID( $productID ) )
        {
            $basketID = $this->getBasketID();
            $user     = new user;
            $userID   = 0;
            if ( $user->checklogin() )
            {
                $userID = $user->info();
                $userID = $userID['u_id'];
            }
            $product = $this->getProduct( $productID );
            if ( is_null( $basketID ) )
            {
                //creat a new basket !
                $d->iQuery( 'ebasket', array(
                    'trackKey'    => $this->transactionKey(),
                    'userID'      => $userID,
                    'ip'          => $_SERVER['REMOTE_ADDR'],
                    'logProxy'    => $this->logproxy(),
                    'time'        => time(),
                    'status'      => 0,
                    'view'        => 0,
                    'fullname'    => '',
                    'address'     => '',
                    'mobile'      => '',
                    'tell'        => '',
                    'zip'         => '',
                    'adminStatus' => '',
                    'price'       => $product['price'],
                ) );
                $_SESSION['ebasketID'] = $basketID              = $d->last();
                //add product to basket list
                $d->iQuery( "ebasket_items", array(
                    'basketID'  => $basketID,
                    'productID' => $productID,
                    'count'     => 1,
                    'price'     => $product['price']
                ) );
            }
            else
            {
                //update basket
                $d->Query( "UPDATE `ebasket` SET `price`=`price`+$product[price] WHERE `basketID`=$basketID LIMIT 1" );
                $isInBasket = $d->Query( "SELECT `itemID` FROM `ebasket_items` WHERE `basketID`=$basketID AND `productID`=$productID LIMIT 1" );
                if ( $d->getRows( $isInBasket ) === 1 )
                {
                    //update count and price
                    $d->Query( "UPDATE `ebasket_items` SET `count`=`count`+1, `price`=`price`+$product[price] WHERE `basketID`=$basketID AND `productID`=$productID LIMIT 1" );
                }
                else
                {
                    //add product to basket list
                    $d->iQuery( "ebasket_items", array(
                        'basketID'  => $basketID,
                        'productID' => $productID,
                        'count'     => 1,
                        'price'     => $product['price']
                    ) );
                }
            }
        }
    }

    function getProduct( $productID )
    {
        if ( $this->isValidProductID( $productID ) )
        {
            global $d;
            $product = $d->Query( "SELECT * FROM `eproducts` WHERE `delete`=0 AND `productID`='$productID' LIMIT 1" );
            return $d->fetch( $product );
        }
        return array();
    }

    /* get proxy settings */

    function logproxy()
    {
        $proxy = array();
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) )
        {
            $proxy['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CLIENT_IP'];
        }

        if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
        {
            $proxy['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if ( isset( $_SERVER['REMOTE_ADDR'] ) )
        {
            $proxy['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        }
        return json_encode( $proxy );
    }

    function deleteFromBasket( $productID )
    {
        $basketID = $this->getBasketID();
        if ( !is_null( $basketID ) && $this->isValidProductID( $productID ) )
        {
            global $d;
            $d->Query( "DELETE FROM `ebasket_items` WHERE `basketID`='$basketID' AND `productID`='$productID' LIMIT 1" );
            $price = $d->Query( "SELECT SUM(`price`) as `total` FROM `ebasket_items` WHERE `basketID`='$basketID' " );
            $price = $d->fetch( $price );
            $price = $price['total'];
            $d->Query( "UPDATE `ebasket` SET `price`='$price' WHERE basketID=$basketID LIMIT 1" );
            global $config;
            header( "Location: $config[site]?ebasket" );
        }
    }

    //creat transaction Key
    function transactionKey()
    {
        // return rand
        return strtolower( GEN( 5 ) . '-' . GEN( 3 ) . '-' . GEN( 7 ) . '-' . GEN( 3 ) . '-' . rand( 1000, 1000000 ) . GEN( 5 ) . '-' . GEN( 3 ) . '-' . GEN( 15 ) );
    }

    function prePayment()
    {
        global $config, $d;
        if ( is_null( $this->getBasketID() ) )
        {
            header( "Location: $config[site]?ebasket" );
            exit;
        }
    }

    function pay( $itpl, $basket )
    {
        global $d;
        $gateways = $this->listGateWays();
        $basketID = $basket['basketID'];
        //make sure required fields are filled
        if ( empty( $_POST['fullname'] ) OR empty( $_POST['email'] ) OR empty( $_POST['zip'] ) OR empty( $_POST['address'] ) OR empty( $_POST['mobile'] ) )
        {
            $itpl->assign( array(
                'message' => true,
                'msg'     => 'لطفا همه فیلدهای ستاره دار را تکمیل کنید.',
            ) );
        }
        else
        {
            if ( !email( $_POST['email'] ) )
            {
                $itpl->assign( array(
                    'message' => true,
                    'msg'     => 'پست الکترونیک نامعتبر است',
                ) );
            }
            else
            if ( !ctype_alnum( $_POST['gateway'] ) OR !isset( $gateways[$_POST['gateway']] ) )
            {
                $itpl->assign( array(
                    'message' => true,
                    'msg'     => 'درگاه نامعتبر است.',
                ) );
            }
            else
            {
                $gate  = $gateways[$_POST['gateway']];
                //$file  = dirname( __FILE__ ) . '/gateways/' . $_POST['gateway'] . '.php';
                $file  = $gate[1];
                $class = 'payment_' . $_POST['gateway'];
                require_once $file;
                if ( !class_exists( $class ) )
                {
                    $itpl->assign( array(
                        'message' => true,
                        'msg'     => 'درگاه با خطا مواجه است.',
                    ) );
                }
                else
                {
                    //update basket
                    $d->uQuery( 'ebasket', array(
                        'fullname' => $_POST['fullname'],
                        'email'    => $_POST['email'],
                        'mobile'   => $_POST['mobile'],
                        'tell'     => $_POST['tell'],
                        'zip'      => $_POST['zip'],
                        'address'  => $_POST['address'],
                            ), " `basketID`='$basketID' LIMIT 1" );
                    //create payment transaction
                    $payment = new $class;
                    $data    = array(
                        'transactionCode' => $this->transactionKey(),
                        'basketID'        => $basketID,
                        'trackKey'        => $this->transactionKey(),
                        'price'           => $basket['price'],
                        'time'            => time(),
                        'ip'              => $_SERVER['REMOTE_ADDR'],
                        'gateway'         => $_POST['gateway'],
                    );
                    $result  = $payment->start( $data );
                    if ( $result['status'] )
                    {
                        //save transaction to db !
                        $data['trackKey']   = $result['trackKey'];
                        $data['reciveData'] = '';
                        if ( isset( $data['sendData'] ) )
                        {
                            if ( !is_array( $data['sendData'] ) )
                            {
                                $data['sendData'] = ( array ) $data['sendData'];
                            }
                            $data['sendData'] = base64_encode( json_encode( $data['sendData'] ) );
                        }
                        else
                        {
                            $data['sendData'] = base64_encode( json_encode( array_merge( $_SERVER, $_POST, $_GET ) ) );
                        }
                        $d->iQuery( 'ebasket_transactions', $data );
                        $payment->redirect( $data );
                    }
                    else
                    {
                        //show error
                        $itpl->assign( array(
                            'message' => true,
                            'msg'     => $result['error']
                        ) );
                    }
                }
            }
        }
    }

    function callback( $itpl, $basket )
    {
        global $config, $d;
        $gateways       = $this->listGateWays();
        $mihanphpErrors = array();
        if ( !ctype_alnum( $_GET['callback'] ) OR !isset( $gateways[$_GET['callback']] ) )
        {
            $mihanphpErrors[] = 'درگاه بدرستی انتخاب نشده است.';
        }
        else
        {
            if ( empty( $_GET['transactionCode'] ) )
            {
                $mihanphpErrors[] = 'شناسه تراکنش نامعتبر است.';
            }
            else
            {
                //get transaction from database
                $tranasctionCode = safe( $_GET['transactionCode'] );
                $transaction     = $d->Query( "SELECT * FROM `ebasket_transactions` WHERE `transactionCode`='$tranasctionCode' LIMIT 1" );
                if ( $d->getRows( $transaction ) !== 1 )
                {
                    $mihanphpErrors[] = 'شناسه تراکنش نامعتبر است.';
                }
                else
                {
                    $transaction = $d->fetch( $transaction );
                    if ( $transaction['status'] == '1' )
                    {
                        //redirect user and show paid items to user
                        $redirect = $this->appendQS( $config['site'], 'showeBasketResult', $basket['trackKey'] );
                        @header( "Location: $redirect" );
                        exit( "<script>document.location='$redirect';</script>" );
                    }
                    else
                    {
                        $gate  = $gateways[$_GET['callback']];
                        $file  = $gate[1];
                        //$file  = dirname( __FILE__ ) . '/gateways/' . $_GET['callback'] . '.php';
                        $class = 'payment_' . $_GET['callback'];
                        require_once $file;
                        if ( !class_exists( $class ) )
                        {
                            $mihanphpErrors[] = 'درگاه با خطا مواجه است.';
                        }
                        else
                        {
                            $payment      = new $class;
                            $verification = $payment->callback( $transaction );
                            if ( $verification['status'] === true )
                            {
                                //update transaction status
                                $d->uQuery( 'ebasket_transactions', array(
                                    'status' => 1,
                                        ), " `transactionCode`='$tranasctionCode' LIMIT 1" );
                                //update basket status
                                $d->uQuery( 'ebasket', array(
                                    'status'        => 1,
                                    'paymentStatus' => 1,
                                        ), " `basketID`='$basket[basketID]' LIMIT 1" );
                                //send email notification
                                send_mail( $config['email'], $config['email'], 'خرید جدیدی از وب سایت ' . $config['site'] . 'انجام شد.<br />شناسه پیگری: ' . $basket['trackKey'] );
                                //send sms
                                if ( function_exists( 'send_sms' ) )
                                {
                                    send_sms( $config['smsTo'], 'خرید جدیدی از وب سایت ' . $config['site'] . 'انجام شد.<br />شناسه پیگری: ' . $basket['trackKey'] );
                                }
                                //redirect user and show paid items to user
                                $redirect = $this->appendQS( $config['site'], 'showeBasketResult', $basket['trackKey'] );
                                @header( "Location: $redirect" );
                                exit( "<script>document.location='$redirect';</script>" );
                            }
                            else
                            {
                                $mihanphpErrors[] = $verification['error'];
                            }
                        }
                    }
                }
            }
        }
    }

    function search()
    {
        
    }

    function showeBasketResult()
    {
        global $config, $show_posts, $d, $tpl;
        if ( empty( $_GET['showeBasketResult'] ) )
        {
            $this->redirect( $config['site'] . '?ebasket' );
        }
        $trackKey = safe( $_GET['showeBasketResult'] );
        $basket   = $d->Query( "SELECT * FROM `ebasket` WHERE `status`!=0 AND `trackKey`='$trackKey'" );
        if ( $d->getRows( $basket ) != 1 )
        {
            $this->redirect( $config['site'] . '?ebasket' );
        }
        $basket     = $d->fetch( $basket );
        $basketID   = $basket['basketID'];
        $show_posts = false;
        $itpl       = new samaneh();
        $itpl->load( 'plugins/eshop/showeBasketResult.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $eproducts  = $d->Query( "SELECT * FROM `ebasket_items` WHERE `basketID`='$basketID'" );
        if ( $d->getRows( $eproducts ) > 0 )
        {
            $row  = 0;
            while ( $data = $d->fetch( $eproducts ) )
            {
                $product  = $this->getProduct( $data['productID'] );
                $category = $d->Query( "SELECT `afterPayment` FROM `eproduct_categories` WHERE `categoryID`='$product[category]' LIMIT 1 " );
                $category = $d->fetch( $category );
                //generate download links !
                $time     = time();
                $hash     = $this->generateKey( $data['productID'], $time );
                $url      = $config['site'] . "index.php?module=eshop&eshopDownload=$product[productID]&key=$hash&time=$time";
                $itpl->block( 'eproducts', array(
                    'row'         => ++$row,
                    'product'     => $product['title'],
                    'productUrl'  => $this->getProductUrl( $data['productID'] ),
                    'price'       => number_format( $product['price'] ),
                    'totalPrice'  => number_format( $data['price'] ),
                    'count'       => number_format( $data['count'] ),
                    'description' => (empty( $category['afterPayment'] )) ? '---' : $category['afterPayment'],
                    'url'         => $url
                ) );
            }
            $itpl->assign( 'totalPrice', number_format( $basket['price'] ) );
            $itpl->block( 'message', array(
                'msg' => 'وضعیت پرداخت : ' . ( ($basket['paymentStatus'] == 1) ? 'پرداخت شده' : 'پرداخت نشده')
            ) );
            $itpl->block( 'message', array(
                'msg' => 'وضعیت سفارش : ' . $this->BasketStatuses[$basket['status']]
            ) );
            if ( !empty( $basket['adminStatus'] ) )
            {
                $itpl->block( 'message', array(
                    'msg' => 'پیام مدیر : ' . stripcslashes( htmlspecialchars_decode( $basket['adminStatus'] ) )
                ) );
            }
        }
        $tpl->block( 'mp', array(
            'sub_link' => $config['site'],
            'link'     => $config['site'],
            'subject'  => 'فروشگاه',
            'title'    => 'سبد خرید',
            'body'     => $itpl->dontshowit()
        ) );
    }

    function viewBasket()
    {
        global $d, $tpl, $config, $show_posts, $single_post;
        $show_posts  = false;
        $single_post = true;
        $itpl        = new samaneh();
        $itpl->load( 'plugins/eshop/viewBasket.html' );
        if ( file_exists( ThemeDir . 'eshop-single.htm' ) )
        {
            $tpl->load( ThemeDir . 'eshop-single.htm' );
        }
        $itpl->assign( 'theme_url', core_theme_url );
        $basketID = $this->getBasketID();
        if ( is_null( $basketID ) )
        {
            $itpl->assign( 'noProduct', true );
        }
        else
        {
            //list all gateways
            $gateways = $this->listGateWays();
            foreach ( $gateways as $key => $value )
            {
                $itpl->block( 'gateways', array( 'name' => $key, 'title' => $value[0] ) );
            }
            $eproducts = $d->Query( "SELECT * FROM `ebasket_items` WHERE `basketID`='$basketID'" );
            if ( $d->getRows( $eproducts ) > 0 )
            {
                $basket = $d->Query( "SELECT * FROM `ebasket` WHERE `basketID`='$basketID' LIMIT 1" );
                $basket = $d->fetch( $basket );
                if ( $basket['status'] != 0 )
                {
                    //remove basketID session !
                    $_SESSION['ebasketID'] = '';
                    unset( $_SESSION['ebasketID'] );
                    $this->redirect( $this->appendQS( $config['site'], 'showeBasketResult', $basket['trackKey'] ) );
                }
                if ( isset( $_POST['payeBasket'] ) )
                {
                    $this->pay( $itpl, $basket );
                }
                else
                if ( isset( $_GET['callback'] ) )
                {
                    $this->callback( $itpl, $basket );
                }
                if ( isset( $_GET['delete'] ) && is_numeric( $_GET['delete'] ) )
                {
                    $this->deleteFromBasket( $_GET['delete'] );
                }
                $itpl->assign( 'haseproducts', true );

                $row  = 0;
                while ( $data = $d->fetch( $eproducts ) )
                {
                    $product = $this->getProduct( $data['productID'] );
                    $itpl->block( 'eproducts', array(
                        'row'        => ++$row,
                        'product'    => $product['title'],
                        'productUrl' => $this->getProductUrl( $data['productID'] ),
                        'price'      => number_format( $product['price'] ),
                        'totalPrice' => number_format( $data['price'] ),
                        'count'      => number_format( $data['count'] ),
                        'deleteUrl'  => $config['site'] . '?ebasket&delete=' . $data['productID']
                    ) );
                }
                $itpl->assign( array(
                    'totalPrice' => number_format( $basket['price'] ),
                    'address'    => !empty( $_POST['address'] ) ? $_POST['address'] : $basket['address'],
                    'fullname'   => !empty( $_POST['fullname'] ) ? $_POST['fullname'] : $basket['fullname'],
                    'tell'       => !empty( $_POST['tell'] ) ? $_POST['tell'] : $basket['tell'],
                    'zip'        => !empty( $_POST['zip'] ) ? $_POST['zip'] : $basket['zip'],
                    'mobile'     => !empty( $_POST['mobile'] ) ? $_POST['mobile'] : $basket['mobile'],
                    'email'      => !empty( $_POST['email'] ) ? $_POST['email'] : $basket['email'],
                ) );
            }
            else
            {
                $itpl->assign( 'noProduct', true );
            }
        }
        $tpl->block( 'mp', array(
            'subject'  => 'فروشگاه',
            'sub_link' => $config['site'],
            'link'     => $config['site'],
            'title'    => 'سبد خرید',
            'body'     => $itpl->dontshowit()
        ) );
    }

    function sideBareBasket()
    {
        global $d, $config;
        $itpl     = new samaneh();
        $itpl->load( 'plugins/eshop/sideBareBasket.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $basketID = $this->getBasketID();
        if ( is_null( $basketID ) )
        {
            $itpl->assign( 'noProduct', true );
        }
        else
        {
            $eproducts = $d->Query( "SELECT * FROM `ebasket_items` WHERE `basketID`='$basketID'" );
            if ( $d->getRows( $eproducts ) > 0 )
            {
                $basket = $d->Query( "SELECT * FROM `ebasket` WHERE `basketID`='$basketID' LIMIT 1" );
                $basket = $d->fetch( $basket );
                $itpl->assign( 'haseproducts', true );

                $row  = 0;
                while ( $data = $d->fetch( $eproducts ) )
                {
                    $product = $this->getProduct( $data['productID'] );
                    $itpl->block( 'eproducts', array(
                        'row'        => ++$row,
                        'product'    => $product['title'],
                        'productUrl' => $this->getProductUrl( $data['productID'] ),
                        'price'      => number_format( $product['price'] ),
                        'totalPrice' => number_format( $data['price'] ),
                        'count'      => number_format( $data['count'] ),
                        'deleteUrl'  => $config['site'] . '?ebasket&delete=' . $data['productID']
                    ) );
                }
                $itpl->assign( 'totalPrice', number_format( $basket['price'] ) );
            }
            else
            {
                $itpl->assign( 'noProduct', true );
            }
        }
        return $itpl->dontshowit();
    }

    function esideBarStatus()
    {
        $itpl = new samaneh();
        $itpl->load( 'plugins/eshop/esideBarStatus.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        if ( isset( $_POST['trackKey'] ) )
        {
            $trackKey = safe( $_POST['trackKey'] );
            global $d;
            $basket   = $d->Query( "SELECT `status`,`adminStatus` FROM `ebasket` WHERE `trackKey`='$trackKey' LIMIT 1" );
            $message  = 'کد رهگیری نامعتبر است.';
            if ( $d->getRows( $basket ) === 1 )
            {
                $basket  = $d->fetch( $basket );
                $message = $this->BasketStatuses[$basket['status']];
                if ( !empty( $basket['adminStatus'] ) )
                {
                    $message .= '<hr />' . stripcslashes( htmlspecialchars_decode( $basket['adminStatus'] ) );
                }
            }
            $itpl->assign( array( 'result' => true, 'message' => $message ) );
        }
        return $itpl->dontshowit();
    }

    static function curPageURL()
    {
        $pageURL = 'http';
        if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" )
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ( $_SERVER["SERVER_PORT"] != "80" )
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . '/' . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    static function appendQS( $url, $name, $value )
    {
        $url       = preg_replace( "#&?$name=[^&]+#", '', $url );
        $separator = (parse_url( $url, PHP_URL_QUERY ) == NULL) ? '?' : '&';
        $url .= $separator . $name . '=' . $value;
        return $url;
    }

    function getProductUrl( $productID )
    {
        global $config;
        return $this->appendQS( $config['site'], 'eproductID', $productID );
    }

    function listGateWays()
    {
        $allgateways = array(
            'payline'  => 'پی لاین',
            'zarinpal' => 'زرین پال',
            'parsian'  => 'پارسیان',
            'melli'    => 'ملی',
            'pasargad' => 'پاسارگاد',
            'saman'    => 'سامان',
        );
        $gateways    = glob( dirname( __FILE__ ) . '/gateways/*.php' );
        usort( $gateways, function($a, $b)
        {
            $a = preg_replace( '/[a-z]/i', '', $a );
            $b = preg_replace( '/[a-z]/i', '', $b );
            return strcmp( $a, $b );
        } );
        $out = array();
        foreach ( $allgateways as $key => $value )
        {
            foreach ( $gateways as $k )
            {
                if ( strpos( $k, $key ) !== false )
                {
                    $name       = preg_replace( "#.*\/gateways/(.*)\.php#", "\\1", $k );
                    $name       = preg_replace( '/\d/', '', $name );
                    $out[$name] = array( $value, $k );
                }
            }
        }
        /*
          foreach ( $gateways as $index => $name )
          {
          $name = preg_replace( "#.*\/gateways/(.*)\.php#", "\\1", $name );
          $name = preg_replace( '/\d/', '', $name );
          if ( !empty( $allgateways[$name] ) )
          {
          $out[$name] = $allgateways[$name];
          }
          }
         */
        return $out;
    }

    function generateKey( $productID, $time = null )
    {
        if ( is_null( $time ) )
        {
            $time = time();
        }
        return sha1( md5( md5( privateKey . $time . $_SERVER['REMOTE_ADDR'] . $productID . 'mihanphp.com' ) . 'shahrokhian.com' . sha1( privateKey ) ) );
    }

    function redirect( $url )
    {
        if ( !headers_sent() )
        {
            header( "Location: $url" );
        }
        echo "<script>document.location='$url';</script>";
        exit;
    }

}

class ResumeDownload
{

    private $file;
    private $name;
    private $boundary;
    private $delay = 0;
    private $size  = 0;

    function __construct( $file, $delay = 0 )
    {
        $filea = $this->is_file( $file );
        if ( !$filea[0] )
        {
            header( "HTTP/1.1 400 Invalid Request" );
            die( "<h3>File Not Found</h3>" );
        }

        $this->size     = $filea[1];
        $this->file     = fopen( $file, "r" );
        $this->boundary = md5( $file );
        $this->delay    = $delay;
        $this->name     = basename( $file );
    }

    function is_file( $url, $size = null )
    {
        $curl          = curl_init( $url );
        $contentLength = 0;
        //don't fetch the actual page, you only want to check the connection is ok
        curl_setopt( $curl, CURLOPT_NOBODY, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        //do request
        $result        = curl_exec( $curl );

        $ret = false;

        //if request did not fail
        if ( $result !== false )
        {
            //if request was ok, check response code
            $statusCode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
            if ( preg_match( '/Content-Length: (\d+)/', $result, $matches ) )
            {
                $contentLength = ( int ) $matches[1];
            }
            if ( $statusCode == 200 )
            {
                $ret = true;
            }
        }

        curl_close( $curl );

        return array( $ret, $contentLength );
    }

    public function process()
    {
        $ranges = NULL;
        $t      = 0;
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_SERVER['HTTP_RANGE'] ) && $range  = stristr( trim( $_SERVER['HTTP_RANGE'] ), 'bytes=' ) )
        {
            $range  = substr( $range, 6 );
            $ranges = explode( ',', $range );
            $t      = count( $ranges );
        }

        header( "Accept-Ranges: bytes" );
        header( "Content-Type: application/octet-stream" );
        header( "Content-Transfer-Encoding: binary" );
        header( sprintf( 'Content-Disposition: attachment; filename="%s"', $this->name ) );

        if ( $t > 0 )
        {
            header( "HTTP/1.1 206 Partial content" );
            $t === 1 ? $this->pushSingle( $range ) : $this->pushMulti( $ranges );
        }
        else
        {
            header( "Content-Length: " . $this->size );
            $this->readFile();
        }

        flush();
    }

    private function pushSingle( $range )
    {
        $start = $end   = 0;
        $this->getRange( $range, $start, $end );
        header( "Content-Length: " . ($end - $start + 1) );
        header( sprintf( "Content-Range: bytes %d-%d/%d", $start, $end, $this->size ) );
        fseek( $this->file, $start );
        $this->readBuffer( $end - $start + 1 );
        $this->readFile();
    }

    private function pushMulti( $ranges )
    {
        $length = $start  = $end    = 0;
        $output = "";

        $tl          = "Content-type: application/octet-stream\r\n";
        $formatRange = "Content-range: bytes %d-%d/%d\r\n\r\n";

        foreach ( $ranges as $range )
        {
            $this->getRange( $range, $start, $end );
            $length += strlen( "\r\n--$this->boundary\r\n" );
            $length += strlen( $tl );
            $length += strlen( sprintf( $formatRange, $start, $end, $this->size ) );
            $length += $end - $start + 1;
        }
        $length += strlen( "\r\n--$this->boundary--\r\n" );
        header( "Content-Length: $length" );
        header( "Content-Type: multipart/x-byteranges; boundary=$this->boundary" );
        foreach ( $ranges as $range )
        {
            $this->getRange( $range, $start, $end );
            echo "\r\n--$this->boundary\r\n";
            echo $tl;
            echo sprintf( $formatRange, $start, $end, $this->size );
            fseek( $this->file, $start );
            $this->readBuffer( $end - $start + 1 );
        }
        echo "\r\n--$this->boundary--\r\n";
    }

    private function getRange( $range, &$start, &$end )
    {
        list($start, $end) = explode( '-', $range );

        $fileSize = $this->size;
        if ( $start == '' )
        {
            $tmp   = $end;
            $end   = $fileSize - 1;
            $start = $fileSize - $tmp;
            if ( $start < 0 )
                $start = 0;
        } else
        {
            if ( $end == '' || $end > $fileSize - 1 )
                $end = $fileSize - 1;
        }

        if ( $start > $end )
        {
            header( "Status: 416 Requested range not satisfiable" );
            header( "Content-Range: */" . $fileSize );
            exit();
        }

        return array(
            $start,
            $end
        );
    }

    private function readFile()
    {
        while ( !feof( $this->file ) )
        {
            echo fgets( $this->file );
            flush();
            usleep( $this->delay );
        }
    }

    private function readBuffer( $bytes, $size = 1024 )
    {
        $bytesLeft = $bytes;
        while ( $bytesLeft > 0 && !feof( $this->file ) )
        {
            $bytesLeft > $size ? $bytesRead = $size : $bytesRead = $bytesLeft;
            $bytesLeft -= $bytesRead;
            echo fread( $this->file, $bytesRead );
            flush();
            usleep( $this->delay );
        }
    }

}
