<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: editImageAction.class.php,v 1.3 2006/01/27 09:50:31 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Blog/forms/imageForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogImage.class.php';

class editImageAction extends HNbEditAction
{
	protected function fetchID()
	{
		return intval($this->request->getParameter('blog_image_id', 0));
	}

	protected function getManager()
	{
		return new BlogImageManager();
	}
	
	protected function getActionForm()
	{
		return new BlogImageForm();
	}

	protected function setUpNewObject(&$obj)
	{
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// =======================================================================
		Navi::GuardNaviEdit($this->getContext()->getUser());
		if($obj->isNew()){
			$this->request->setAttribute('message', HNb::tr('ファイルが存在しません'));
			return FALSE;
		}
		return TRUE; 
//		if($this->user->isMyNaviPage()){
//			
//			return TRUE;
//		}else{
//			$this->request->setAttribute('message', HNb::tr('ファイル編集権限がありません'));
//			return FALSE;
//		}
	}

	public function isSecure()
	{
		return true;
	}
}

?>
