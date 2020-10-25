<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty wordwrap modifier plugin
 *
 * Type:     modifier<br>
 * Name:     wordwrap<br>
 * Purpose:  wrap a string of text at a given length
 * @param string
 * @param integer
 * @param string
 * @param integer 0以外の時は最大行数
 * @return string
 */
function smarty_modifier_mbwordwrap($string,$length=80,$break="\n", $maxline=0)
{
	// 行に分割
	$lines = explode($break, $string);
	$ret = array();
	foreach($lines as $line){
		$stringLength = mb_strwidth($line);
		if($stringLength > $length){
			// 切りつめ処理
			$start = 0;
			while($stringLength >$start){
				$ret[] = $cutString = mb_strcut($line, $start, $length);
				$start += mb_strwidth($cutString);
			}
		}else{
			$ret[] = $line;
		}
	}
	if(($maxline > 0) && (count($ret) > $maxline)){
		$cutRet = array(); 
		while(count($cutRet) < $maxline){
			$cutRet[] = array_shift($ret);
		}
		return implode($break, $cutRet);	
	}
	return implode($break, $ret);
}

?>
