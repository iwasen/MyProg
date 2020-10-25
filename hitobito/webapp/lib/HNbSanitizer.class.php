<?php
/**
 * サニタイズ
 * @author Ryuji
 * @version $Id: HNbSanitizer.class.php,v 1.1 2006/02/01 16:02:51 ryu Exp $
 */

class HNbSanitizer
{
	private 
		$NgTagList = array(
			'object',
			'script',
			'link',
			'style' 
			);
	private
		$NgAttributes = array(
			'style'
		);
	public function sanitizeString($string)
	{
		$sanitizedString = $this->sanitizeNgTag($string);
		$sanitizedString = $this->sanitizeTagAttribute($sanitizedString);
		$sanitizedString = $this->sanitizeUri($sanitizedString);
		return $sanitizedString;
	}

	/**
	 * 指定タグのサニタイズ
	 */
	private function sanitizeNgTag($string)
	{
		$search = array();
		$replace = array();
		foreach($this->NgTagList as $tag){
			//$search[] = '/<'.$tag.'[^<]*<\/'.$tag.'>/is';
			$search[] = '/<\/?'.$tag.'[^>]*>/isU';
			$replace[] = '';
		}
		$sanitizedString = preg_replace($search, $replace, $string);
		return $sanitizedString;
	}
	
	/**
	 * 指定タグ属性のサニタイズ
	 */
	private function sanitizeTagAttribute($string)
	{
		$search = array(
			'/(<[^>]*)([\s]on[^>]*=)([^>]*>)/isU' // onClick=などの on*イベントの禁止
		);
		$replace = array(
			'$1 $3'
		);
		foreach($this->NgAttributes as $attribute){
			$search[] = '/(<[^>]*)([\s]'.$attribute.'[^>]*=)([^>]*>)/isU';  // onClick=などの on*イベントの禁止
			$replace[] = '$1 $3';
		}
		$sanitizedString =  preg_replace($search, $replace, $string);
		return $sanitizedString;
	}

	/**
	 * a タグ、imgタグのURIサニタイズ
	 */	
	private function sanitizeUri($string)
	{
		$sanitizedString = preg_replace('/(<[^>]*)(href|src)[\s]*=["\']([\s]*(javascript|vbscript|about))([^>]*>)/isU', '$1$2>', $string);	
		return $sanitizedString;
	}
}
?>