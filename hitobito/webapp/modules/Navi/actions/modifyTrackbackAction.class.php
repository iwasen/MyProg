<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyTrackbackAction.class.php,v 1.2 2006/01/11 13:53:13 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Navi/forms/trackbackForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogTrackback.class.php';
class modifyTrackbackAction extends HNbEditAction
{
	protected function fetchID()
	{
		$item_id =  $this->request->getParameter('navi_item_id');
		list($parentContentsId, $trackbackNo) = explode('-', $item_id);
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return array('mmt_melmaga_contents_id' => $parentContentsId, 'mmt_trackback_no' => $trackbackNo);
			case 'enquete':
				return array('net_enquete_id' => $parentContentsId, 'net_trackback_no' => $trackbackNo);
			case 'blog':
			default:
				return array('blt_article_id' => $parentContentsId, 'blt_trackback_no' => $trackbackNo);
		}
		
	}

	protected function getManager()
	{
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return HNb::getDBHandler('l_melmaga_trackback');
			case 'enquete':
				return HNb::getDBHandler('l_navi_enq_trackback');
			case 'blog':
			default:
				return new BlogTrackbackManager();
		}
	}
	
	protected function getActionForm()
	{
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return new MelmagaAdminModifyTrackbackForm();
			case 'enquete':
				return new EnqueteAdminModifyTrackbackForm();
			case 'blog':
			default:
				return new BlogAdminModifyTrackbackForm();
		}
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id); 
		die('invalid parameter');
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

?>
