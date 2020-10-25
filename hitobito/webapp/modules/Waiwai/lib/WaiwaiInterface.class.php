<?php
/**
* waiwaiモジュールのインターフェース
* @author Ryuji
* @version $Id: WaiwaiInterface.class.php,v 1.4 2006/06/07 07:32:07 aizawa Exp $
*/

require_once MO_WEBAPP_DIR.'/modules/Navi/lib/NaviPageModuleInterface.class.php';
require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiDatabase.class.php');

class WaiwaiInterface2NaviPage implements NaviPageModuleInterface
{
    /**
    * 未承認のゲストメンバー、一般ユーザからの承認待ちがあったら通知する
    */
    public function getNotify2Navi($navipageId)
    {
        $messages = array();
        $waiwaiDB = new WaiwaiDatabase();
        $roomId = $waiwaiDB->getRoomIdFromNaviPageId($navipageId);
		if($waiwaiDB->checkGuestMember($roomId) =='t'){
		    $messages[] = 'ゲスト登録メンバーがいます。承認作業を行なってください。';
		}
		// 利用しないことになったのでコメントアウト seq 315
//		if($waiwaiDB->checkPublicPost($roomId) == 't'){
//		    $messages[] = '一般ユーザーからの投稿待ちがあります。承認作業を行なってください。';
//		}
        
        return $messages;
    }
}

?>