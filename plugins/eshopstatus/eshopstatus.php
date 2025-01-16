<?php

if ( !defined( 'plugins-inc' ) OR !is_array( @$data ) )
{
    die( '<a href="http://help.mihanphp.com/plugins" target=_blank>samaneh</a> :: Invalid calling of ' . basename( __FILE__ ) );
}

function eshopstatus_output()
{
    global $config, $tpl, $d, $cats;
    $args = func_get_args();
    if ( !empty( $args[0] ) )
    {
        $args = $args[0];
    }
    $itpl  = new samaneh();
	if( !empty( $args['template'] ) && ctype_alnum( $args['template'] ) && file_exists( current_theme_dir. 'plugins\\eshopstatus\\' . $args['template'] . '.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshopstatus\\' . $args['template'] . '.html' );
		
	}	
	else if( file_exists( current_theme_dir . 'plugins\\eshopstatus_block-theme.html' ) )
	{
		$itpl->load( current_theme_dir. 'plugins\\eshopstatus_block-theme.html' );
	}
	else
	{
		$itpl->load( 'plugins/eshopstatus/block-theme.html' );
	}
		$itpl->assign( 'theme_url', core_theme_url );
        if ( isset( $_POST['etrackKey'] ) )
        {
            $trackKey = safe( $_POST['etrackKey'] );
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
		$itpl->assign( 'icon', @$args['icon'] );
        return $itpl->dontshowit();
}