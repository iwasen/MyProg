<?php
/**
 * �᡼����������
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: notify_configAction.class.php,v 1.1 2006/04/30 08:43:59 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Navi/forms/notifyConfigForm.class.php';

require_once MO_WEBAPP_DIR.'/hitobito/db/t_navi_page_notify.class.php';
class notify_configAction extends HNbEditAction
{
	protected function fetchID()
	{
		return hitobito::getNaviPageInfo()->getId();
	}

	protected function getManager()
	{
		return new NaviNotifyConfigHandler();
	}
	
	protected function getActionForm()
	{
		// notify_target�˱�����form���ѹ�����
		$request = $this->getContext()->getRequest();
		$target = $request->getParameter('navi_notify_target');
		switch($target){
		    case 'comment':
		        $form = new NaviCommentNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('������'));
		        break;
		    case 'trackback':
		        $form = new NaviTrackbackNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('�ȥ�å��Хå�'));
		        break;
		    case 'contact':
		        $form = new NaviContactNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('�䤤��碌'));
		        break;
		    default:
		        die('Bad request');
		    }
		$request->setAttribute('notify_target', $target);
		return $form;
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		$obj->setAttribute('nnt_navi_page_id', hitobito::getNaviPageInfo()->getId());
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
		// ======================================================================= 
    	Navi::GuardNaviEdit($this->getContext()->getUser());

		return true;
	}

	public function isSecure()
	{
		return true;
	}
}

class NaviNotifyConfigHandler extends t_navi_page_notifyObjectHandler
{
    protected function insertObject($obj)
	{
		$vars = $obj->getAttributes();
        //unset($vars[$this->pkey]);
        $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
		return $rs;
	}
}


?>
