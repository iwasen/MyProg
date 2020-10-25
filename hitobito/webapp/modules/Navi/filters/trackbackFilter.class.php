<?php
/**
 *
 *
 * 
 * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: trackbackFilter.class.php,v 1.1 2006/01/11 13:53:13 ryu Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class NaviTrackbackFilter extends HNbActionFilter
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
class BlogTrackbackFilter extends NaviTrackbackFilter
{
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY blt_date DESC");
		return $criteria;
	}

}
class MelmagaTrackbackFilter extends NaviTrackbackFilter
{
	public function fetch($context)
	{
		parent::fetch($context);
		$this->addParameter('navi_list_target', 'melmaga');
	}
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY mmt_date DESC");
		return $criteria;
	}

}
class EnqueteTrackbackFilter extends NaviTrackbackFilter
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
		$criteria->setOrder("ORDER BY net_date DESC");
		return $criteria;
	}

}
?>