<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: UploadImageAction.class.php,v 1.3 2006/01/27 09:50:31 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Blog/forms/imageForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogImage.class.php';

class UploadImageAction extends HNbEditAction
{
	protected function fetchID()
	{
		return 0;
	}

	protected function getManager()
	{
		return new BlogImageManager();
	}
	
	protected function getActionForm()
	{
		return new BlogNewImageForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		$blogId = hitobito::getNaviPageInfo()->getBlog_id();		 
		$obj->setAttribute('bli_blog_id', $blogId);
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
		// ======================================================================= 
		Navi::GuardNaviEdit($this->getContext()->getUser());
		return TRUE;
//		if($this->user->isMyNaviPage()){
//			return TRUE;
//		}else{
//			$this->request->setAttribute('message', HNb::tr('�ե����륢�åץ��ɸ��¤�����ޤ���'));
//			return FALSE;
//		}
	}

	public function isSecure()
	{
		return true;
	}
}

?>
