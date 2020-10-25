<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCommentAction.class.php,v 1.2 2006/01/11 11:20:41 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Navi/forms/commentForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogComment.class.php';
class modifyCommentAction extends HNbEditAction
{
	protected function fetchID()
	{
		$item_id =  $this->request->getParameter('navi_item_id');
		list($parentContentsId, $commentNo) = explode('-', $item_id);
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return array('mmo_melmaga_contents_id' => $parentContentsId, 'mmo_comment_no' => $commentNo);
			case 'enquete':
				return array('neo_enquete_id' => $parentContentsId, 'neo_comment_no' => $commentNo);
			case 'blog':
			default:
				return array('blo_article_id' => $parentContentsId, 'blo_comment_no' => $commentNo);
		}
		
	}

	protected function getManager()
	{
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return HNb::getDBHandler('l_melmaga_comment');
			case 'enquete':
				return HNb::getDBHandler('l_navi_enq_comment');
			case 'blog':
			default:
				return new BlogCommentManager();
		}
	}
	
	protected function getActionForm()
	{
		switch($this->request->getParameter('navi_modify_target')){
			case 'melmaga':
				return new MelmagaAdminModifyCommentForm();
			case 'enquete':
				return new EnqueteAdminModifyCommentForm();
			case 'blog':
			default:
				return new BlogAdminModifyCommentForm();
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