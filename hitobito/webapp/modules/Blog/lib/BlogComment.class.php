<?php
require_once MO_WEBAPP_DIR .'/lib/HNbValueObject.class.php';
require_once MO_WEBAPP_DIR .'/lib/HNbValueObjectHandler.class.php';

class BlogComment extends HNbValueObject
{
	protected $pkey = array('blo_article_id', 'blo_comment_no');
	 protected $table = 't_blog_comment';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['blo_article_id'] = 0;
		$this->attribute['blo_comment_no'] = 0;
		$this->attribute['blo_date'] = date('Y-m-d H:i:s');
		$this->attribute['blo_user_name'] = '';
		$this->attribute['blo_comment'] = '';
		$this->attribute['blo_ng_word'] = '';
		$this->attribute['blo_patrol_check'] = 1;
		$this->attribute['blo_patrol_date'] = null;
		$this->attribute['blo_patrol_id'] = null;
		$this->attribute['blo_admin_check'] = 1;
		$this->attribute['blo_admin_date'] = null;
		$this->attribute['blo_admin_id'] = null;
		$this->attribute['blo_open_flag'] = 1;
		$this->attribute['blo_pv_id'] = 0;

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
	public function updatePageView()
	{
		hitobito::countPageView($this->getAttribute('blo_pv_id'));
	}
	public function getPosterName()
	{
		return $this->getAttribute('blo_user_name');	
	}
	public function getShortText()
	{
		return mb_substr(strip_tags($this->getAttribute('blo_comment')),0, 20);
	}
	public function getDate()
	{
		return $this->getAttribute('blo_date');
	}
	public function getStatus()
	{
		return $this->getAttribute('blo_open_flag');
	}
	public function getStoryId()
	{
		return $this->getAttribute('blo_article_id');
	}
}

class BlogCommentManager extends HNbValueObjectHandler
{
	/**
	 * プライマリキー
	 * TODO プライマリキーが2カラムなのでinsert get 利用不可
	 */
	protected $pkey = array('blo_article_id', 'blo_comment_no');
	
	/**
	 * テーブル名
	 */
	 protected $table = 't_blog_comment';
	 
	 /**
	  * VOクラス名
	  */
	 protected $class = 'BlogComment';
	 
	 public function get($id)
	 {
		$obj = new $this->class();
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
        if($id['blo_comment_no'] > 0){
			$vars = $obj->getAttributes();
             $rs = $this->db->AutoExecute($this->table, $vars, "UPDATE", $this->getPkeyWhere($id));
         }else{
         	$obj->setAttribute('blo_pv_id', hitobito::getNewPageViewId());
         	$obj->setAttribute('blo_comment_no', $this->getNewCommentNo($obj->getAttribute('blo_article_id')));
			$vars = $obj->getAttributes();
             $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
             $oid = $this->db->Insert_ID();
             $id = $this->db->GetRow("SELECT ".implode(',', $this->pkey)." FROM ".$this->table." WHERE oid=".$oid);
             $obj->setId($id);
         }
         return $rs;
	}
	
	private function getNewCommentNo($blo_article_id)
	{
		$no = $this->db->GetOne("SELECT max(blo_comment_no) FROM ".$this->table." WHERE blo_article_id=".$blo_article_id);
		$no++;
		return $no;
	}
	 
	 public function getCommentsByStoryId($storyid)
	 {
	 	$criteria = new HNbCriteria();
	 	$criteria->setWhere('blo_article_id='.$storyid. " AND blo_open_flag=1");
	 	$criteria->setOrder('ORDER BY blo_date ASC');
	 	return $this->getObjects($criteria);
	 }
	 
	 public function getCommentsNumByStoryId($storyid)
	 {
	 	$criteria = new HNbCriteria();
	 	$criteria->setWhere('blo_article_id='.$storyid. " AND blo_open_flag=1");
		return $this->getCount($criteria);	 	
	 }
	 


}
?>