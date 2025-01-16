<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function eshoprecentproducts_output()
{
    global $config, $tpl, $d, $cats;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\eshoprecentproducts\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshoprecentproducts\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\eshoprecentproducts_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshoprecentproducts_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/eshoprecentproducts/block-theme.html' );
	}
		$itpl->assign( 'theme_url', 'theme/core/' . $config['theme'] . '/' );
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
                'url'         => appendQS( $config['site'], 'eproductID', $data['productID'] )
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
        return $itpl->dontshowit();
}