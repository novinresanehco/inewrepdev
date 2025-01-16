<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    exit;
}
$shop = new shop;
$tpl->assign( 'shopCategories', $shop->categoryList() );
$tpl->assign( 'recentProducts', $shop->recentProducts() );
$tpl->assign( 'listProducts', $shop->listProducts() );
$tpl->assign( 'sideBarBasket', $shop->sideBarBasket() );
$tpl->assign( 'bestProducts', $shop->bestProducts() );
$tpl->assign( 'sideBarStatus', $shop->sideBarStatus() );
$shop->proccessSearches();
if ( isset( $_POST['payBasket'] ) )
{
    $shop->prePayment();
}
if ( isset( $_GET['basket'] ) )
{
    $shop->viewBasket();
}
else
if ( !empty( $_GET['showBasketResult'] ) )
{
    $shop->showBasketResult();
}
else
if ( isset( $_GET['productID'] ) && is_numeric( $_GET['productID'] ) )
{
    $action = (isset( $_GET['action'] ) ) ? $_GET['action'] : '';

    switch ( $action )
    {
        case 'addToBasket':
            $shop->addTobasket( $_GET['productID'] );
            $shop->showProduct( $_GET['productID'] );

            break;

        default:
            //show product page
            $shop->showProduct( $_GET['productID'] );
            break;
    }
}

//send sms to admin after new payment
//search
class shop
{

    var $BasketStatuses = array( 'بررسی نشده', 'در حال بررسی', 'تایید اولیه', 'بسته بندی محصول', 'ارسال شده', 'برگشت خورده', 'انصراف' );

    //proccess search forms
    function proccessSearches()
    {
        global $d, $tpl, $config;
        $searches = $d->Query( "SELECT * FROM `shopsearch`" );
        while ( $data     = $d->fetch( $searches ) )
        {
            $itpl   = new samaneh();
            $itpl->load( 'plugins/shop/search.html' );
            $itpl->assign( 'theme_url', core_theme_url );
            $fields = json_decode( base64_decode( $data['fields'] ) );
            if ( is_array( $fields ) )
            {
                foreach ( $fields as $field )
                {
                    if ( $field != 'price' AND $field != 'title' )
                    {
                        //get field from db
                        $field = $d->Query( "SELECT `fieldID`,`title` FROM `category_fields` WHERE `fieldID`='$field' LIMIT 1" );
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
            $tpl->assign( 'search_' . $data['searchID'], $itpl->dontshowit() );
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
        if ( file_exists( ThemeDir . 'shop-single.htm' ) )
        {
            $tpl->load( ThemeDir . 'shop-single.htm' );
        }
        $product = $d->Query( "SELECT * FROM `products` WHERE `productID`='$productID' LIMIT 1" );
        if ( $d->getRows( $product ) !== 1 )
        {
            header( "Location: $config[site]" );
            exit;
        }
        $product      = $d->fetch( $product );
        $itpl         = new samaneh();
        $itpl->load( 'plugins/shop/singleProduct.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        //product images
        $images       = $d->Query( "SELECT `imageUrl` FROM `product_images` WHERE `productID`='$product[productID]'" );
        $primaryImage = '';
        $imgData      = $d->fetch( $images );
        $primaryImage = $imgData['imageUrl'];


        $category = $d->Query( "SELECT `title` FROM `product_categories` WHERE `categoryID`='$product[category]' LIMIT 1" );
        $category = $d->fetch( $category );
        //$categoryFields = $category['fields'];
        $category = $category['title'];
        $bData    = array(
            'title'       => $product['title'],
            'description' => stripcslashes( htmlspecialchars_decode( $product['description'] ) ),
            'price'       => $product['price'],
            'fprice'      => number_format( $product['price'] ),
            'category'    => $category,
            'stock'       => $product['stock'],
            'productID'   => $product['productID'],
            'url'         => $this->getProductUrl( $product['productID'] )
        );
        if ( !empty( $primaryImage ) )
        {
            $bData['primaryImage']    = $primaryImage;
            $bData['hasPrimaryImage'] = true;
        }
        $itpl->block( 'products', $bData );
        while ( $imgData = $d->fetch( $images ) )
        {
            $itpl->block( 'productImages_' . $product['productID'], array(
                'image' => $imgData['imageUrl']
            ) );
        }
        $fieldsDB = $d->Query( "SELECT * FROM `category_fields` WHERE `categoryID`='$product[category]'" );
        while ( $field    = $d->fetch( $fieldsDB ) )
        {
            $Fdata = $d->Query( "SELECT `value` FROM `product_fields` WHERE `productID`='$product[productID]' AND `fieldID`='$field[fieldID]' LIMIT 1" );
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
			'title'	   => $product['title'],
            'body'     => $itpl->dontshowit(),
            'link'     => $config['site'],
            'sub_link' => $config['site'],
            'subject'  => $category
        ) );
    }

    //recent Sidebar
    function recentProducts()
    {
        global $d;
        $itpl  = new samaneh();
        $itpl->load( 'plugins/shop/recentProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $query = "SELECT * FROM `products` WHERE `delete`=0 LIMIT 10";
        $query = $d->Query( $query );
        while ( $data  = $d->fetch( $query ) )
        {
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'stock'       => $data['stock'],
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
			$images       = $d->Query( "SELECT `imageUrl` FROM `product_images` WHERE `productID`='$data[productID]'" );
            $primaryImage = '';
            $imgData      = $d->fetch( $images );
            $primaryImage = $imgData['imageUrl'];
			
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'recentProducts', $bData );
        }
        return $itpl->dontshowit();
    }

    //best products list
    function bestProducts()
    {
        global $d;
        $itpl  = new samaneh();
        $itpl->load( 'plugins/shop/bestProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $query = "select `products`.*, count(*) from `basket_items` inner join `products` on products.productID=basket_items.productID group by products.productID order by count(*) desc;";
        $query = $d->Query( $query );
        while ( $data  = $d->fetch( $query ) )
        {
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'stock'       => $data['stock'],
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
			$images       = $d->Query( "SELECT `imageUrl` FROM `product_images` WHERE `productID`='$data[productID]'" );
            $primaryImage = '';
            $imgData      = $d->fetch( $images );
            $primaryImage = $imgData['imageUrl'];
		
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'bestProducts', $bData );
        }
        return $itpl->dontshowit();
    }

//product lists
    function listProducts()
    {
        global $d;
        $itpl = new samaneh();
        $itpl->load( 'plugins/shop/shopProducts.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        //list based on current category or list recents
        if ( isset( $_GET['shopCategory'] ) && is_numeric( $_GET['shopCategory'] ) )
        {
            $shopCategory = ( int ) $_GET['shopCategory'];
            $query        = "SELECT * FROM `products` WHERE `delete`=0 AND `category`='$shopCategory'";
            $queryCount   = "SELECT COUNT(*) as total FROM `products` WHERE `delete`=0 AND `category`='$shopCategory'";
        }
        else
        {
            $query      = "SELECT * FROM `products` WHERE `delete`=0";
            $queryCount = "SELECT COUNT(*) as total FROM `products` WHERE `delete`=0";
        }
        if ( isset( $_GET ) && is_array( $_GET ) )
        {
            foreach ( $_GET as $key => $value )
            {
                if ( !empty( $value ) && substr( $key, 0, 7 ) == 'search_' )
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
                        $query .= " AND `productID` IN ( SELECT `productID` FROM `product_fields` WHERE `fieldID`='$key'  AND `value` LIKE '%$value%' ) ";
                        $queryCount .= " AND `productID` IN ( SELECT `productID` FROM `product_fields` WHERE `fieldID`='$key'  AND `value` LIKE '%$value%' ) ";
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
        $products       = $d->Query( $query );
        $categories     = array();
        $categoryFields = array();
        while ( $data           = $d->fetch( $products ) )
        {
            //product images
            $images       = $d->Query( "SELECT `imageUrl` FROM `product_images` WHERE `productID`='$data[productID]'" );
            $primaryImage = '';
            $imgData      = $d->fetch( $images );
            $primaryImage = $imgData['imageUrl'];


            if ( empty( $categories[$data['category']] ) )
            {
                $categories[$data['category']]     = $d->Query( "SELECT `title` FROM `product_categories` WHERE `categoryID`='$data[category]' LIMIT 1" );
                $categories[$data['category']]     = $d->fetch( $categories[$data['category']] );
                $categoryFields[$data['category']] = $d->fetch( $d->Query( "SELECT * FROM `category_fields` WHERE `categoryID`='$data[category]'" ) );
                $categories[$data['category']]     = $categories[$data['category']]['title'];
            }
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'category'    => $categories[$data['category']],
                'stock'       => $data['stock'],
                'productID'   => $data['productID'],
                'url'         => $this->getProductUrl( $data['productID'] )
            );
            if ( !empty( $primaryImage ) )
            {
                $bData['primaryImage']    = $primaryImage;
                $bData['hasPrimaryImage'] = true;
            }
            $itpl->block( 'products', $bData );
            while ( $imgData = $d->fetch( $images ) )
            {
                $itpl->block( 'productImages_' . $data['productID'], array(
                    'image' => $imgData['imageUrl']
                ) );
            }

            $fieldsDB = $d->Query( "SELECT * FROM `category_fields` WHERE `categoryID`='$data[category]'" );
            while ( $field    = $d->fetch( $fieldsDB ) )
            {
                $Fdata = $d->Query( "SELECT `value` FROM `product_fields` WHERE `productID`='$data[productID]' AND `fieldID`='$field[fieldID]' LIMIT 1" );
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
        $itpl->load( 'plugins/shop/shopSideBarCategories.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $categories = $d->Query( "SELECT * FROM `product_categories` WHERE `parentID`=0" );
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
            $url           = preg_replace( '#shopCategory=[0-9]+#iUs', '', $url );
            $url           = preg_replace( '#\??productID=[0-9]+#iUs', '', $url );
            $ddd['url']    = $this->appendQs( $url, 'shopCategory', $data['categoryID'] );
            $subCategories = $d->Query( "SELECT * FROM `product_categories` WHERE `parentID`='$data[categoryID]'" );
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
                    'subur;'          => $this->appendQs( $url, 'shopCategory', $idata['categoryID'] )
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
            $product = $d->Query( "SELECT `productID` FROM `products` WHERE `delete`=0 AND `productID`='$productID' LIMIT 1" );
            return ( $d->getRows( $product ) === 1);
        }
        return false;
    }

    //get Bassket ID
    function getBasketID()
    {
        global $d;
        $basketID = null;
        if ( !empty( $_SESSION['basketID'] ) && is_numeric( $_SESSION['basketID'] ) )
        {
            $basketID = $_SESSION['basketID'];
        }
        else
        {
            //if user is logged in ~> get last unpayed basket !
            $user = new user();
            if ( $user->checklogin() )
            {
                $info = $user->info();
                $tmp  = $d->Query( "SELECT `basketID` FROM `basket` WHERE `status`=0 AND `userID`='$info[u_id]' LIMIT 1" );
                if ( $d->getRows( $tmp ) === 1 )
                {
                    $tmp                  = $d->fetch( $tmp );
                    $basketID             = $tmp['basketID'];
                    $_SESSION['basketID'] = $basketID;
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
			$count = 1;
			if( isset( $_GET['count'] ) && is_numeric( $_GET['count'] ) && $_GET['count'] > 0 )
			{
				$count = $_GET['count'];
			}
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
                $d->iQuery( 'basket', array(
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
                    'price'       => $count * $product['price'],
                ) );
                $_SESSION['basketID'] = $basketID             = $d->last();
                //add product to basket list
                $d->iQuery( "basket_items", array(
                    'basketID'  => $basketID,
                    'productID' => $productID,
                    'count'     => $count,
                    'price'     => $product['price']
                ) );
            }
            else
            {
				$price = $count * $product['price'];
                //update basket
                $d->Query( "UPDATE `basket` SET `price`=$price WHERE `basketID`=$basketID LIMIT 1" );
                $isInBasket = $d->Query( "SELECT `itemID` FROM `basket_items` WHERE `basketID`=$basketID AND `productID`=$productID LIMIT 1" );
                if ( $d->getRows( $isInBasket ) === 1 )
                {
					
                    //update count and price
                    $d->Query( "UPDATE `basket_items` SET `count`=$count WHERE `basketID`=$basketID AND `productID`=$productID LIMIT 1" );
                }
                else
                {
                    //add product to basket list
                    $d->iQuery( "basket_items", array(
                        'basketID'  => $basketID,
                        'productID' => $productID,
                        'count'     => $count,
                        'price'     => $product['price']
                    ) );
                }
            }
        }
		if( !empty( $_GET['redirect'] ) )
		{
			header('Location: ' . urldecode( $_GET['redirect'] ));
die('<script>document.location="'.$config['site'].'?basket";</script>');
		}			
    }

    function getProduct( $productID )
    {
        if ( $this->isValidProductID( $productID ) )
        {
            global $d;
            $product = $d->Query( "SELECT * FROM `products` WHERE `delete`=0 AND `productID`='$productID' LIMIT 1" );
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
            $d->Query( "DELETE FROM `basket_items` WHERE `basketID`='$basketID' AND `productID`='$productID' LIMIT 1" );
            //update basket stock !
            $price = $d->Query( "SELECT SUM(`price`) as `total` FROM `basket_items` WHERE `basketID`='$basketID' " );
            $price = $d->fetch( $price );
            $price = $price['total'];
            $d->Query( "UPDATE `basket` SET `price`='$price' WHERE basketID=$basketID LIMIT 1" );
            global $config;
            header( "Location: $config[site]?basket" );
        }
    }

    //creat transaction Key
    function transactionKey()
    {
        // return rand
        return strtolower( GEN( 5 ) . '-' . GEN( 3 ) . '-' . GEN( 7 ) );
    }

    function prePayment()
    {
        global $config, $d;
        if ( is_null( $this->getBasketID() ) )
        {
            header( "Location: $config[site]?basket" );
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
                    $d->uQuery( 'basket', array(
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
                        $d->iQuery( 'basket_transactions', $data );
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
                $transaction     = $d->Query( "SELECT * FROM `basket_transactions` WHERE `transactionCode`='$tranasctionCode' LIMIT 1" );
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
                        $redirect = $this->appendQS( $config['site'], 'showBasketResult', $basket['trackKey'] );
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
                                $d->uQuery( 'basket_transactions', array(
                                    'status' => 1,
                                        ), " `transactionCode`='$tranasctionCode' LIMIT 1" );
                                //update basket status
                                $d->uQuery( 'basket', array(
                                    'status'        => 1,
                                    'paymentStatus' => 1,
                                        ), " `basketID`='$basket[basketID]' LIMIT 1" );
                                $basketItems = $d->Query( "SELECT * FROM `basket_items` WHERE `basketID`='$basket[basketID]' " );
                                while ( $dd          = $d->fetch( $basketItems ) )
                                {
                                    //update product count
                                    $d->Query( "UPDATE `products` SET `stock`=`stock`-$dd[count] WHERE `productID`='$dd[productID]' LIMIT 1" );
                                }
                                //send email notification
                                send_mail( $config['email'], $config['email'], 'خرید جدیدی از وب سایت ' . $config['site'] . 'انجام شد.<br />شناسه پیگری: ' . $basket['trackKey'] );
                                //send sms
                                if ( function_exists( 'send_sms' ) )
                                {
                                    send_sms( $config['smsTo'], 'خرید جدیدی از وب سایت ' . $config['site'] . 'انجام شد.<br />شناسه پیگری: ' . $basket['trackKey'] );
                                }
                                //redirect user and show paid items to user
                                $redirect = $this->appendQS( $config['site'], 'showBasketResult', $basket['trackKey'] );
                                @header( "Location: $resirect" );
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

    function showBasketResult()
    {
        global $config, $show_posts, $d, $tpl;
        if ( empty( $_GET['showBasketResult'] ) )
        {
            $this->redirect( $config['site'] . '?basket' );
        }
        $trackKey = safe( $_GET['showBasketResult'] );
        $basket   = $d->Query( "SELECT * FROM `basket` WHERE `status`!=0 AND `trackKey`='$trackKey'" );
        if ( $d->getRows( $basket ) != 1 )
        {
            $this->redirect( $config['site'] . '?basket' );
        }
        $basket     = $d->fetch( $basket );
        $basketID   = $basket['basketID'];
        $show_posts = false;
        $itpl       = new samaneh();
        $itpl->load( 'plugins/shop/showBasketResult.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $products   = $d->Query( "SELECT * FROM `basket_items` WHERE `basketID`='$basketID'" );
        if ( $d->getRows( $products ) > 0 )
        {
            $row  = 0;
            while ( $data = $d->fetch( $products ) )
            {
                $product  = $this->getProduct( $data['productID'] );
                $category = $d->Query( "SELECT `afterPayment` FROM `product_categories` WHERE `categoryID`='$product[category]' LIMIT 1 " );
                $category = $d->fetch( $category );

                $itpl->block( 'products', array(
                    'row'         => ++$row,
                    'product'     => $product['title'],
                    'productUrl'  => $this->getProductUrl( $data['productID'] ),
                    'price'       => number_format( $product['price'] ),
                    'totalPrice'  => number_format( $data['price'] ),
                    'count'       => number_format( $data['count'] ),
                    'description' => (empty( $category['afterPayment'] )) ? '---' : $category['afterPayment'],
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
        $itpl->load( 'plugins/shop/viewBasket.html' );
        if ( file_exists( ThemeDir . 'shop-single.htm' ) )
        {
            $tpl->load( ThemeDir . 'shop-single.htm' );
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
            $products = $d->Query( "SELECT * FROM `basket_items` WHERE `basketID`='$basketID'" );
            if ( $d->getRows( $products ) > 0 )
            {
                $basket = $d->Query( "SELECT * FROM `basket` WHERE `basketID`='$basketID' LIMIT 1" );
                $basket = $d->fetch( $basket );
                if ( $basket['status'] != 0 )
                {
                    //remove basketID session !
                    $_SESSION['basketID'] = '';
                    unset( $_SESSION['basketID'] );
                    $this->redirect( $this->appendQS( $config['site'], 'showBasketResult', $basket['trackKey'] ) );
                }
                if ( isset( $_POST['payBasket'] ) )
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
                $itpl->assign( 'hasProducts', true );

                $row  = 0;
                while ( $data = $d->fetch( $products ) )
                {
                    $product = $this->getProduct( $data['productID'] );
                    $itpl->block( 'products', array(
                        'row'        => ++$row,
                        'productID'        => $data['productID'],
                        'product'    => $product['title'],
                        'productUrl' => $this->getProductUrl( $data['productID'] ),
                        'price'      => number_format( $product['price'] ),
                        'totalPrice' => number_format( $data['price'] * $data['count'] ) ,
                        'count'      => number_format( $data['count'] ),
                        'deleteUrl'  => $config['site'] . '?basket&delete=' . $data['productID']
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
                $itpl->assign( 'curPageURLe', urlencode( curPageURL() ) );
        }
        $tpl->block( 'mp', array(
            'subject'  => 'فروشگاه',
            'sub_link' => $config['site'],
            'link'     => $config['site'],
            'title'    => 'سبد خرید',
            'body'     => $itpl->dontshowit()
        ) );
    }

    function sideBarBasket()
    {
        global $d, $config;
        $itpl     = new samaneh();
        $itpl->load( 'plugins/shop/sideBarBasket.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        $basketID = $this->getBasketID();
        if ( is_null( $basketID ) )
        {
            $itpl->assign( 'noProduct', true );
        }
        else
        {
            $products = $d->Query( "SELECT * FROM `basket_items` WHERE `basketID`='$basketID'" );
            if ( $d->getRows( $products ) > 0 )
            {
                $basket = $d->Query( "SELECT * FROM `basket` WHERE `basketID`='$basketID' LIMIT 1" );
                $basket = $d->fetch( $basket );
                $itpl->assign( 'hasProducts', true );

                $row  = 0;
                while ( $data = $d->fetch( $products ) )
                {
                    $product = $this->getProduct( $data['productID'] );
                    $itpl->block( 'products', array(
                        'row'        => ++$row,
                        'product'    => $product['title'],
                        'productUrl' => $this->getProductUrl( $data['productID'] ),
                        'price'      => number_format( $product['price'] ),
                        'totalPrice' => number_format( $data['price'] ),
                        'count'      => number_format( $data['count'] ),
                        'deleteUrl'  => $config['site'] . '?basket&delete=' . $data['productID']
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

    function sideBarStatus()
    {
        $itpl = new samaneh();
        $itpl->load( 'plugins/shop/sideBarStatus.html' );
        $itpl->assign( 'theme_url', core_theme_url );
        if ( isset( $_POST['trackKey'] ) )
        {
            $trackKey = safe( $_POST['trackKey'] );
            global $d;
            $basket   = $d->Query( "SELECT `status`,`adminStatus` FROM `basket` WHERE `trackKey`='$trackKey' LIMIT 1" );
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
        return $this->appendQS( $config['site'], 'productID', $productID );
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
