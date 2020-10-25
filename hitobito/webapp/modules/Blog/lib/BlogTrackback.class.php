<?php
require_once MO_WEBAPP_DIR .'/lib/HNbValueObject.class.php';
require_once MO_WEBAPP_DIR .'/lib/HNbValueObjectHandler.class.php';

class BlogTrackback extends HNbValueObject
{
	protected $pkey = array('blt_article_id', 'blt_trackback_no');
	 protected $table = 't_blog_trackback';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['blt_article_id'] = 0;
		$this->attribute['blt_trackback_no'] = 0;
		$this->attribute['blt_date'] = date('Y-m-d H:i:s');
		$this->attribute['blt_title'] = '';
		$this->attribute['blt_excerpt'] = '';
		$this->attribute['blt_url'] = '';
		$this->attribute['blt_blog_name'] = '';
		$this->attribute['blt_ng_word'] = '';
		$this->attribute['blt_patrol_check'] = 1;
		$this->attribute['blt_patrol_date'] = null;
		$this->attribute['blt_patrol_id'] = null;
		$this->attribute['blt_admin_check'] = 1;
		$this->attribute['blt_admin_date'] = null;
		$this->attribute['blt_admin_id'] = null;
		$this->attribute['blt_open_flag'] = 1;
		$this->attribute['blt_pv_id'] = 0;

	}
	public function getId()
	{
		$ret = array();
		foreach($this->pkey as $key){
			$ret[$key] = $this->getAttribute($key);
		}
		return $ret;
	}
	
	public function setId($id)
	{
		foreach($id as $key => $var){
			$this->setAttribute($key, $var);
		}
	}
	
	public function getTitle()
	{
		return $this->getAttribute('blt_title');
	}
	
	public function getUrl()
	{
		return $this->getAttribute('blt_url');
	}
	
	public function getBlog_name()
	{
		return $this->getAttribute('blt_blog_name');
	}
	public function getDate()
	{
		return $this->getAttribute('blt_date');
	}
	public function getExcerpt()
	{
		return $this->getAttribute('blt_excerpt');
	}
	public function updatePageView()
	{
		hitobito::countPageView($this->getAttribute('blt_pv_id'));
	}
	
}

class BlogTrackbackManager extends HNbValueObjectHandler
{
	/**
	 * プライマリキー
	 * TODO プライマリキーが2カラムなのでinsert get 利用不可
	 */
	protected $pkey = array('blt_article_id', 'blt_trackback_no');
	
	/**
	 * テーブル名
	 */
	 protected $table = 't_blog_trackback';
	 
	 /**
	  * VOクラス名
	  */
	 protected $class = 'BlogTrackback';
	 
	 public function get($id)
	 {
		$obj = new $this->class();
//		$pkeyWhereList = array();
//		foreach($this->pkey as $key){
//			$pkeyWhereList[] = $key.'='.$id[$key];
//		}
//		$pkeyWhere = implode(' AND ', $pkeyWhereList);
		$pkeyWhere = $this->getPkeyWhere($id);
		$sql = 'SELECT * FROM '.$this->table .' WHERE '.$pkeyWhere;
		$row = $this->db->GetRow($sql);
		if(empty($row)){
			$obj->setNew();
		}else{
			$obj->setAttributes($row);
		}
		return $obj;
	 }
	public function insert($obj)
	{
		$id = $obj->getId();
        if($id['blt_trackback_no'] > 0){
			$vars = $obj->getAttributes();
             $rs = $this->db->AutoExecute($this->table, $vars, "UPDATE", $this->getPkeyWhere($id));
         }else{
         	$obj->setAttribute('blt_pv_id', hitobito::getNewPageViewId());
         	$obj->setAttribute('blt_trackback_no', $this->getNewTrackbackNo($obj->getAttribute('blt_article_id')));
			$vars = $obj->getAttributes();
             $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
             $oid = $this->db->Insert_ID();
             $id = $this->db->GetRow("SELECT ".implode(',', $this->pkey)." FROM ".$this->table." WHERE oid=".$oid);
             $obj->setId($id);
         }
         return $rs;
	}
	
	private function getNewTrackbackNo($blt_article_id)
	{
		$no = $this->db->GetOne("SELECT max(blt_trackback_no) FROM ".$this->table." WHERE blt_article_id=".$blt_article_id);
		$no++;
		return $no;
	}
	 
	 public function getTrackbacksByStoryId($storyid)
	 {
	 	$criteria = new HNbCriteria();
	 	$criteria->setWhere('blt_article_id='.$storyid. ' AND blt_open_flag=1');
	 	$criteria->setOrder('ORDER BY blt_date ASC');
	 	return $this->getObjects($criteria);
	 }
	 
	 public function getTrackbacksNumByStoryId($storyid)
	 {
	 	$criteria = new HNbCriteria();
	 	$criteria->setWhere('blt_article_id='.$storyid. ' AND blt_open_flag=1');
		return $this->getCount($criteria);	 	
	 }
		 

}
?>