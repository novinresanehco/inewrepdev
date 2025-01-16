<?php

if ( !defined( 'plugins_admin_area' ) OR !isset( $permissions['access_admin_area'] ) OR $permissions['access_admin_area'] != '1' )
    die( 'invalid access' );
if( !function_exists( 'get_eshopstatus_templates' ) )
{
	function get_eshopstatus_templates()
	{
		$themesGLob = glob( current_theme_dir . 'plugins\\eshopstatus\\' . '*.htm*' );
		$themes     = array();
		foreach ( $themesGLob as $value )
		{
			$value    = str_replace( current_theme_dir. 'plugins\\eshopstatus\\' , '', $value );
			$value 	  = explode( '\\', $value );
			$value    = $value[(count( $value ) - 1)];
			$value    = str_replace( '.html', '', $value );
			$value    = str_replace( '.htm', '', $value );
			$value    = trim( $value, '/' );
			$value    = trim( $value, '\\' );
			$themes[$value] = $value;
		}
		return $themes;
	}
}	
$information = array(
    'name'        => 'وضعیت سفارش فروشگاه مجازی',
    'provider'    => 'رضا شاهرخيان',
    'providerurl' => 'http://rashcms.com',
    'install'     => true,
    'uninstall'   => true,
    'activate'    => true,
    'inactivate'  => true,
    'data'        => array( //لیست پارامترهای اختصاصی ماژول
        'title'      => array( 'name' => 'عنوان', 'value' => 'وضعیت سفارش فروشگاه مجازی' ),
		'template' => array( 'name' => 'قالب', 'value' => 'default', 'type' => 'select', 'options' => get_eshopstatus_templates()  ),
        'icon'       => array( 'name' => 'آیکن', 'value' => '', 'type' => 'icon' ),
    )
);
$tpl->assign( 'first', '' );
if ( defined( 'methods' ) )
{

    function defaultop()
    {
        print_msg( 'اين ماژول شامل تنظيمات خاصي نمي باشد.', 'Info' );
    }

    function inactivateop()
    {
        global $d;
        $d->Query( "UPDATE `plugins` SET `stat`='0' WHERE `name`='eshopstatus' LIMIT 1" );
        print_msg( 'ماژول با موفقيت غير فعال شد.', 'Success' );
    }

    function activateop()
    {
        global $d;
        $d->Query( "UPDATE `plugins` SET `stat`='1',`sortable`=1 WHERE `name`='eshopstatus' LIMIT 1" );
        print_msg( 'ماژول با موفقيت فعال شد.', 'Success' );
    }

    function installop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='eshopstatus' LIMIT 1", true );
        if ( $q > 0 )
            print_msg( 'اين ماژول قبلا نصب شده است.', 'Info' );
        else
        {
            global $information;
            $options          = array();
            $options['theme'] = true;
            $options          = base64_encode( serialize( $options ) );
            $q                = $d->Query( "INSERT INTO `plugins` SET `name`='eshopstatus',`title`='$information[name]',`stat`='0',`options`='$options'" );
            print_msg( 'ماژول با موفقيت نصب شد.', 'Success' );
            activateop();
        }
    }

    function uninstallop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='eshopstatus' LIMIT 1", true );
        if ( $q <= 0 )
            print_msg( 'اين ماژول نصب نشده است يا استاندارد نيست.', 'Info' );
        else
        {
            $q = $d->Query( "DELETE FROM `plugins` WHERE `name`='eshopstatus' LIMIT 1" );
            print_msg( 'ماژول با موفقيت حذف شد.', 'Success' );
        }
    }

    function print_msg( $msg, $type )
    {
        global $tpl, $information;
        $tpl->assign( array(
            'plugin_name' => $information['name'],
            $type         => 1,
            'msg'         => $msg,
        ) );
    }

}