<?php
require_once MO_WEBAPP_DIR .'/hitobito/db/m_link_category.class.php';
class WeblinksCategory extends m_link_categoryObject
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCategoryName()
	{
		return $this->getAttribute('lct_category_name');
	}
	
	public function getTitle()
	{
		return $this->getAttribute('lct_category_name');
	}
	
	public function canDelete()
	{
		// このカテゴリに登録されているアイテムがあったら削除不可
		$linkManager = new WeblinksLinkManager();
		$criteria = new HNbCriteria();
		$criteria->setWhere('nol_link_category_id='.$this->getId());
		if($linkManager->getCount($criteria)){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function getOpenItemCount($navipageid)
	{
		$linkManager = new WeblinksLinkManager();
		$criteria = new HNbCriteria();
		$criteria->setWhere('nol_link_category_id='.$this->getId());
		$criteria->addWhere('nol_open_flag=1');
		if($navipageid > 0){
			$criteria->addWhere('nol_navi_page_id='.$navipageid);
		}
		return $linkManager->getCount($criteria);
	}
	
}

class WeblinksCategoryManager extends m_link_categoryObjectHandler
{
	 /**
	  * VOクラス名
	  */
	 protected $class = 'WeblinksCategory';

	private static $instance;
	public static function getInstance()
	{
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }
       return self::$instance;
	}
	 
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
	 		return parent::insert($obj);
	 	}else{
//	 		$lastOrder = $this->db->GetOne('SELECT lct_order FROM '.$this->table.' WHERE lct_navi_page_id='.$obj->getAttribute('lct_navi_page_id'). ' ORDER BY lct_order DESC');
	 		$lastOrder = $this->db->GetOne('SELECT lct_order FROM '.$this->table.' ORDER BY lct_order DESC');
	 		$obj->setAttribute('lct_order', $lastOrder+1);
	 		return parent::insert($obj);
	 	}
	 }
	 
	 
	 /**
	  * 指定されたnavipageid のオープンリンクが存在するカテゴリを返す
	  */
	public function getCategoriesByNavipageId($navipageid)
	{
		$sql = "SELECT DISTINCT m_link_category.* FROM m_link_category JOIN t_navi_osusume_link ON lct_link_category_id=nol_link_category_id " .
				" WHERE nol_open_flag=1 ";
		if($navipageid > 0){
			$sql .=" AND nol_navi_page_id=".$navipageid;
		}
		$sql .= ' ORDER BY lct_order ASC';
		return $this->getObjectsBySql($sql);
	}
	
	/**
	 * 指定されたナビページIDで作成されたカテゴリを得る
	 */
	public function getCategoriesByModifyNavipageId($navipageId)
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder('ORDER BY lct_order ASC');
		$criteria->setWhere('lct_navi_page_id='. $navipageId);
		return $this->getObjects($criteria);
	}
	
	public function getCategories()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder('ORDER BY lct_order ASC');
		$criteria->setWhere('1=1');
		return $this->getObjects($criteria);
	}

	
		 
	/**
	 * カテゴリ並び順をあげる
	 * 
	 * category_orderの小さいカテゴリとcategory_orderを入れ替える
	 */
	public function orderUp($obj)
	{
		$this->exchangeOrder($obj, 'up');
		return;
	}
	
	/**
	 * カテゴリ並び順を下げる
	 */
	public function orderDown($obj)
	{
		$this->exchangeOrder($obj, 'down');
		return ;
	}

	/**
	 * orderUp, orderDownから呼び出す
	 * 
	 * @param	WeblinksCategory 並び順変更をするカテゴリ
	 * @param	string	入れ替え対象カテゴリ　up or down
	 */
	private function exchangeOrder($sourceCategory, $targetOrdedr)
	{
		$navipage_id = $sourceCategory->getAttribute('lct_navi_page_id');
		$category_order = $sourceCategory->getAttribute('lct_order');
		switch($targetOrdedr){
			case 'up':
//				$sql = sprintf('SELECT lct_link_category_id FROM m_link_category WHERE lct_navi_page_id=%d AND lct_order < %d ORDER BY lct_order DESC',
//						$navipage_id,
				$sql = sprintf('SELECT lct_link_category_id FROM m_link_category WHERE lct_order < %d ORDER BY lct_order DESC',
						$category_order);
				break;
			case 'down':
//				$sql = sprintf('SELECT lct_link_category_id FROM m_link_category WHERE lct_navi_page_id=%d AND lct_order > %d ORDER BY lct_order ASC',
//						$navipage_id,
				$sql = sprintf('SELECT lct_link_category_id FROM m_link_category WHERE lct_order > %d ORDER BY lct_order ASC',
						$category_order);
						break;
		}
				
		$exchangeCategoryId = $this->db->GetOne($sql);
		if($exchangeCategoryId > 0){
			$exchangeCategory = $this->get($exchangeCategoryId);
			$exchangeOrder = $exchangeCategory->getAttribute('lct_order');
			$sourceOrder = $sourceCategory->getAttribute('lct_order');
			$exchangeCategory->setAttribute('lct_order', $sourceOrder);
			$sourceCategory->setAttribute('lct_order', $exchangeOrder);
			$this->insert($sourceCategory);
			$this->insert($exchangeCategory);
		}
		
	}
}
?>