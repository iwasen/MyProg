<?php

require_once MO_WEBAPP_DIR . '/lib/HNbValueObjectHandler.class.php';

class HNbValueObject
{
    protected $attribute = array();
    protected $pkey = '';
    protected $isNew = FALSE;
    protected $db;
    protected $fields = array();
    protected $table;
    /**
     * ���󥹥ȥ饯����$attribute�����
     */
    public function __construct()
    {
    	$this->db = HNb::getAdodb();
    	$this->fields = $this->db->MetaColumnNames($this->table);
    }

	public function getId()
	{
		if(is_array($this->pkey)){
			return $this->getMultiPkey();
		}
		return $this->getAttribute($this->pkey);
	}
	/**
	 * ʣ���ե�����ɤ��祭���ΤȤ��˼祭����Ϣ��������֤�
	 */
	protected function getMultiPkey()
	{
		$ret = array();
		foreach($this->pkey as $key){
			$ret[$key] = $this->getAttribute($key);
		}
		return $ret;
	}
	
	public function setId($id)
	{
		if(is_array($this->pkey)){
			$this->setMultiPkey($id);	
		}else{
			$this->setAttribute($this->pkey, $id);
		}
	}
	/**
	 * ʣ���ե�����ɤ��祭���λ��˼祭����Ϣ������ǥ��åȤ���
	 */
	protected function setMultiPkey($id)
	{
		foreach($this->pkey as $key){
			$this->setAttribute($key, $id[$key]);
		}
	}

    /**
     * $attribute���ͤ򥻥åȤ���
     * ���������Ƥ��ʤ��ͤϥ��åȤ��ʤ���
     * $force == TRUE�λ��Τ߽��������Ƥ��ʤ��Ƥ⥻�åȤ���
     */
    public function setAttribute($key, $value, $force = FALSE)
    {
    	if(in_array($key, $this->fields) || $force){
			$this->attribute[$key] = $value;
		}
    }

    public function getAttribute($key)
    {
		return isset($this->attribute[$key]) ? $this->attribute[$key] : null;
	}
	
	public function setAttributes($array)
	{
		if(is_array($array)){
			foreach($array as $key => $value)
			{
				$this->setAttribute($key, $value);
			}
		}
	}
	
	public function getAttributes()
	{
		return $this->attribute;
	}
	
	public function isNew()
	{
		return $this->isNew;
	}
	
	public function setNew($boolian = TRUE)
	{
		$this->isNew = $boolian;
	}
}

class HNbValueObjectHandler
{
	/**
	 * adodb ���󥹥���
	 */
	protected $db;
	
	/**
	 * �ץ饤�ޥꥭ��
	 */
	protected $pkey = '';
	
	/**
	 * �ơ��֥�̾
	 */
	 protected $table = '';
	 
	 /**
	  * VO���饹̾
	  */
	  protected $class = '';
	  
	  protected $cacheObjects = array();

	public function __construct()
	{
		$this->db = HNb::getAdodb();
	}
	
	public function create()
	{
		$obj = new $this->class();
		return $obj;
	}
	
	public function cacheGet($id)
	{
	    if(!isset($this->cacheObjects[$id])){
	        $obj =  $this->get($id);
	        $this->cacheObjects[$id] = $obj;
	    }
        return $this->cacheObjects[$id];
	}
	
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
	protected function getPkeyWhere($id)
	{
		if(is_array($id)){
			// ʣ������
			$pkeyWhereList = array();
			foreach($this->pkey as $key){
				$pkeyWhereList[] = $key.'='.$id[$key];
			}
			$pkeyWhere = implode(' AND ', $pkeyWhereList);
			return $pkeyWhere;
		}
		// ʸ����
		if(!is_numeric($id)){
			return  $this->pkey . '='.$this->db->qstr($id);
		}
		// ����
		return $this->pkey . '='. $id;
	}
	
	
	
	public function & getOne($criteria)
	{
		if(is_object($criteria) && strtolower(get_class($criteria)) == 'hnbcriteria'){
			$where = $criteria->render();
		}else{
			$where = $criteria;
		}
		$obj = new $this->class();
		$sql = 'SELECT * FROM '.$this->table .' WHERE '.$where;
		$row = $this->db->GetRow($sql);
		if($row === FALSE){
			$obj->setNew();
		}else{
			$obj->setAttributes($row);
		}
		return $obj;
	}

	public function getCount($criteria)
	{
		if(is_object($criteria) && strtolower(get_class($criteria)) == 'hnbcriteria'){
			$where = $criteria->getWhere();
		}else{
			$where = $criteria;
		}
        $sql = "SELECT count(*) FROM ".$this->table;
        $sql .= !empty($where) ? " WHERE ".$where : "";
        $num = $this->db->GetOne($sql);
        return $num;		
	}
	
	public function getObjects($criteria, $limit = -1, $start = -1, $order = "")
	{
		if(is_object($criteria) && strtolower(get_class($criteria)) == 'hnbcriteria'){
			$where = $criteria->render();
			$order = '';
			$start = $criteria->getStart();
			$limit = $criteria->getLimit();
		}else{
			$where = $criteria;
		}
			
        $sql = "SELECT * FROM ".$this->table;
        $sql .= !empty($where) ? " WHERE ".$where : "";
        $sql .= !empty($order) ? " ".$order : "";
        $rs = $this->db->SelectLimit($sql, $limit, $start);
		$ret = array();
		if(!is_object($rs)){
			return $ret;
		}
		$array = $rs->GetRows();
		foreach($array as $vars){
			$obj = new $this->class();
			$obj->setAttributes($vars);
			$ret[] = $obj;
		}
        return $ret;
    }
    public function getObjectsBySql($sql, $limit = -1, $start= -1)
    {
        $rs = $this->db->SelectLimit($sql, $limit, $start);
		$ret = array();
		if(!is_object($rs)){
			return $ret;
		}
		$array = $rs->GetRows();
		foreach($array as $vars){
			$obj = new $this->class();
			$obj->setAttributes($vars);
			$ret[] = $obj;
		}
        return $ret;
    }	
	
	public function insert(& $obj)
	{
		if($this->isNew($obj)){
			return $this->insertObject($obj);
		}else{
			return $this->updateObject($obj);
		}
	}
	protected function isNew($obj)
	{
		return ($this->getCount($this->getPkeyWhere($obj->getId())) > 0) ? FALSE : TRUE;
	}
	protected function insertObject($obj)
	{
		$vars = $obj->getAttributes();
		if(is_array($this->pkey)){
             $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
             $oid = $this->db->Insert_ID();
             $id = $this->db->GetRow("SELECT ".implode(',', $this->pkey)." FROM ".$this->table." WHERE oid=".$oid);
			 $obj->setId($id);
		}else{
             unset($vars[$this->pkey]);
             $rs = $this->db->AutoExecute($this->table, $vars, "INSERT");
             $oid = $this->db->Insert_ID();
             $id = $this->db->GetOne("SELECT ".$this->pkey." FROM ".$this->table." WHERE oid=".$oid);
			 $obj->setAttribute($this->pkey, $id);
		}
		return $rs;
	}
	protected function updateObject($obj)
	{
		$vars = $obj->getAttributes();
		return $this->db->AutoExecute($this->table, $vars, 'UPDATE', $this->getPkeyWhere($obj->getId()));
	}
	
	public function delete($obj)
	{
		return $this->deletes($this->getPkeyWhere($obj->getId()));
	}
	
	public function deletes($criteria)
	{
		if(is_object($criteria) && strtolower(get_class($criteria)) == 'hnbcriteria'){
			$where = $criteria->getWhere();
		}else{
			$where = $criteria;
		}
		$sql = 'DELETE FROM '.$this->table.' WHERE '.$where;
		if($rs = $this->db->Execute($sql)){
			return TRUE;
		}else{
			return FALSE;
		}
	}		
}

/**
 * ��������ݻ����饹
 */
class HNbCriteria
{
	private	
				$order,
				$start = -1,
				$limit = -1,
				$whereList = array();
				
	/**
	 * where��Υ��å�
	 */
	public function setWhere($where)
	{
//		$this->where = $where;
		$this->addWhere($where);
	}
	
	public function addWhere($where)
	{
		$this->whereList[] = $where;
	}
	
	/**
	 * ����������Υ��å�
	 */
	public function setOrder($order)
	{
		$this->order = $order;
	}
	
	public function setStart($start)
	{
		$this->start = $start;
	}
	
	public function getStart()
	{
		return $this->start;
	}
	
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
  
  	public function getLimit()
  	{
  		return $this->limit;
  	}
	
	public function render()
	{
		return $this->getWhere() . ' '. $this->order;
	}
	
	public function getWhere()
	{
		$where = implode(' AND ', $this->whereList);
		return ' '.$where;
	}
	
		
}

?>