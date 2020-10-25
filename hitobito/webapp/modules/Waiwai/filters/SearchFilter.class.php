<?php
/**
 *
 *
 * 
 * 
 * @package Waiwai
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: SearchFilter.class.php,v 1.5 2006/06/07 07:32:07 aizawa Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
abstract class RoomSearchFilter extends HNbActionFilter
{
	public function fetch($context)
	{
		$this->context = $context;
		$request = $context->getRequest();
		$this->addParameter('search_word', $request->getParameter('search_word'));
		// channel
		if($request->hasParameter('category_id')){
			$this->addParameter('category_id', intval($request->getParameter('category_id')));
		}
		// navipage		
		if($request->hasParameter('navipage_id')){
			$this->addParameter('navipage_id', intval($request->getParameter('navipage_id')));
		}
		
	}
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('search_word')){
			$searchWordList = preg_split('/( |กก)/', $this->getParameter('search_word'));
			foreach($searchWordList as $word){
				$word = addslashes($word);
				$criteria->addWhere("(rmk_subject LIKE '%".$word."%' OR rmk_content LIKE '%".$word."%')");
			}
		}
		if($this->hasParameter('category_id')){
			$criteria->addWhere('nvp_channel_id='.$this->getParameter('category_id'));
		}
		if($this->hasParameter('navipage_id')){
			$criteria->addWhere('nvp_navi_page_id='.$this->getParameter('navipage_id'));
		}
		return $criteria;
	}
	
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setOrder("ORDER BY rmk_date DESC");
		return $criteria;
	}

}
class RoomSearchGuestUserFilter extends RoomSearchFilter
{
	protected function getDefaultCriteria()
	{
		$criteria = parent::getDefaultCriteria();
		$criteria->addWhere('rom_status=1');
		return $criteria;
	}
}
class RoomSearchRegistUserFilter extends RoomSearchFilter
{
	protected function getDefaultCriteria()
	{
		$criteria = parent::getDefaultCriteria();
		$userId  = $this->context->getUser()->getAttribute('mbr_member_id');
		
		$db = HNb::getAdodb();
		$sql = 'SELECT rmm_room_id FROM t_room_member WHERE rmm_member_id='.$userId.' AND rmm_status=3';
		$rs = $db->Execute($sql);
		$RegistRoomList = array();
		while($row = $rs->FetchRow()){
			$RegistRoomList[] = $row['rmm_room_id']; 
		}
		if(count($RegistRoomList)>0){
			$criteria->addWhere('(rom_status=1 OR (rom_status=2 AND rom_room_id IN ('.implode(',', $RegistRoomList).')))');
		}else{
			$criteria->addWhere('(rom_status=1)');
		}
		return $criteria;
	}
}
?>