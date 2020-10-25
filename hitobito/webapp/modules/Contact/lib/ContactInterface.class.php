<?php
/**
* ���󥿥��ȥ⥸�塼��Υ��󥿡��ե�����
* @author Ryuji
* @version $Id: ContactInterface.class.php,v 1.1 2006/02/17 18:06:59 ryu Exp $
*/

require_once MO_WEBAPP_DIR.'/modules/Navi/lib/NaviPageModuleInterface.class.php';
require_once dirname(__FILE__).'/../config/config.inc.php';

class ContactInterface2NaviPage implements NaviPageModuleInterface
{
    /**
    * �ֿ����Ƥʤ��䤤��碌�����ä������Τ���
    */
    public function getNotify2Navi($navipageId)
    {
        $messages = array();
        $criteria = new HNbCriteria();
        $criteria->addWhere('nvi_navi_page_id='. $navipageId. ' AND nvi_status='.CONTACT_STATUS_IS_NOT_REPLY);
        $hanlder = HNb::getDBHandler('t_navi_inquiry');
        if($hanlder->getCount($criteria)){
            $messages[] = '̤�������䤤��碌������ޤ�';
        }
        return $messages;
    }
}

?>