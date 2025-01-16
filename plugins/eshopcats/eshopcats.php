<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function eshopcats_output()
{
    global $config, $tpl, $d, $cats;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\eshopcats\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshopcats\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\eshopcats_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshopcats_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/eshopcats/block-theme.html' );
	}
		$itpl->assign( 'theme_url', 'theme/core/' . $config['theme'] . '/' );
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
            $url           = curPageURL();
            $url           = preg_replace( '#eshopcategory=[0-9]+#iUs', '', $url );
            $url           = preg_replace( '#\??productID=[0-9]+#iUs', '', $url );
            $ddd['url']    = appendQs( $url, 'eshopcategory', $data['categoryID'] );
            $subCategories = $d->Query( "SELECT * FROM `product_categories` WHERE `parentID`='$data[categoryID]'" );
            if ( $d->getRows( $subCategories ) > 0 )
            {
                $ddd['hasSub'] = true;
            }
            $itpl->block( 'categories', $ddd );
            while ( $idata = $d->fetch( $subCategories ) )
            {
                $itpl->block( 'subCategories_' . $data['categoryID'], array(
                    'subcategoryID'   => $idata['categoryID'],
                    'subtitle'        => $idata['title'],
                    'subdescription'  => $idata['description'],
                    'subproductCount' => $idata['productCount'],
                    'subimage'        => $idata['image'],
                    'suburl'          => appendQs( $url, 'eshopcategory', $idata['categoryID'] )
                ) );
            }
        }
		$itpl->assign( 'icon', @$args['icon'] );
        return $itpl->dontshowit();
}