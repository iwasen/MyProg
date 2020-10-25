<?php

/**
 * 会議室検索クラス
 */

require_once MO_WEBAPP_DIR.'/hitobito/db/l_room_remark.class.php';

class RoomRemark extends l_room_remarkObject
{
	private $navipage;
	public function getNaviPageInfo()
	{
		if(!$this->navipage){
			$criteria = new HNbCriteria();
			$criteria->setWhere('nvp_room_id='.$this->getAttribute('rmk_room_id'));
			$navipageHandler = new hitobitoNavipageManager();
			$this->navipage = $navipageHandler->getOne($criteria);
		}
		return $this->navipage;
	}
}

class RoomSearchManager {
	private $class= 'RoomRemark';
	private $db;
	
	public function __construct()
	{
		$this->db =  HNb::getAdodb();
	}
	
	public function getCount($criteria)
	{
    	$sql = $this->getOpenStoriesSql('count(*)');
    	$sql .= " AND ". $criteria->getWhere();
    	return $this->db->GetOne($sql);
    }
	public function getObjects($criteria)
    {
    	$sql = $this->getOpenStoriesSql('*');
    	$sql .= " AND ".$criteria->render();
        $start = $criteria->getStart();
		$limit = $criteria->getLimit();
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
	private function getOpenStoriesSql($select)
	{
    	$sql = "SELECT ".$select." FROM l_room_remark " .
    			" JOIN t_room ON rmk_room_id=rom_room_id".
    			" JOIN t_navi_page ON rmk_room_id=nvp_room_id" .
    			" WHERE nvp_status=1 AND nvp_open_flag=1" .
    			" AND rmk_status=1 AND rmk_open_flag=1" .
    			" AND rmk_disp_type=1";
    	return $sql;
	}
}
?>