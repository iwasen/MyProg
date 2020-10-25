<?php

class _blockShowCategoryMenuAction extends Action
{
	public function execute()
	{
		$controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		$navipage = hitobito::getNaviPageInfo();
		$blogId = $navipage->getBlog_id();
		$handler = HNb::getDBHandler('blog_category');
		
		$criteria = new HNbCriteria();
		$criteria->setWhere('blog_id='.$blogId);
		$criteria->setOrder('ORDER BY category_order ASC');
		
		$list = $handler->getObjects($criteria);
		$request->setAttribute('blog_category_list', $list);
		return View::SUCCESS;
		
	}
}
?>