<?php
/**
 * Blog module action base
 * 
 * @author Ryuji
 * 
 * @version $Id: BlogBaseAction.class.php,v 1.2 2006/02/06 06:18:55 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogCategory.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogComment.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogTrackback.class.php';
require_once MO_WEBAPP_DIR .'/lib/HNbTrackBack.class.php';

abstract class BlogBaseAction extends Action
{
	protected $navipage;
	protected $blog_id;
	public function initialize($context)
	{
		parent::initialize($context);
		
		$this->navipage = hitobito::getNaviPageInfo();
		$request = $context->getRequest();
		
		$request->setAttribute('navipage_id', $this->navipage->getId());
		
		$this->blog_id = $this->navipage->getBlog_id();
		return TRUE;
	}
	

}
?>