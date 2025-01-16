<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function shopbestproducts_output()
{
    global $config, $tpl, $d, $cats;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\shopbestproducts\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\shopbestproducts\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\shopbestproducts_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\shopbestproducts_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/shopbestproducts/block-theme.html' );
	}
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
            $itpl->block( 'bestProducts', $bData );
        }
		$itpl->assign( 'icon', @$args['icon'] );
        return $itpl->dontshowit();
}