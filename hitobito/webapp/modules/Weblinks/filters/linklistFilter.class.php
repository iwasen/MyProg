<?php
/**
 *
 *
 * 
 * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: linklistFilter.class.php,v 1.3 2006/06/12 06:17:14 ryu Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class WeblinkLinkListFilter extends HNbActionFilter
{
	
	public function fetch($context)
	{
		$this->context = $context;
		$navipage_id = hitobito::getNaviPageInfo()->getId();
		if($navipage_id > 0){
			$this->addParameter('navipage_id', $navipage_id);
		}
		$request = $context->getRequest();
		if($request->hasParameter('weblinks_category_id')){
			$this->addParameter('weblinks_category_id', intval($request->getParameter('weblinks_category_id')));
		}	
	}
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('navipage_id')){
			$criteria->addWhere('nol_navi_page_id='.$this->getParameter('navipage_id'));
//			$criteria->setOrder('ORDER BY nol_order ASC, nol_date DESC');
			$criteria->setOrder('ORDER BY nol_date DESC');
		}
		if($this->hasParameter('weblinks_category_id')){
			$criteria->addWhere('nol_link_category_id='.$this->getParameter('weblinks_category_id'));
		}
		
		// order
		if($this->hasParameter('navipage_id') && $this->hasParameter('weblinks_category_id')){
			$criteria->setOrder("ORDER BY nol_order ASC, nol_date DESC");
		}		
		
		return $criteria;
	}
	
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setWhere('nol_open_flag=1 ');
//		$criteria->setOrder("ORDER BY nol_order ASC, nol_date DESC");
		$criteria->setOrder("ORDER BY nol_date DESC");
		return $criteria;
	}

}
?>