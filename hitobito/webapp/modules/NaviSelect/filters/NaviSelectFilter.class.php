<?php

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class NaviSelectFilter extends HNbActionFilter
{
	
	
	public function fetch($context)
	{
		$this->context = $context;
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		if($navipage_id > 0){
			$this->addParameter('navipage_id', $navipage_id);
		}
		$request = $context->getRequest();
		if($request->hasParameter('category_id')){
			$this->addParameter('category_id', intval($request->getParameter('category_id')));
		}
	}
	
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('navipage_id')){
			$blog_id = hitobito::getNaviPageInfo()->getBlog_id();
			$criteria->addWhere('bla_blog_id='.$blog_id);
		}
		if($this->hasParameter('category_id')){
        	$criteria->addWhere('nvp_channel_id='.$this->getParameter('category_id'));
        }
  
		return $criteria;
	}
	
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setWhere('bla_open_flag=1 AND bla_navi_select_flag > 0');
		$criteria->setOrder("ORDER BY bla_date DESC");
		return $criteria;
	}

}
?>