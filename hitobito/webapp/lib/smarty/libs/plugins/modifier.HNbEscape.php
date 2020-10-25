<?php
/**
 * 自動リンクとサニタイズを行うプラグイン
 * サニタイズには、HNbSanitizer使用
 * @author Ryuji
 * @version $Id: modifier.HNbEscape.php,v 1.2 2006/02/01 16:02:51 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbSanitizer.class.php';
function smarty_modifier_HNbEscape($string)
{
	$sanitizer = new HNbSanitizer();
	$string = $sanitizer->sanitizeString($string);
    $string = nl2br($string);
    $patterns = array();
    $replaces = array();
    // URL
    $patterns[] = "/([^=^\"]|^)((http|ftp|https)\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\,\|]+)/";
    $replaces[] = "\\1<a href=\"\\2\" target=_blank>\\2</a>";
    // mail
    $patterns[] = "/([^=^\"]|^)([\w\.\~\-\/\?\&\+\=\:\@\%\;\#\,\|]+@[\w\.\~\-\/\?\&\+\=\:\@\%\;\#\,\|]+)/";
    $replaces[] = "\\1<a href=\"mailto:\\2\" target=_blank>\\2</a>";
    $ret = preg_replace( $patterns, $replaces, $string );
    
    return $ret;
}


?>