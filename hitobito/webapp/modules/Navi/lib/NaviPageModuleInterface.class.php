<?php
/**
* ナビページで使われるモジュールの共通インターフェース
*  
* @author Ryuji
* @version $Id: NaviPageModuleInterface.class.php,v 1.1 2006/02/17 18:06:59 ryu Exp $
*/

interface NaviPageModuleInterface
{
    /**
    * ナビに通知が必要なメッセージを返す
    *
    * @return   array メッセージを配列に積んで返す
    */
    public function getNotify2Navi($navipageId);
}

?>