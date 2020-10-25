<?php
/**
* waiwai�⥸�塼��Υ��󥿡��ե�����
* @author Ryuji
* @version $Id: WaiwaiInterface.class.php,v 1.4 2006/06/07 07:32:07 aizawa Exp $
*/

require_once MO_WEBAPP_DIR.'/modules/Navi/lib/NaviPageModuleInterface.class.php';
require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiDatabase.class.php');

class WaiwaiInterface2NaviPage implements NaviPageModuleInterface
{
    /**
    * ̤��ǧ�Υ����ȥ��С������̥桼������ξ�ǧ�Ԥ������ä������Τ���
    */
    public function getNotify2Navi($navipageId)
    {
        $messages = array();
        $waiwaiDB = new WaiwaiDatabase();
        $roomId = $waiwaiDB->getRoomIdFromNaviPageId($navipageId);
		if($waiwaiDB->checkGuestMember($roomId) =='t'){
		    $messages[] = '��������Ͽ���С������ޤ�����ǧ��Ȥ�ԤʤäƤ���������';
		}
		// ���Ѥ��ʤ����Ȥˤʤä��Τǥ����ȥ����� seq 315
//		if($waiwaiDB->checkPublicPost($roomId) == 't'){
//		    $messages[] = '���̥桼�������������Ԥ�������ޤ�����ǧ��Ȥ�ԤʤäƤ���������';
//		}
        
        return $messages;
    }
}

?>