<?php
/**
 * @package Blog
 * @author Ryuji
 * @version $Id: NgwordValidator.class.php,v 1.5 2008/08/28 06:12:32 aizawa Exp $
 */
class BlogNgwordValidator 
{
	private
		$NgWords = array(),
		$NgUrls = array()
	;
	public function __construct($navipage_id)
	{
		$db = HNb::getAdodb();
		$sql = "SELECT bng_type, bng_word FROM t_blog_ngword WHERE bng_status=1 AND bng_navi_page_id=".$navipage_id;
		
		$rs = $db->Execute($sql);
		while($row = $rs->FetchRow()){
			if($row['bng_type'] ==1){
				$this->NgUrls[] = $row['bng_word'];
			}elseif($row['bng_type']==2){
				$this->NgWords[] = $row['bng_word'];
			}
		}

		// NGワードマスタ(m_ngword)によるチェックを追加　2008/06/10 aizawa
		$sql = "SELECT ngw_word FROM m_ngword WHERE ngw_status=1";
		$rs = $db->Execute($sql);
		while($row = $rs->FetchRow()){
			$this->NgWords[] = $row['ngw_word'];
		}
	}
	/**
	 * NGワードとNG URLが含まれていないかチェックする
	 * @return 含まれていなければTRUE; 含まれていればFALSE;
	 * 
	 */
	public function validateText($text)
	{
		$list = array_merge($this->NgWords, $this->NgUrls);
		if(count($list) == 0){
			// NGリストが無ければTRUE
			return TRUE;
		}
		return $this->checkNgWord($text, $list);
/*
		$match = $this->getOrPatternByArray($list);
		if(preg_match($match, mb_convert_kana($text, 'rHcV'))){
			return FALSE;
		}else{
			return TRUE;
		}
*/
	}
	
	/**
	 * NG URLが含まれていないかチェックする
	 * @return 含まれていなければTRUE; 含まれていればFALSE;
	 */ 
	public function validateUrl($text)
	{
		if(count($this->NgUrls) == 0){
			// NGリストが無ければTRUE
			return TRUE;
		}
		return $this->checkNgWord($text, $this->NgUrls);
/*
		$match = $this->getOrPatternByArray($this->NgUrls);
		if(preg_match($match, $text)){
			return FALSE;
		}else{
			return TRUE;
		}
*/
	}
/*
	private function getOrPatternByArray($array)
	{
		$list = array();
		foreach($array as $str){
			$list[] = preg_quote(mb_convert_kana($str, 'rHcV'), '/');
		}
		$match = '/(' . implode('|', $list) . ')/i';
		return $match;
	}
*/
	// NGワーチェック　2008/08/28 aizawa
	// 正規表現を使った方法ではNGワードが増えた場合に動作しなくなるので１つずつチェックする
	private function checkNgWord($text, &$array) {
		$check_text = strtoupper(mb_convert_kana($text, 'rHcV'));

		foreach($array as $str) {
			if (strpos($check_text, strtoupper(mb_convert_kana($str, 'rHcV'))) !== false)
				return false;
		}
		return true;
	}
}
?>