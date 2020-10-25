<?php
/**
 * メール通知設定
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
		// notify_targetに応じてformを変更する
		$request = $this->getContext()->getRequest();
		$target = $request->getParameter('navi_notify_target');
		switch($target){
		    case 'comment':
		        $form = new NaviCommentNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('コメント'));
		        break;
		    case 'trackback':
		        $form = new NaviTrackbackNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('トラックバック'));
		        break;
		    case 'contact':
		        $form = new NaviContactNotifyConfigForm();
		        $request->setAttribute('notify_target_name', HNb::tr('問い合わせ'));
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
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		$obj->setAttribute('nnt_navi_page_id', hitobito::getNaviPageInfo()->getId());
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
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
