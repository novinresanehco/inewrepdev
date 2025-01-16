<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function recentshopproductsbycatParser( $tpl )
{
    if ( preg_match_all( '#\[recentshopproductsbycat\_([0-9]+)(\_([a-z0-9\-]+))?\]#Ui', $tpl, $matches ) )
    {
        for ( $i = 0, $c = count( $matches[0] ); $i < $c; $i++ )
        {
            $category           = $matches[1][$i];
            $template           = $matches[3][$i];
            $data               = array();
            $data['categories'] = $category;
            $data['template'] 	= $template;
            $tpl                = str_replace( $matches[0][$i], recentshopproductsbycat_output( $data ), $tpl );
        }
    }
    return $tpl;
}

function recentshopproductsbycat_output()
{
    global $config, $tpl, $d, $cats, $cache;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $md5 = md5( implode( 'mihanphp.com', $args ) );
	$itpl = $cache->get( $md5 );
	if( !is_null( $itpl ) )
	{
		//return $itpl;
	}
	/*
    if ( empty( $args['categories'] ) OR !is_numeric( $args['categories'] ) OR $args['categories'] < 0 )
    {
        return '';
    }
	*/
	
    $catId = @$args['categories'];
    $count = ( isset( $args['count'] ) && is_numeric( $args['count'] ) && $args['count'] > 0 ) ? $args['count'] : 10;
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\recentshopproductsbycat\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\recentshopproductsbycat\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\recentshopproductsbycat_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\recentshopproductsbycat_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/recentshopproductsbycat/block-theme.html' );
	}
    $itpl->assign( 'theme_url', 'theme/core/' . $config['theme'] . '/' );
    $ctimestamp = time();
	if ( !empty( $args['categories'] ) )
	{
		$iq  = $d->Query( "SELECT * FROM `products` WHERE `delete`=0 AND ( `category` = '$catId' OR `category` IN ( SELECT `categoryID` FROM `product_categories` WHERE  `parentID`='$catId' ) ) LIMIT $count ");
	}
	else
	{
		$iq  = $d->Query( "SELECT * FROM `products` WHERE `delete`=0 LIMIT $count ");
	}
    $first      = true;
    $itpl->assign( 'categoryTitle', @$cats[$catId] );
    while ( $data  = $d->fetch( $iq ) )
        {
            $bData = array(
                'title'       => $data['title'],
                'description' => stripcslashes( htmlspecialchars_decode( $data['description'] ) ),
                'price'       => $data['price'],
                'fprice'      => number_format( $data['price'] ),
                'stock'       => $data['stock'],
                'productID'   => $data['productID'],
                'url'         => appendQS( $config['site'], 'productID', $data['productID'] )
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
		$itpl->assign( 'icon', @$args['icon'] );
		$itpl->assign( 'title', @$args['title'] );
		$itpl->assign( 'block_id', rand( 2000 , 4000 ) );
    $out = $itpl->dontshowit();
    $cache->set( $md5, $out, 10800 ); //cache for 3 ours
    return $out;
}

$tpl->assign( 'recentshopproductsbycat', recentshopproductsbycat_output() );