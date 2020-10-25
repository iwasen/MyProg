<?php
/**
 * メール通知設定
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: daily_report_configAction.class.php,v 1.2 2006/05/06 04:57:12 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Navi/forms/dailyReportConfigForm.class.php';

require_once MO_WEBAPP_DIR.'/hitobito/db/t_navi_page_daily_report.class.php';
class daily_report_configAction extends HNbEditAction
{
	protected function fetchID()
	{
		return hitobito::getNaviPageInfo()->getId();
	}

	protected function getManager()
	{
		return new NaviDailyReportConfigHandler();
	}
	
	protected function getActionForm()
	{
		return new NaviDailyReportConfigForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		$obj->setAttribute('ndr_navi_page_id', hitobito::getNaviPageInfo()->getId());
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

class NaviDailyReportConfigHandler extends t_navi_page_daily_reportObjectHandler
{
    protected function insertObject($obj)
	{
		$vars = $obj->getAttributes();
        $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
		return $rs;
	}
}


?>