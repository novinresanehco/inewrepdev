<?php

if ( !defined( 'plugins_admin_area' ) OR !isset( $permissions['access_admin_area'] ) OR $permissions['access_admin_area'] != '1' )
{
    die( 'invalid access' );
}
$information    = array(
    'name'        => 'فروشگاه اینترنتی',
    'provider'    => 'رضا شاهرخیان',
    'providerurl' => 'http://www.shahrokhian.com',
    'install'     => true,
    'uninstall'   => true,
    'activate'    => true,
    'inactivate'  => true,
    'data'        => array(
        'icon' => array( 'name' => 'آیکن', 'value' => '', 'type' => 'icon' ),
    )
);
$BasketStatuses = array( 'بررسی نشده', 'در حال بررسی', 'تایید اولیه', 'بسته بندی محصول', 'ارسال شده', 'برگشت خورده', 'انصراف' );
$tpl->assign( 'first', '' );
if ( defined( 'methods' ) )
{
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
	
    function defaultop()
    {
        global $tpl, $d, $information, $config;
        $itpl = new samaneh();
        $ac   = (!empty( $_GET['ac'] )) ? $_GET['ac'] : 'default';
        switch ( $ac )
        {
			case 'settings':
                $itpl->load( '../plugins/eshop/settings.html' );
                $itpl->assign( 'site', $config['site'] );
				
                //list available gateways
				$gateways = listGateWays();
				foreach( $gateways as $name => $gateway )
				{
					list( $title, $file ) = $gateway;
					include_once( $file );
					$class = "payment_" . $name;
					$obj = new $class;
					$variables = get_object_vars( $obj );
					$itpl->block( 'banks', array( 'gateway' => $name, 'gateway_title' => $title ) );
					foreach( $variables as $key => $value )
					{
						$value = isset( $config[$name . '_' . $key] ) ? $config[$name . '_' . $key] : $value;
						$itpl->block( 'gateway_' . $name, array( 'field' => $key, 'value' => $value ) );
					}
				}

                break;
				case 'saveSettings':
					header( 'Content-Type: application/json' );
                    $out          = array( 'success' => false );
                    $out['error'] = 'working';
					$gateways = listGateWays();
					foreach( $gateways as $name => $gateway )
					{
						foreach( $_POST[$name] as $key => $value )
						{
							saveconfig( $name . '_' . $key, htmlspecialchars( $value ) );
						}
					}
					$out['success'] = true;
					$out['error'] = '';
					exit( json_encode( $out ) );	

                break;
            case 'reports':
                $itpl->load( '../plugins/eshop/reports.html' );
                $itpl->assign( 'site', $config['site'] );
                $eproducts  = $d->Query( "SELECT SUM(`count`) as `total`,`productID` FROM `ebasket_items` WHERE `basketID` IN (SELECT `basketID` FROM `ebasket` WHERE `paymentStatus`=1) GROUP BY `productID`" );
                $labels     = '';
                $counts     = '';
                $categories = array();
                $totalSales = 0;
                while ( $data       = $d->fetch( $eproducts ) )
                {
                    $product = $d->Query( "SELECT `title`,`category` FROM `eproducts` WHERE `productID`='$data[productID]' LIMIT 1" );
                    $product = $d->fetch( $product );
                    $labels .= "'$product[title]',";
                    $counts .= $data['total'] . ',';
                    if ( !isset( $categories[$product['category']] ) )
                    {
                        $categories[$product['category']] = 0;
                    }
                    $totalSales += $data['total'];
                    $categories[$product['category']] += $data['total'];
                }
                $categoryTmp      = '';
                $categoryCountTmp = '';
                foreach ( $categories as $catID => $total )
                {
                    $category = $d->Query( "SELECT `title` FROM `eproduct_categories` WHERE `categoryID`='$catID' LIMIT 1" );
                    $category = $d->fetch( $category );
                    $categoryTmp .= "'$category[title]',";
                    $categoryCountTmp .= $total . ',';
                }
                $labels           = rtrim( $labels, "," );
                $counts           = rtrim( $counts, "," );
                $categoryTmp      = rtrim( $categoryTmp, "," );
                $categoryCountTmp = rtrim( $categoryCountTmp, "," );
                $itpl->assign( array(
                    'labels'          => $labels,
                    'count'           => $counts,
                    'categories'      => $categoryTmp,
                    'categoriesCount' => $categoryCountTmp,
                    'totalSales'      => $totalSales
                ) );

                break;
            case 'search':
                $itpl->load( '../plugins/eshop/advancedSearch.html' );
                if ( empty( $_GET['category'] ) OR !is_numeric( $_GET['category'] ) )
                {
                    $itpl->assign( 'category', true );
                    $categories = $d->Query( 'SELECT * FROM `eproduct_categories` WHERE `parentID`=0' );
                    while ( $data       = $d->fetch( $categories ) )
                    {
                        $itpl->block( 'categories', array(
                            'title' => $data['title'],
                            'id'    => $data['categoryID'],
                        ) );
                        $subCategories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `parentID`='$data[categoryID]'" );
                        while ( $idata         = $d->fetch( $subCategories ) )
                        {
                            $itpl->block( 'categories', array(
                                'title' => '----------' . $idata['title'],
                                'id'    => $idata['categoryID'],
                            ) );
                        }
                    }
                }
                else
                {
                    //make sure category ID is valid
                    $categoryID = intval( $_GET['category'] );
                    $category   = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `categoryID`='$categoryID' LIMIT 1" );
                    if ( $d->getRows( $category ) !== 1 )
                    {
                        header( "Location: plugins.php?plugin=eshop&ac=search" );
                        exit( 'invalid category' );
                    }
                    $itpl->assign( 'categorySearch', true );
                    $category       = $d->fetch( $category );
                    $itpl->assign( $category );
                    $categoryFields = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$categoryID' " );
                    while ( $data           = $d->fetch( $categoryFields ) )
                    {
                        $itpl->block( 'fields', array(
                            'fieldID' => trim( $data['fieldID'] ),
                            'title'   => trim( $data['title'] ),
                            'name'    => trim( $data['name'] ),
                        ) );
                    }
                    if ( isset( $_POST['creatSearch'] ) )
                    {
                        $_POST['search'] = (isset( $_POST['search'] )) ? $_POST['search'] : array();
                        $d->iQuery( 'eshopsearch', array(
                            'categoryID' => $categoryID,
                            'fields'     => base64_encode( json_encode( ( array ) $_POST['search'] ) )
                        ) );
                        $search          = $d->last();
                        $itpl->block( 'messages', array(
                            'message' => "فرم جستجو با موفقیت ایجاد شد : [esearch_$search]<br />کد جستجو در مطالب و صفحات و بلوک ها و قالب قابل استفاده است.",
                        ) );
                    }
                }
                break;
            case 'product':
                if ( isset( $_GET['delete'] ) && is_numeric( $_GET['delete'] ) )
                {
                    $id = ( int ) $_GET['delete'];
                    $p  = $d->Query( "SELECT `productID` FROM `ebasket_items` WHERE `productID`='$id' LIMIT 1" );
                    if ( $d->getRows( $p ) === 0 )
                    {
                        //delete product
                        $d->Query( "DELETE FROM `eproducts` WHERE `productID`='$id' LIMIT 1" );
                        //delete images
                        $d->Query( "DELETE FROM `eproduct_images` WHERE `productID`='$id' " );
                    }
                    else
                    {
                        $d->Query( "UPDATE `eproducts` SET `delete`='1'  WHERE `productID`='$id' LIMIT 1" );
                    }
                }
                if ( isset( $_POST['list'] ) )
                {
                    $itpl->load( '../plugins/eshop/productList.html' );
                    $categories = $d->Query( 'SELECT `eproducts`.*,`eproduct_categories`.`title` as `cat` FROM `eproducts` inner join'
                            . '  `eproduct_categories` on `eproducts`.`category` = `eproduct_categories`.`categoryID` WHERE `eproducts`.`delete`=0' );
                    $row        = 0;
                    while ( $data       = $d->fetch( $categories ) )
                    {
                        $itpl->block( 'eproducts', array(
                            'name'      => $data['title'],
                            'productID' => $data['productID'],
                            'price'     => number_format( trim( $data['price'] ) ),
                            'category'  => $data['cat'],
                            'row'       => ++$row,
                        ) );
                    }
                    $itpl->showit();
                    exit;
                }
                else
                if ( isset( $_GET['edit'] ) && is_numeric( $_GET['edit'] ) )
                {
                    $editID  = ( int ) $_GET['edit'];
                    $product = $d->Query( "SELECT * FROM `eproducts` WHERE `productID`='$editID' LIMIT 1" );
                    if ( $d->getRows( $product ) !== 1 )
                    {
                        header( 'Location: plugins.php?plugin=eshop&ac=product' );
                        exit;
                    }
                    $product = $d->fetch( $product );
                    //edit product
                    if ( isset( $_POST['productName'] ) )
                    {
                        header( 'Content-Type: application/json' );
                        $out          = array( 'success' => false );
                        $out['error'] = 'working';
                        if ( empty( $_POST['productName'] ) OR empty( $_POST['description_text'] ) OR !is_numeric( $_POST['price'] ) OR $_POST['price'] < 0 )
                        {
                            $out['error'] = 'لطفا همه فیلدهای ستاره دار را تکمیل کنید.';
                        }
                        else
                        {
                            $d->uQuery( 'eproducts', array(
                                'title'       => $_POST['productName'],
                                'description' => $_POST['description_text'],
                                'price'       => $_POST['price'],
                                'link'        => $_POST['link'],
                                    //'productValues' => json_encode( $_POST['fields'] )
                                    ), "`productID`='$editID' LIMIT 1" );
                            //update product values
                            $_POST['fields'] = (isset( $_POST['fields'] ) && is_array( $_POST['fields'] )) ? $_POST['fields'] : array();
                            foreach ( $_POST['fields'] as $field => $value )
                            {
                                $value         = trim( $value );
                                $categoryField = $d->Query( "SELECT `fieldID` FROM `ecategory_fields` WHERE `categoryID`='$product[category]' AND `name`='$field' LIMIT 1" );
                                if ( $d->getRows( $categoryField ) > 0 )
                                {
                                    $fieldID = $d->fetch( $categoryField );
                                    $fieldID = $fieldID['fieldID'];
                                    if ( !empty( $value ) )
                                    {
                                        //insert or update !
                                        if ( $d->getRows( $q = $d->Query( "SELECT * FROM `eproduct_fields` WHERE `productID`='$editID' AND `fieldID`='$fieldID' LIMIT 1" ) ) > 0 )
                                        {
                                            //update
                                            $q = $d->fetch( $q );
                                            $d->uQuery( 'eproduct_fields', array(
                                                'value' => $value,
                                                    ), " `fieldID`='$fieldID' AND `productID`='$editID' LIMIT 1"
                                            );
                                        }
                                        else
                                        {
                                            //insert
                                            $d->iQuery( 'eproduct_fields', array(
                                                'productID' => $editID,
                                                'fieldID'   => $fieldID,
                                                'value'     => $value
                                            ) );
                                        }
                                    }
                                    else
                                    {
                                        $d->Query( "DELETE FROM `eproduct_fields` WHERE `productID`='$editID' AND `fieldID`='$fieldID' LIMIT 1" );
                                    }
                                }
                            }
                            //remove old images
                            $d->Query( "DELETE FROM `eproduct_images` WHERE `productID`='$editID'" );
                            //save images !
                            $images = (isset( $_POST['images'] ) && is_array( $_POST['images'] )) ? $_POST['images'] : array();
                            foreach ( $images as $image )
                            {
                                if ( !empty( $image ) )
                                {
                                    $d->iQuery( 'eproduct_images', array(
                                        'imageUrl'  => $image,
                                        'productID' => $editID
                                    ) );
                                }
                            }
                            $out['error']   = '';
                            $out['success'] = true;
                        }
                        echo json_encode( $out );
                        exit;
                    }
                    $category = $d->Query( "SELECT `title` FROM `eproduct_categories` WHERE `categoryID`='$product[category]' LIMIT 1" );
                    if ( $d->getRows( $category ) === 1 )
                    {
                        $category = $d->fetch( $category );
                        $category = $category['title'];
                    }
                    else
                    {
                        $category = 'فاقد دسته بندی';
                    }
                    $tpl->assign( array(
                        'title'         => $product['title'],
                        'description'   => $product['description'],
                        'price'         => $product['price'],
                        'link'          => $product['link'],
                        'editID'        => $editID,
                        'categoryTitle' => $category,
                    ) );
                    /*
                      $categories = $d->Query( 'SELECT * FROM `eproduct_categories` ' );
                      while ( $data       = $d->fetch( $categories ) )
                      {
                      $itpl->block( 'categories', array(
                      'title'  => $data['title'],
                      'id'     => $data['categoryID'],
                      'select' => ($data['categoryID'] == $product['category']) ? 'selected' : '',
                      ) );
                      }
                     */
                    //list and sort images
                    $images = $d->Query( "SELECT * FROM `eproduct_images` WHERE `productID`=$product[productID]" );
                    $row    = 0;
                    while ( $img    = $d->fetch( $images ) )
                    {
                        $itpl->block( 'eproduct_images', array(
                            'imageUrl' => $img['imageUrl'],
                            'row'      => ++$row,
                        ) );
                    }
                    //list extra fields
                    //$categoryFields = nl2br( trim( $categoryFields ) );
                    //$categoryFields = explode( '<br />', $categoryFields );
                    $fieldsDB = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$product[category]'" );
                    while ( $field    = $d->fetch( $fieldsDB ) )
                    {
                        $data  = $d->Query( "SELECT `value` FROM `eproduct_fields` WHERE `productID`='$product[productID]' AND `fieldID`='$field[fieldID]]' LIMIT 1" );
                        $data  = $d->fetch( $data );
                        $value = $data['value'];
                        $name  = $field['title'];
                        if ( !empty( $name ) )
                        {
                            if ( substr( $name, 0, 7 ) == 'متن ' )
                            {
                                $itpl->block( 'fieldstext', array(
                                    'title' => substr( $name, 7 ),
                                    'name'  => $field['name'],
                                    'value' => $value
                                ) );
                            }
                            else
                            {
                                $itpl->block( 'fields', array(
                                    'title' => $name,
                                    'name'  => $field['name'],
                                    'value' => $value
                                ) );
                            }
                        }
                    }
                    $itpl->load( '../plugins/eshop/editProduct.html' );
                }
                else
                {
                    $itpl->load( '../plugins/eshop/product.html' );
                    if ( !isset( $_POST['category'] ) OR !is_numeric( $_POST['category'] ) )
                    {
                        $itpl->assign( 'addProduct', true );
                        $categories = $d->Query( 'SELECT * FROM `eproduct_categories` WHERE `parentID`=0' );
                        while ( $data       = $d->fetch( $categories ) )
                        {
                            $itpl->block( 'categories', array(
                                'title' => $data['title'],
                                'id'    => $data['categoryID'],
                            ) );
                            $subCategories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `parentID`='$data[categoryID]'" );
                            while ( $idata         = $d->fetch( $subCategories ) )
                            {
                                $itpl->block( 'categories', array(
                                    'title' => '----------' . $idata['title'],
                                    'id'    => $idata['categoryID'],
                                ) );
                            }
                        }
                    }
                    else
                    {
                        $itpl->assign( 'newProduct', true );
                        $category = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `categoryID`='$_POST[category]' LIMIT 1" );
                        if ( $d->getRows( $category ) != 1 )
                        {
                            exit( 'invalid' );
                        }
                        $category = $d->fetch( $category );
                        if ( isset( $_POST['productName'] ) )
                        {
                            header( 'Content-Type: application/json' );
                            $out            = array( 'success' => false );
                            $out['error']   = 'working';
                            $_POST['price'] = trim( $_POST['price'] );
                            if ( empty( $_POST['productName'] ) OR empty( $_POST['description_text'] ) OR !is_numeric( $_POST['price'] ) OR $_POST['price'] < 0 )
                            {
                                $out['error'] = 'لطفا همه فیلدهای ستاره دار را تکمیل کنید.';
                            }
                            else
                            {
                                $product         = $d->iQuery( 'eproducts', array(
                                    'title'       => $_POST['productName'],
                                    'description' => $_POST['description_text'],
                                    'price'       => $_POST['price'],
                                    'category'    => $_POST['category'],
                                    'link'        => $_POST['link'],
                                        // 'productValues' => json_encode( $_POST['fields'] )
                                        ) );
                                $productID       = $d->last();
                                //save product values
                                $_POST['fields'] = (isset( $_POST['fields'] ) && is_array( $_POST['fields'] )) ? $_POST['fields'] : array();
                                foreach ( $_POST['fields'] as $field => $value )
                                {
                                    if ( !empty( $value ) )
                                    {
                                        $categoryField = $d->Query( "SELECT `fieldID` FROM `ecategory_fields` WHERE `categoryID`='$_POST[category]' AND `name`='$field' LIMIT 1" );
                                        if ( $d->getRows( $categoryField ) > 0 )
                                        {
                                            $fieldID = $d->fetch( $categoryField );
                                            $d->iQuery( 'eproduct_fields', array(
                                                'productID' => $productID,
                                                'fieldID'   => $fieldID['fieldID'],
                                                'value'     => trim( $value )
                                            ) );
                                        }
                                    }
                                }

                                //save images !
                                $images = (isset( $_POST['images'] ) && is_array( $_POST['images'] )) ? $_POST['images'] : array();
                                foreach ( $images as $image )
                                {
                                    if ( !empty( $image ) )
                                    {
                                        $d->iQuery( 'eproduct_images', array(
                                            'imageUrl'  => $image,
                                            'productID' => $productID
                                        ) );
                                    }
                                }
                                $out['error']   = '';
                                $out['success'] = true;
                            }
                            echo json_encode( $out );
                            exit;
                        }
                        $itpl->assign( 'categoryTitle', $category['title'] );
                        $itpl->assign( 'ncategoryID', $category['categoryID'] );
                        //$fields = explode( '<br />', nl2br( trim( $category['fields'] ) ) );
                        $fieldsDB = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$category[categoryID]'" );
                        while ( $field    = $d->fetch( $fieldsDB ) )
                        {
                            $name = $field['title'];
                            if ( !empty( $name ) )
                            {
                                if ( substr( $name, 0, 7 ) == 'متن ' )
                                {
                                    $itpl->block( 'fieldstext', array(
                                        'title' => substr( $name, 7 ),
                                        'name'  => $field['name']
                                    ) );
                                }
                                else
                                {
                                    $itpl->block( 'fields', array(
                                        'title' => $name,
                                        'name'  => $field['name']
                                    ) );
                                }
                            }
                        }
                    }
                }
                break;
            case 'category':
                if ( isset( $_GET['delete'] ) && is_numeric( $_GET['delete'] ) )
                {
                    $_GET['delete'] = ( int ) $_GET['delete'];
                    $d->Query( "DELETE FROM `eproduct_categories` WHERE `categoryID`='$_GET[delete]' LIMIT 1" );
                    $d->Query( "DELETE FROM `ecategory_fields` WHERE `categoryID`='$_GET[delete]' LIMIT 1" );
                    $d->Query( "UPDATE `eproduct_categories` SET `parentID`='0' WHERE `parentID`='$_GET[delete]'" );
                }
                if ( isset( $_POST['list'] ) )
                {
                    $itpl->load( '../plugins/eshop/categoryList.html' );
                    $categories = $d->Query( 'SELECT * FROM `eproduct_categories` WHERE `parentID`=0' );
                    $row        = 0;
                    while ( $data       = $d->fetch( $categories ) )
                    {
                        $itpl->block( 'categories', array(
                            'name'       => $data['title'],
                            'categoryID' => $data['categoryID'],
                            'row'        => ++$row,
                        ) );
                        $subCategories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `parentID`='$data[categoryID]'" );
                        while ( $subData       = $d->fetch( $subCategories ) )
                        {
                            $itpl->block( 'categories', array(
                                'name'       => '-------   ' . $subData['title'],
                                'categoryID' => $subData['categoryID'],
                                'row'        => ++$row,
                            ) );
                        }
                    }
                    $itpl->showit();
                    exit;
                }
                $categories = $d->Query( 'SELECT * FROM `eproduct_categories` WHERE `parentID`=0' );
                while ( $data       = $d->fetch( $categories ) )
                {
                    $itpl->block( 'categories', array(
                        'title' => $data['title'],
                        'id'    => $data['categoryID'],
                    ) );
                }
                $itpl->load( '../plugins/eshop/category.html' );
                if ( isset( $_GET['edit'] ) && is_numeric( $_GET['edit'] ) )
                {
                    $itpl->load( '../plugins/eshop/editCategory.html' );
                    $categories = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `categoryID`='$_GET[edit]' LIMIT 1" );
                    if ( $d->getRows( $categories ) != 1 )
                    {
                        header( 'Location: plugins.php?plugin=eshop&ac=category' );
                        exit( 'invalid id' );
                    }
                    $categories        = $d->fetch( $categories );
                    $currentFieldsDB   = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$categories[categoryID]'" );
                    $currentFields     = array();
                    while ( $currentFieldsData = $d->fetch( $currentFieldsDB ) )
                    {
                        $itpl->block( 'fields', array(
                            'name'  => $currentFieldsData['name'],
                            'title' => $currentFieldsData['title'],
                        ) );
                    }
                    if ( !empty( $_POST['name'] ) )
                    {
                        $parentID = ( int ) @$_POST['parent'];
                        if ( $parentID != 0 )
                        {
                            $category = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `categoryID`='$parentID' LIMIT 1" );
                            if ( $d->getRows( $category ) != 1 )
                            {
                                exit( 'error' );
                            }
                        }
                        //remove un-checked fields !
                        $currentFieldsDB   = $d->Query( "SELECT * FROM `ecategory_fields` WHERE `categoryID`='$categories[categoryID]'" );
                        while ( $currentFieldsData = $d->fetch( $currentFieldsDB ) )
                        {
                            if ( !in_array( $currentFieldsData['name'], $_POST['fields'] ) )
                            {
                                $d->Query( "DELETE FROM `ecategory_fields` WHERE `fieldID`='$currentFieldsData[fieldID]' LIMIT 1" );
                                $d->Query( "DELETE FROM `eproduct_fields` WHERE `fieldID`='$currentFieldsData[fieldID]' LIMIT 1" );
                            }
                        }
                        $d->uQuery( 'eproduct_categories', array(
                            'title'        => $_POST['name'],
                            'description'  => $_POST['description_text'],
                            'image'        => $_POST['image'],
                            'parentID'     => $parentID,
                            'afterPayment' => $_POST['afterPayment_text'],
                                //'fields'       => trim( $_POST['fields'] ),
                                ), " `categoryID`='$categories[categoryID]'" );
                        //insert category fields
                        $fields = explode( '<br />', nl2br( trim( $_POST['newfields'] ) ) );

                        foreach ( $fields as $field )
                        {
                            if ( !empty( $field ) )
                            {
                                $field = trim( $field );
                                $d->iQuery( 'ecategory_fields', array(
                                    'categoryID' => $categories['categoryID'],
                                    'name'       => md5( $field ),
                                    'title'      => $field
                                ) );
                            }
                        }
                        exit( 'ok' );
                    }
                    $itpl->assign( array(
                        'title'        => $categories['title'],
                        'description'  => $categories['description'],
                        'image'        => $categories['image'],
                        'afterPayment' => $categories['afterPayment'],
                        // 'fields'       => $categories['fields'],
                        'categoryID'   => $categories['categoryID'],
                    ) );
                }
                else
                if ( !empty( $_POST['name'] ) )
                {
                    header( 'Content-Type: application/json' );
                    $out          = array( 'success' => false );
                    $out['error'] = 'working';
                    //insert
                    $parentID     = ( int ) @$_POST['parent'];
                    if ( $parentID != 0 )
                    {
                        $category = $d->Query( "SELECT * FROM `eproduct_categories` WHERE `categoryID`='$parentID' LIMIT 1" );
                        if ( $d->getRows( $category ) != 1 )
                        {
                            $out['error'] = 'اطلاعات فرم ناقص است';
                            echo json_encode( $out );
                            exit;
                        }
                    }
                    $d->iQuery( 'eproduct_categories', array(
                        'title'        => $_POST['name'],
                        'description'  => $_POST['description_text'],
                        'image'        => $_POST['image'],
                        'parentID'     => $parentID,
                        'afterPayment' => $_POST['afterPayment_text'],
                            //'fields'       => trim( $_POST['fields'] ),
                    ) );
                    $insert = $d->last();
                    //insert category fields
                    $fields = explode( '<br />', nl2br( trim( $_POST['fields'] ) ) );
                    foreach ( $fields as $field )
                    {
                        $field = trim( $field );
                        if ( !empty( $field ) )
                        {
                            $d->iQuery( 'ecategory_fields', array(
                                'categoryID' => $insert,
                                'name'       => md5( $field ),
                                'title'      => $field
                            ) );
                        }
                    }
                    $out['error']   = '';
                    $out['success'] = true;
                    echo json_encode( $out );
                    exit;
                }

                break;
            case 'orders':
                global $BasketStatuses;
                if ( isset( $_GET['delete'] ) && is_numeric( $_GET['delete'] ) )
                {
                    $basketID = intval( $_GET['delete'] );
                    $d->Query( "DELETE FROM `ebasket` WHERE `basketID`='$basketID' LIMIT 1" );
                }
                if ( isset( $_GET['edit'] ) && is_numeric( $_GET['edit'] ) )
                {
                    $editID = ( int ) $_GET['edit'];
                    $order  = $d->Query( "SELECT * FROM `ebasket` WHERE `basketID`='$editID' LIMIT 1" );
                    if ( $d->getRows( $order ) !== 1 )
                    {
                        header( "Location: plugins.php?plugin=eshop&ac=orders" );
                        exit;
                    }
                    $d->Query( "UPDATE `ebasket` SET `view`=1 WHERE `basketID`='$editID' LIMIT 1" );
                    if ( !empty( $_POST['status'] ) && isset( $BasketStatuses[$_POST['status']] ) )
                    {
                        //update
                        $d->uQuery( 'ebasket', array(
                            'status'      => $_POST['status'],
                            'adminStatus' => @$_POST['adminStatus'],
                                ), " `basketID`='$editID' LIMIT 1" );
                        $order = $d->Query( "SELECT * FROM `ebasket` WHERE `basketID`='$editID' LIMIT 1" );
                    }
                    $order         = $d->fetch( $order );
                    $order['time'] = mytime( 'Y/m/d H:i:s', $order['time'], 3.5 );
                    $itpl->assign( $order );
                    foreach ( $BasketStatuses as $key => $value )
                    {
                        $itpl->block( 'orderStatus', array(
                            'key'    => $key,
                            'value'  => $value,
                            'select' => ($key == $order['status'] ) ? 'selected' : '',
                        ) );
                    }
                    //basket Items
                    $items = $d->Query( "SELECT * FROM `ebasket_items` WHERE `basketID`='$order[basketID]'" );
                    $r     = 0;
                    while ( $data  = $d->fetch( $items ) )
                    {
                        $product = $d->Query( "SELECT `price`,`title` FROM `eproducts` WHERE `productID`='$data[productID]' LIMIT 1" );
                        $product = $d->fetch( $product );
                        $itpl->block( 'eproducts', array(
                            'row'        => ++$r,
                            'productID'  => $data['productID'],
                            'product'    => $product['title'],
                            'price'      => number_format( $product['price'] ),
                            'totalPrice' => number_format( $data['price'] ),
                            'count'      => $data['count']
                        ) );
                    }
                    //transactions
                    $items = $d->Query( "SELECT * FROM `ebasket_transactions` WHERE `basketID`='$order[basketID]'" );
                    $r     = 0;
                    while ( $data  = $d->fetch( $items ) )
                    {
                        $itpl->block( 'transactions', array(
                            'row'      => ++$r,
                            'price'    => number_format( $data['price'] ),
                            'trackKey' => $data['trackKey'],
                            'time'     => mytime( 'Y/m/d H:i:s', $data['time'], 3.5 ),
                            'gateway'  => $data['gateway'],
                            'status'   => ($data['status'] == 1) ? '<b style="color:green">موفق</b>' : '<font color=red>ناموفق</font>',
                        ) );
                    }
                    $itpl->load( '../plugins/eshop/viewOrder.html' );
                }
                else
                {
                    $itpl->load( '../plugins/eshop/orders.html' );
                    $orders = $d->Query( "SELECT * FROM `ebasket` ORDER BY `basketID` DESC" );
                    $row    = 0;

                    while ( $data = $d->fetch( $orders ) )
                    {
                        if ( empty( $data['price'] ) )
                        {
                            $data['price'] = 0;
                        }
                        $itpl->block( 'orders', array(
                            'row'      => ++$row,
                            'date'     => mytime( 'Y/m/d H:i:s', $data['time'], 3.5 ),
                            'price'    => number_format( $data['price'] ),
                            'status'   => $BasketStatuses[$data['status']],
                            'orderID'  => $data['basketID'],
                            'customer' => $data['fullname']
                        ) );
                    }
                }
                break;
            default:
                $itpl->load( '../plugins/eshop/information.html' );
                //first page information
                $eproducts           = $d->Query( "SELECT COUNT(*) as `total` FROM `eproducts`" );
                $eproducts           = $d->fetch( $eproducts );
                $eproducts           = $eproducts['total'];
                $eproduct_categories = $d->Query( "SELECT COUNT(*) as `total` FROM `eproduct_categories`" );
                $eproduct_categories = $d->fetch( $eproduct_categories );
                $eproduct_categories = $eproduct_categories['total'];
                $basket              = $d->Query( "SELECT COUNT(*) as `total` FROM `ebasket`" );
                $basket              = $d->fetch( $basket );
                $basket              = $basket['total'];
                $basket_new          = $d->Query( "SELECT COUNT(*) as `total` FROM `ebasket` WHERE `view`='0'" );
                $basket_new          = $d->fetch( $basket_new );
                $basket_new          = $basket_new['total'];
                $totalSalary         = $d->Query( "SELECT SUM(`price`) as `total` FROM `ebasket_transactions` WHERE `status`='1'" );
                $totalSalary         = $d->fetch( $totalSalary );
                $totalSalary         = $totalSalary['total'];
                $firstOfMonth        = jmaketime( 0, 0, 0, jdate( 'm' ), 1, jdate( 'Y' ) );
                $monthSalary         = $d->Query( "SELECT SUM(`price`) as `total` FROM `ebasket_transactions` WHERE `status`='1' AND `time`>'$firstOfMonth'" );
                $monthSalary         = $d->fetch( $monthSalary );
                $monthSalary         = $monthSalary['total'];
                $itpl->assign( array(
                    'eproducts'           => number_format( $eproducts ),
                    'eproduct_categories' => number_format( $eproduct_categories ),
                    'basket'              => number_format( $basket ),
                    'basket_new'          => number_format( $basket_new ),
                    'totalSalary'         => number_format( $totalSalary ),
                    'monthSalary'         => number_format( $monthSalary ),
                ) );
                break;
        }
        //tabs
        $tpl->block( 'tabs', array(
            'title' => 'محصولات',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=product',
        ) );
        $tpl->block( 'tabs', array(
            'title' => 'دسته ها',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=category',
        ) );
        $tpl->block( 'tabs', array(
            'title' => 'سفارشات',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=orders',
        ) );
        $tpl->block( 'tabs', array(
            'title' => 'جستجو پیشرفته',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=search',
        ) );
        $tpl->block( 'tabs', array(
            'title' => 'گزارشات فروش',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=reports',
        ) );
		$tpl->block( 'tabs', array(
            'title' => 'تنظیمات',
            'id'    => 1,
            'url'   => 'plugins.php?plugin=eshop&ac=settings',
        ) );
        $tpl->assign( 'plugins_name', $information['name'] );
        $tpl->assign( 'first', $itpl->dontshowit() );
    }

    function inactivateop()
    {
        global $d;
        $d->Query( "UPDATE `plugins` SET `stat`='0' WHERE `name`='eshop' LIMIT 1" );
        print_msg( 'ماژول با موفقيت غير فعال شد.', 'Success' );
    }

    function activateop()
    {
        global $d;
        $d->Query( "UPDATE `plugins` SET `stat`='1' WHERE `name`='eshop' LIMIT 1" );
        print_msg( 'ماژول با موفقيت فعال شد.', 'Success' );
    }

    function installop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='eshop' LIMIT 1", true );
        if ( $q > 0 )
        {
            print_msg( 'اين ماژول قبلا نصب شده است.', 'Info' );
        }
        else
        {
            global $information;
            $oid    = $d->getmax( 'oid', 'menus' );
            $d->Query( "INSERT INTO `menus` SET `oid`='$oid',`name`='eshop',`title`='فروشگاه الکترونیک',`url`='plugins.php?plugin=eshop',`type`='0'" );
            $parent = $d->last();
            $oid++;
            $d->Query( "INSERT INTO `menus` SET `oid`='$oid',`name`='eshop',`title`='محصولات',`url`='plugins.php?plugin=eshop&ac=product',`type`='0',`parent`='$parent'" );
            $oid++;
            $d->Query( "INSERT INTO `menus` SET `oid`='$oid',`name`='eshop',`title`='دسته ها',`url`='plugins.php?plugin=eshop&ac=category',`type`='0',`parent`='$parent'" );
            $oid++;
            $d->Query( "INSERT INTO `menus` SET `oid`='$oid',`name`='eshop',`title`='سفارشات',`url`='plugins.php?plugin=eshop&ac=orders',`type`='0',`parent`='$parent'" );
            $oid++;
            $d->Query( "INSERT INTO `plugins` SET `name`='eshop',`title`='$information[name]',`stat`='0'" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `ebasket` (
  `basketID` int(10) NOT NULL AUTO_INCREMENT,
  `trackKey` varchar(50) NOT NULL,
  `userID` int(10) DEFAULT NULL,
  `ip` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `time` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `paymentStatus` tinyint(1) NOT NULL DEFAULT '0',
  `view` tinyint(1) NOT NULL DEFAULT '0',
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `tell` varchar(15) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `adminStatus` text NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`basketID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            print_msg( 'ماژول با موفقيت نصب شد.', 'Success' );

            $d->Query( "
CREATE TABLE IF NOT EXISTS `ebasket_items` (
  `itemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `basketID` int(10) NOT NULL,
  `productID` int(10) NOT NULL,
  `count` int(10) NOT NULL,
  `price` varchar(50) NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `ebasket_transactions` (
  `transactionID` int(10) NOT NULL AUTO_INCREMENT,
  `transactionCode` varchar(100) NOT NULL,
  `trackKey` varchar(100) NOT NULL,
  `basketID` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `logProxy` tinytext NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `gateway` varchar(100) NOT NULL,
  `sendData` text NOT NULL,
  `reciveData` text NOT NULL,
  PRIMARY KEY (`transactionID`),
  UNIQUE KEY `trackKey` (`trackKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `eproducts` (
  `productID` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(100) NOT NULL,
  `category` int(3) NOT NULL,
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `eproduct_categories` (
  `categoryID` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `productCount` int(5) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL,
  `parentID` int(3) NOT NULL DEFAULT '0',
  `afterPayment` text NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "
CREATE TABLE IF NOT EXISTS `eproduct_fields` (
  `productFeildID` bigint(20) NOT NULL AUTO_INCREMENT,
  `productID` int(10) NOT NULL,
  `fieldID` int(10) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`productFeildID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `eproduct_images` (
  `imageID` int(10) NOT NULL AUTO_INCREMENT,
  `imageUrl` varchar(255) NOT NULL,
  `productID` int(10) NOT NULL,
  PRIMARY KEY (`imageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `eshopsearch` (
  `searchID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`searchID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            $d->Query( "CREATE TABLE IF NOT EXISTS `ecategory_fields` (
  `fieldID` int(10) NOT NULL AUTO_INCREMENT,
  `categoryID` int(5) NOT NULL,
  `name` varchar(35) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`fieldID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;" );
            activateop();
        }
    }

    function uninstallop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='eshop' LIMIT 1", true );
        if ( $q <= 0 )
            print_msg( 'اين ماژول نصب نشده است يا استاندارد نيست.', 'Info' );
        else
        {
            $d->Query( "DELETE FROM `menus` WHERE `name`='eshop'" );
            $d->Query( "DELETE FROM `plugins` WHERE `name`='eshop'" );
            $d->Query( "DROP TABLE IF EXISTS `ebasket_items`" );
            $d->Query( "DROP TABLE IF EXISTS `ebasket_transactions`" );
            $d->Query( "DROP TABLE IF EXISTS `eproducts`" );
            $d->Query( "DROP TABLE IF EXISTS `eproduct_categories`" );
            $d->Query( "DROP TABLE IF EXISTS `eproduct_fields`" );
            $d->Query( "DROP TABLE IF EXISTS `eproduct_images`" );
            $d->Query( "DROP TABLE IF EXISTS `eshopsearch`" );
            $d->Query( "DROP TABLE IF EXISTS `ecategory_fields`" );

            print_msg( 'ماژول با موفقيت حذف شد.', 'Success' );
        }
    }

    function print_msg( $msg, $type )
    {
        global $tpl, $information;
        $tpl->assign( array(
            'plugins_name' => $information['name'],
            $type          => 1,
            'msg'          => $msg,
        ) );
    }

}