<?php
require_once MO_WEBAPP_DIR .'/hitobito/db/t_navi_osusume_link.class.php';
class WeblinksLink extends t_navi_osusume_linkObject
{
	private $navipage;
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getTitle()
	{
		return $this->getAttribute('nol_link_name');
	}
	public function getNaviPageInfo()
	{
		if(!$this->navipage){
			$criteria = new HNbCriteria();
			$criteria->setWhere('nvp_navi_page_id='.$this->getAttribute('nol_navi_page_id'));
			$navipageHandler = new hitobitoNavipageManager();
			$this->navipage = $navipageHandler->getOne($criteria);
		}
		return $this->navipage;
	}
	public function getPosterName()
	{
    	$handler = HNb::getHandler('MemberManager');
    	$poster = $handler->get($this->getAttribute('nol_member_id'));
    	return $poster->getName();
	}
	public function getCategoryName()
	{
	    $categoryManager = WeblinksCategoryManager::getInstance();
	    $category = $categoryManager->cacheGet($this->getAttribute('nol_link_category_id'));
	    return $category->getTitle();
	}
	public function getCategoryId()
	{
	    return $this->getAttribute('nol_link_category_id');
	}
}

class WeblinksLinkManager extends t_navi_osusume_linkObjectHandler
{
	 /**
	  * VO���饹̾
	  */
	 protected $class = 'WeblinksLink';
	 
	 public function get($id)
	 {
	 	if($id > 0){
	 		return parent::get($id);
	 	}else{
	 		$obj =  $this->create();
	 		$obj->setNew();
	 		return $obj;
	 	}
	 }
	 
	 public function insert($obj)
	 {
	 	if($obj->getAttribute($this->pkey) > 0){
	 		$result = parent::insert($obj);
	 	}else{
       	// �ڡ����ӥ塼�����󥿽����
        	$page_view_id = hitobito::getNewPageViewId();
        	$obj->setAttribute('nol_pv_id', $page_view_id);
	 		//$lastOrder = $this->db->GetOne('SELECT nol_order FROM '.$this->table.' WHERE nol_navi_page_id='.$obj->getAttribute('nol_navi_page_id'). ' ORDER BY nol_order DESC');
	 		$obj->setAttribute('nol_order', 0);
	 		$result = parent::insert($obj);
	 	}
	 	if($result){
	 		$this->updateCategoryLinkNum();
	 		return TRUE;
	 	}
	 	return FALSE;
	 }
	 
	 public function delete($obj)
	 {
	 	if(parent::delete($obj)){
	 		$this->updateCategoryLinkNum();
	 	}
	 	return FALSE;
	 }
	 
 	private function updateCategoryLinkNum()
	{
		$this->db->Execute('UPDATE m_link_category ' .
				'SET lct_link_num=(SELECT count(*) FROM t_navi_osusume_link WHERE nol_link_category_id=lct_link_category_id) ');
	}
 
	/**
	 * �¤ӽ�򤢤���
	 * 
	 * Link_order�ξ��������ƥ����Link_order�������ؤ���
	 */
	public function orderUp($obj)
	{
		$this->exchangeOrder($obj, 'up');
		return;
	}
	
	/**
	 * �¤ӽ�򲼤���
	 */
	public function orderDown($obj)
	{
		$this->exchangeOrder($obj, 'down');
		return ;
	}

	/**
	 * orderUp, orderDown����ƤӽФ�
	 * 
	 * @param	WeblinksLink �¤ӽ��ѹ��򤹤륫�ƥ���
	 * @param	string	�����ؤ��оݥ��ƥ��ꡡup or down
	 */
	private function exchangeOrder($sourceLink, $targetOrdedr)
	{
		$navipage_id = $sourceLink->getAttribute('nol_navi_page_id');
		$Link_order = $sourceLink->getAttribute('nol_order');
		switch($targetOrdedr){
			case 'down':
				$sql = sprintf('SELECT nol_osusume_link_id FROM %s WHERE nol_navi_page_id=%d AND nol_order < %d ORDER BY nol_order DESC',
						$this->table,
						$navipage_id,
						$Link_order);
				break;
			case 'up':
				$sql = sprintf('SELECT nol_osusume_link_id FROM %s WHERE nol_navi_page_id=%d AND nol_order > %d ORDER BY nol_order ASC',
						$this->table,
						$navipage_id,
						$Link_order);
						break;
		}
				
		$exchangeLinkId = $this->db->GetOne($sql);
		if($exchangeLinkId > 0){
			$exchangeLink = $this->get($exchangeLinkId);
			$exchangeOrder = $exchangeLink->getAttribute('nol_order');
			$sourceOrder = $sourceLink->getAttribute('nol_order');
			$exchangeLink->setAttribute('nol_order', $sourceOrder);
			$sourceLink->setAttribute('nol_order', $exchangeOrder);
			$this->insert($sourceLink);
			$this->insert($exchangeLink);
		}
	}
	
	public function getItemsByCategoryId($categoryId)
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder('ORDER BY nol_date DESC'); ///< default�Ϻǿ���Ȥ���
		
		if($categoryId > 0){
			$criteria->addWhere('nol_link_category_id='.$categoryId);
			// ���ƥ�����Τ��¤ӽ������ͥ�褵����
			$criteria->setOrder('ORDER BY nol_order ASC, nol_date DESC');
		}
		if(hitobito::getNaviPageInfo()->getId() > 0)
		{
			$criteria->addWhere('nol_navi_page_id='.hitobito::getNaviPageInfo()->getId());
		}
		return $this->getObjects($criteria);
	}
}
?>