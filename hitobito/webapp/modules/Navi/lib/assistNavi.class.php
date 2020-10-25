<?php
require_once MO_WEBAPP_DIR.'/hitobito/db/t_navi.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/db/t_assist_navi.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoImage.class.php';
class assistNavi extends HNbValueObject
{
	private
		$navi,
		$assist;
	public function setNavi($obj)
	{
		$this->navi = $obj;
	}
	public function setAssist($obj)
	{
		$this->assist = $obj;
	}
	public function getNavi()
	{
		return $this->navi;
	}
	public function getAssist()
	{
		return $this->assist;
	}
	
	public function __construct()
	{
		
	}
	public function setAttribute($key, $var)
	{
		$_key = substr($key, 0,3);
		switch($_key){
			case 'anv':
				$this->assist->setAttribute($key, $var);
				break;
			case 'nav':
				$this->navi->setAttribute($key, $var);
		}
	}
	public function getAttribute($key)
	{
		$_key = substr($key, 0,3);
		switch($_key){
			case 'anv':
				return $this->assist->getAttribute($key);
			case 'nav':
				return $this->navi->getAttribute($key);
		}
	}
	
	public function getName()
	{
		return $this->getAttribute('nav_name1').$this->getAttribute('nav_name2');
	}
	
	public function getEmail()
	{
		$db = HNb::getAdodb();
		$email = $db->GetOne('SELECT mbr_mail_addr FROM t_member' .
				' JOIN t_navi ON nav_member_id=mbr_member_id' .
				' JOIN t_assist_navi ON anv_navi_id=nav_navi_id' .
				' WHERE anv_navi_id='.$this->getAttribute('anv_navi_id') . 
				' AND anv_navi_page_id='.$this->getAttribute('anv_navi_page_id'));
		return $email;
	}
}

class assistNaviManager extends HNbValueObjectHandler
{
	protected $class = 'assistNavi';
	protected $naviHandler,
		$assistHandler;
	protected $table = 't_assist_navi JOIN t_navi ON anv_navi_id=nav_navi_id';
	public function __construct()
	{
		$this->db = HNb::getAdodb();
		$this->naviHandler = new t_naviObjectHandler();
		$this->assistHandler = new t_assist_naviObjectHandler();
	}
	public function create()
	{
		$obj = new $this->class();
		$obj->setNavi($this->naviHandler->create());
		$obj->setAssist($this->assistHandler->create());
		return $obj;
	}
	
	public function get($id)
	{
		$obj = $this->create();
		$assist = $this->assistHandler->get($id);
		$obj->setAssist($assist);
		if($assist->isNew()){
			$obj->setNavi($this->naviHandler->create());
			$obj->setNew();
		}else{
			$obj->setNavi($this->naviHandler->get($obj->getAttribute('anv_navi_id')));
		}
		return $obj;
	}
	public function insert($obj)
	{
		$navi = $obj->getNavi();
		// ÅÐÏ¿ºÑ¤ß¤Î¥Ê¥Ó¤«¡©
		$criteria = new HNbCriteria();
		$criteria->setWhere('nav_member_id='.$navi->getAttribute('nav_member_id'));
		if($this->naviHandler->getCount($criteria) > 0){
			// ÅÐÏ¿ºÑ¤ß¥Ê¥Ó
			$navi = $this->naviHandler->getOne($criteria);
		}else{
			// ¥Ê¥ÓÌ¤ÅÐÏ¿
			$this->naviHandler->insert($navi);
		}
		$assist = $obj->getAssist();
		$assist->setAttribute('anv_navi_id', $navi->getId());
		if($assist->isNew()){
			$lastOrder = $this->db->GetOne('SELECT anv_order FROM t_assist_navi WHERE anv_navi_page_id='.$assist->getAttribute('anv_navi_page_id'));
			$assist->setAttribute('anv_order', $lastOrder+1);
		}
		$this->assistHandler->insert($assist);
		return TRUE;
	}
	
	public function delete($obj)
	{
		$imageManager = new hitobitoImageManager();
		$image = $imageManager->get($obj->getAttribute('anv_image_id'));
		$imageManager->delete($image);
		$this->assistHandler->delete($obj->getAssist());
		return TRUE;
	}
	
	public function getObjectByNaviPageId($navipage_id, $extraWhere = '')
	{
		$sql = 'SELECT * FROM t_assist_navi JOIN t_navi ON anv_navi_id=nav_navi_id WHERE anv_navi_page_id='.$navipage_id ;
		if(!empty($extraWhere)){
			$sql .= ' AND ' . $extraWhere;
		}
		$sql.=' ORDER BY anv_order';
		
		
		$result = $this->db->GetAll($sql);
		$ret = array();
		foreach($result as $row){
			$obj = $this->create();
			$obj->setAttributes($row);
			$ret[] = $obj;
		}
		return $ret;
	}
	public function orderUp($obj)
	{
		$this->exchangeOrder($obj, 'up');
		return;
	}
	
	public function orderDown($obj)
	{
		$this->exchangeOrder($obj, 'down');
		return ;
	}

	/**
	 * orderUp, orderDown¤«¤é¸Æ¤Ó½Ð¤¹
	 * 
	 */
	private function exchangeOrder($sourceObject, $targetOrdedr)
	{
		$navi_page_id = $sourceObject->getAttribute('anv_navi_page_id');
		$sourceOrder = $sourceObject->getAttribute('anv_order');
		switch($targetOrdedr){
			case 'up':
				$sql = sprintf('SELECT anv_navi_id, anv_navi_page_id,anv_order FROM t_assist_navi WHERE anv_navi_page_id=%d AND anv_order < %d ORDER BY anv_order DESC',
						$navi_page_id,
						$sourceOrder);
				break;
			case 'down':
				$sql = sprintf('SELECT anv_navi_id, anv_navi_page_id, anv_order FROM t_assist_navi WHERE anv_navi_page_id=%d AND anv_order > %d ORDER BY anv_order ASC',
						$navi_page_id,
						$sourceOrder);
				break;
		}
		$target = $this->db->GetRow($sql);
		if(!empty($target)){
			$this->db->Execute('UPDATE t_assist_navi SET anv_order='.$target['anv_order']. ' WHERE anv_navi_id='.$sourceObject->getAttribute('anv_navi_id').' AND anv_navi_page_id='.$sourceObject->getAttribute('anv_navi_page_id'));
			$this->db->Execute('UPDATE t_assist_navi SET anv_order='.$sourceOrder. ' WHERE anv_navi_id='.$target['anv_navi_id'].' AND anv_navi_page_id='.$target['anv_navi_page_id']);
		}
	}	
}
?>