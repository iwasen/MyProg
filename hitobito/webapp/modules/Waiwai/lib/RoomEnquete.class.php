<?php
/**
  *
  * @auther Ryuji
  * @version $Id: RoomEnquete.class.php,v 1.2 2006/06/07 07:32:07 aizawa Exp $
  */
require_once MO_WEBAPP_DIR.'/hitobito/db/t_room_enquete.class.php';
class RoomEnquete extends t_room_enqueteObject
{
    public function getRoomTitle()
    {
        $db = HNb::getAdodb();
        $sql = "SELECT rom_name FROM t_room WHERE rom_room_id=".$this->getAttribute('ren_room_id');
        return $db->GetOne($sql);
    }
    public function getNaviPageId()
    {
        $db = HNb::getAdodb();
        $sql = "SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_room_id=".$this->getAttribute('ren_room_id');
        return $db->GetOne($sql);
    }
}

class RoomEnqueteManager extends t_room_enqueteObjectHandler
{
   	protected $class = 'RoomEnquete';


    public function getNoAnswerEnqueteNum($member_id)
    {
        
    }
    /**
    * 未回答アンケートリストを返す
    */
    public function getNoAnswerEnqueteList($member_id)
    {
        $room_id_list = $this->getRegistRoomIdList($member_id);
        if(count($room_id_list) > 0){
            $room_ids = implode(',', $room_id_list);
            $sql = "SELECT t_room_enquete.* FROM t_room_enquete"
                . " LEFT JOIN l_room_enq_answer ON (ren_enquete_id=rel_enquete_id AND rel_member_id=".$member_id.")"
                . " WHERE ren_room_id IN (".$room_ids.") AND ren_status=2 AND ren_start_date < now() AND ren_end_date > now()"
                ." AND rel_member_id IS NULL"
                ." ORDER BY ren_date DESC";
                
            $result = $this->db->GetAll($sql);
            $enquete_list = array();
            foreach($result as $row){
                $obj = $this->create();
                $obj->setAttributes($row);
                $enquete_list[] = $obj;
            }
        }else{
            $enquete_list = array();
        }
        return $enquete_list;
    }
    
    /**参加会議室IDリストを得る
    */
    private function getRegistRoomIdList($member_id)
    {
    	$sql = "SELECT rmm_room_id FROM t_room_member"
			. " JOIN t_room ON rom_room_id=rmm_room_id AND rom_status IN (1,2)"
			. " JOIN t_navi_page ON nvp_room_id=rmm_room_id AND nvp_open_flag=1"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE rmm_member_id=$member_id AND rmm_status IN (2,3)";
		$result =  $this->db->GetAll($sql);
		$list = array();
		foreach($result as $row){
		    $list[] = $row['rmm_room_id'];
		}
		return $list;
    }
}
?>