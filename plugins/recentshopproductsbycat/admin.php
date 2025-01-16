<?php

if ( !defined( 'plugins_admin_area' ) OR !isset( $permissions['access_admin_area'] ) OR $permissions['access_admin_area'] != '1' )
    die( 'invalid access' );
if( !function_exists( 'get_recentshopproductsbycat_templates' ) )
{
	function get_recentshopproductsbycat_templates()
	{
		$themesGLob = glob( current_theme_dir . 'plugins\\recentshopproductsbycat\\' . '*.htm*' );
		$themes     = array();
		foreach ( $themesGLob as $value )
		{
			$value    = str_replace( current_theme_dir. 'plugins\\recentshopproductsbycat\\' , '', $value );
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
if( !function_exists( 'getShopSelectCats' ) )
{
	function getShopSelectCats( $parent = 0, $join = '', $id = '', $selected_id = false, $justArray = false )
	{
		global $d, $colors;
		
		$menu_data = $d->Query( "SELECT * FROM `product_categories` WHERE parentID = '$parent' ORDER BY `parentID`,`categoryID` ASC" );
		if ( $justArray )
		{
			$out = array();
			if( $parent == 0 )
			{
				$out[0] =  'همه';
			}
		}
		else
		{
			$out = '';
			if( $parent == 0 )
			{
				$out .= "<option style='font-weight:$font' value='0'>همه</option>";
			}
		}
		if ( $d->GetRows( $menu_data ) > 0 )
		{
			$p_sub = ( int ) $d->GetRowValue( "parentID", "SELECT `parentID` FROM `product_categories` WHERE `categoryID`='$parent' LIMIT 1", true );
			$join .= '---';
			while ( $menu  = $d->fetch( $menu_data ) )
			{
				$font = '';
				if ( $p_sub == $parent )
				{
					$join = substr( $join, 0, -3 );
				}
				if ( $menu['parentID'] == 0 )
				{
					$catMainId = 0;
					$font      = 'bold';
				}
				else
				{
					$catMainId = $menu['categoryID'];
					$font      = 'notmal';
				}
				$vid      = (!empty( $id )) ? "id='" . $id . $menu['categoryID'] . "'" : '';
				$selected = '';
				if ( $selected_id !== false && $selected_id == $menu['categoryID'] )
				{
					$selected = " selected ";
				}
				if ( $justArray )
				{
					$out[$menu['categoryID']] = $join . ' ' . $menu['title'];
					$tmp              = getShopSelectCats( $menu['categoryID'], $join, $id, $selected_id, $justArray );
					foreach ( $tmp as $key => $value )
					{
						$out[$key] = $value;
					}
				}
				else
				{
					$out .= "<option style='font-weight:$font' value='$menu[categoryID]' $selected $vid >" . $join . ' ' . $menu['title'] . "</option>";
					$out .= getShopSelectCats( $menu['categoryID'], $join, $id, $selected_id, $justArray );
				}
			}
		}
		return $out;
	}
}
$information = array(
    'name'        => 'آخرین محصولات گروه',
    'provider'    => 'رضا شاهرخيان',
    'providerurl' => 'http://shahrokhian.com',
    'install'     => true,
    'uninstall'   => true,
    'activate'    => true,
    'inactivate'  => true,
    'data'        => array( //لیست پارامترهای اختصاصی ماژول
        'title'      => array( 'name' => 'عنوان', 'value' => 'آخرین محصولات گروه' ),
        'count'      => array( 'name' => 'تعداد', 'value' => '10', 'type' => 'number' ),
        'categories' => array( 'name' => 'دسته', 'value' => '', 'type' => 'select', 'options' => getShopSelectCats( 0, '', '', false, true ) ),
		'template' => array( 'name' => 'قالب', 'value' => 'default', 'type' => 'select', 'options' => get_recentshopproductsbycat_templates()  ),
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
        $d->Query( "UPDATE `plugins` SET `stat`='0' WHERE `name`='recentshopproductsbycat' LIMIT 1" );
        print_msg( 'ماژول با موفقيت غير فعال شد.', 'Success' );
    }

    function activateop()
    {
        global $d;
        $d->Query( "UPDATE `plugins` SET `stat`='1',`sortable`=1 WHERE `name`='recentshopproductsbycat' LIMIT 1" );
        print_msg( 'ماژول با موفقيت فعال شد.', 'Success' );
    }

    function installop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='recentshopproductsbycat' LIMIT 1", true );
        if ( $q > 0 )
            print_msg( 'اين ماژول قبلا نصب شده است.', 'Info' );
        else
        {
            global $information;
            $options          = array();
            $options['theme'] = true;
            $options          = base64_encode( serialize( $options ) );
            $q                = $d->Query( "INSERT INTO `plugins` SET `name`='recentshopproductsbycat',`title`='$information[name]',`stat`='0',`options`='$options'" );
            print_msg( 'ماژول با موفقيت نصب شد.', 'Success' );
            activateop();
        }
    }

    function uninstallop()
    {
        global $d;
        $q = $d->getrows( "SELECT `stat` FROM `plugins` WHERE `name`='recentshopproductsbycat' LIMIT 1", true );
        if ( $q <= 0 )
            print_msg( 'اين ماژول نصب نشده است يا استاندارد نيست.', 'Info' );
        else
        {
            $q = $d->Query( "DELETE FROM `plugins` WHERE `name`='recentshopproductsbycat' LIMIT 1" );
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