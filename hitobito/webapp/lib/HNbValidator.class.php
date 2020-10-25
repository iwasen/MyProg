<?php
/**
 * @package HNb
 * @author Ryuji
 * @version $Id: HNbValidator.class.php,v 1.2 2006/01/18 17:50:06 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/pear/PEAR/Validate.php';
 
class HNbValidator
{
	/**
	 * emailの妥当性検査
	 * 
	 * @param	string email
	 * @return	Bool
	 */
	public function validateEmail($mail)
	{
		return Validate::Email($mail);
//		return preg_match('/^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$/', 
//		$mail) ? TRUE : FALSE;
		
	}
	
	public function validateUrl($url)
	{
		// pear validate だと http://example.com/ でfalseになるので単純なものに変更
	    if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $url)) {
	        return TRUE;
	    } else {
	        return FALSE;
	    }
	}
	
}

/** 
 * miss spel 対策
 */
class HNbValidater extends HNbValidator
{
}
?>