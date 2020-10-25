<?php
/**
 * Smarty plugin
 * @package HNb
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');
function smarty_modifier_HNbDate($string, $formatType="m", $default_date=null)
{
	$formatType = strtolower($formatType);
	switch($formatType){
		case 's':
			$format = '%y.%m.%d';
			$format = 'y.m.d';
			break;
		case 'm':
			$format = '%y.%m.%d %H:%M';
			$format = 'y.m.d H:i';
			break;
		case 'rss':
			$format = 'r';
			break;
	}
		
    if($string != '') {
        return date($format, smarty_make_timestamp($string));
    } elseif (isset($default_date) && $default_date != '') {
        return date($format, smarty_make_timestamp($default_date));
    } else {
        return;
    }
}

/* vim: set expandtab: */

?>
