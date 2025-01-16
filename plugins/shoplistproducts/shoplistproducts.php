<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function shoplistproducts_output()
{
    global $config, $tpl, $d, $cats;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\shoplistproducts\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\shoplistproducts\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\shoplistproducts_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\shoplistproducts_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/shoplistproducts/block-theme.html' );
	}
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
		$i = 0;
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
                'row'         => ++$i,
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'category'    => $categories[$data['category']],
                'stock'       => $data['stock'],
                'productID'   => $data['productID'],
                'url'         => appendQS( $config['site'], 'productID', $data['productID'] )
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
        $url = curPageURL();
        for ( $i = 1; $i <= $pages; $i++ )
        {
            $itpl->block( 'pages', array( 'page' => $i, 'active' => ($i === $page) ? ' active' : ' ', 'url' => appendQS( $url, 'page', $i ) ) );
        }
		$itpl->assign( 'icon', @$args['icon'] );
        return $itpl->dontshowit();
}