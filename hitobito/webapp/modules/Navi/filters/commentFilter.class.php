<?php
/**
 *
 *
 * 
 * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: commentFilter.class.php,v 1.1 2006/01/11 06:27:21 ryu Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class NaviCommentFilter extends HNbActionFilter
{
	public function fetch($context)
	{
		$this->context = $context;
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		if($navipage_id > 0){
			$this->addParameter('navipage_id', $navipage_id);
		}
	}
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('navipage_id')){
			$criteria->addWhere('nvp_navi_page_id='.$this->getParameter('navipage_id'));
		}
		return $criteria;
	}
}
class BlogCommentFilter extends NaviCommentFilter
{
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY blo_date DESC");
		return $criteria;
	}

}
class MelmagaCommentFilter extends NaviCommentFilter
{
	public function fetch($context)
	{
		parent::fetch($context);
		$this->addParameter('navi_list_target', 'melmaga');
	}
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY mmo_date DESC");
		return $criteria;
	}

}
class EnqueteCommentFilter extends NaviCommentFilter
{
	public function fetch($context)
	{
		parent::fetch($context);
		$this->addParameter('navi_list_target', 'enquete');
	}
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('navipage_id')){
			$criteria->addWhere('nen_navi_page_id='.$this->getParameter('navipage_id'));
		}
		return $criteria;
	}
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY neo_date DESC");
		return $criteria;
	}

}
?>