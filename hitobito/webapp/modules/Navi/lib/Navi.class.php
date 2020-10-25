<?php
/*
* Navi モジュールインターフェース
* @author Ryuji
* @version $Id: Navi.class.php,v 1.6 2006/02/16 11:16:25 ryu Exp $
*/
class Navi 
{
    private static $isNaviEditActionExecution = FALSE;
	public function GuardNaviEdit($user)
	{
	    self::$isNaviEditActionExecution = TRUE;
	   
		if(!$user->isAuthenticated()){
			header('Location: index.php?module=Member&action=Login');
			exit();
		}
		if($user->isMyNaviPage()){
			return TRUE;
		}
		if($user->isMyAssistNaviPage()){
			return TRUE;
		}
		header('HTTP/1.0 403 Forbidden');
		die('Forbidden');
	}
	/**
	* 
	* @return  boolian  ナビエディットアクション実行中ならTRUE. 
	*/
	public static function isNaviEditActionExecution()
	{
	    return self::$isNaviEditActionExecution;
	}
}
?>