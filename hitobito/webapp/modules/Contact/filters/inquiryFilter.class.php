<?php
/**
 *
 *
 * 
 * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: inquiryFilter.class.php,v 1.1 2005/12/22 16:24:31 ryu Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class ContactInquiryFilter extends HNbActionFilter
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
			$criteria->addWhere('nvi_navi_page_id='.$this->getParameter('navipage_id'));
		}
		return $criteria;
	}
	
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY nvi_date DESC");
		return $criteria;
	}

}
?>