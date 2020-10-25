<?php
require_once MO_WEBAPP_DIR .'/lib/HNbValueObject.class.php';
require_once MO_WEBAPP_DIR .'/lib/HNbValueObjectHandler.class.php';

class BlogCategory extends HNbValueObject
{
	protected $pkey='blog_category_id';
	protected $table = 'blog_category';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['blog_category_id'] = 0;
		$this->attribute['blog_id'] = 0;
		$this->attribute['category_name'] = '';
		$this->attribute['category_order'] = 0;
	}
	
	public function getCategoryName()
	{
		return $this->getAttribute('category_name');
	}
	
	public function setBlog_id($blog_id)
	{
		$this->setAttribute('blog_id', $blog_id);
	}
	
}

class BlogCategoryManager extends HNbValueObjectHandler
{
	/**
	 * �ץ饤�ޥꥭ��
	 */
	protected $pkey = 'blog_category_id';
	
	/**
	 * �ơ��֥�̾
	 */
	 protected $table = 'blog_category';
	 
	 /**
	  * VO���饹̾
	  */
	 protected $class = 'BlogCategory';
	 
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
	 		$lastOrder = $this->db->GetOne('SELECT category_order FROM blog_category WHERE blog_id='.$obj->getAttribute('blog_id'). ' ORDER BY category_order DESC');
	 		$obj->setAttribute('category_order', $lastOrder+1);
	 		return parent::insert($obj);
	 	}
	 }
	 
	public function getCategoriesByBlogId($blog_id)
	{
		$where = 'blog_id='.$blog_id . ' ORDER BY category_order ASC';
		return $this->getObjects($where);
	}
		 
	/**
	 * ���ƥ����¤ӽ�򤢤���
	 * 
	 * category_order�ξ��������ƥ����category_order�������ؤ���
	 */
	public function orderUp($obj)
	{
		$this->exchangeOrder($obj, 'up');
		return;
	}
	
	/**
	 * ���ƥ����¤ӽ�򲼤���
	 */
	public function orderDown($obj)
	{
		$this->exchangeOrder($obj, 'down');
		return ;
	}

	/**
	 * orderUp, orderDown����ƤӽФ�
	 * 
	 * @param	BlogCategory �¤ӽ��ѹ��򤹤륫�ƥ���
	 * @param	string	�����ؤ��оݥ��ƥ��ꡡup or down
	 */
	private function exchangeOrder($sourceCategory, $targetOrdedr)
	{
		$blog_id = $sourceCategory->getAttribute('blog_id');
		$category_order = $sourceCategory->getAttribute('category_order');
		switch($targetOrdedr){
			case 'up':
				$sql = sprintf('SELECT blog_category_id FROM blog_category WHERE blog_id=%d AND category_order < %d ORDER BY category_order DESC',
						$blog_id,
						$category_order);
				break;
			case 'down':
				$sql = sprintf('SELECT blog_category_id FROM blog_category WHERE blog_id=%d AND category_order > %d ORDER BY category_order ASC',
						$blog_id,
						$category_order);
						break;
		}
				
		$exchangeCategoryId = $this->db->GetOne($sql);
		if($exchangeCategoryId > 0){
			$exchangeCategory = $this->get($exchangeCategoryId);
			$exchangeOrder = $exchangeCategory->getAttribute('category_order');
			$sourceOrder = $sourceCategory->getAttribute('category_order');
			$exchangeCategory->setAttribute('category_order', $sourceOrder);
			$sourceCategory->setAttribute('category_order', $exchangeOrder);
			$this->insert($sourceCategory);
			$this->insert($exchangeCategory);
		}
		
	}
}
?>