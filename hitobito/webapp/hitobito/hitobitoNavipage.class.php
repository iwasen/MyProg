<?php
/**
 * 
 * @package hitobito
 * @author Ryuji
 * @version $id$
 */

//require_once MO_WEBAPP_DIR.'/lib/HNb';
require_once MO_WEBAPP_DIR.'/modules/Navi/lib/assistNavi.class.php';
class hitobitoNavipage extends HNbValueObject
{
	protected	$pkey = 'nvp_navi_page_id',
				$table = 't_navi_page';
	private
		$naviEmail = '';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['nvp_navi_page_id'] = 0;
		$this->attribute['nvp_navi_id'] = 0;
		$this->attribute['nvp_status'] = 0; //ステータス（0:承認待ち、1:承認、2:修正、9:却下）
		$this->attribute['nvp_regist_date'] = time();
		$this->attribute['nvp_update_date'] = time();
		$this->attribute['nvp_open_date'] = 0;
		$this->attribute['nvp_resign_date'] = 0;
		$this->attribute['nvp_theme'] = '';
		$this->attribute['nvp_channel_id'] = 0;
		$this->attribute['nvp_subchannel_id'] = 0;
		$this->attribute['nvp_type'] = 0;
		$this->attribute['nvp_outline'] = '';
		$this->attribute['nvp_blog_id'] = 0;
		$this->attribute['nvp_room_id'] = 0;
		$this->attribute['nvp_melmaga_id'] = 0;
		$this->attribute['nvp_title'] = '';
		$this->attribute['nvp_copy'] = '';
		$this->attribute['nvp_self_introduction'] = '';
		$this->attribute['nvp_image_id'] = 0;
		
	}
	
	/**
	 * ナビページ情報をロードする
	 */
	public function load($navipage_id)
	{
		// TODO エラー処理
		$rs = $this->db->GetRow("SELECT * FROM t_navi_page WHERE nvp_navi_page_id=".$navipage_id);
		$this->setAttributes($rs);
		return TRUE;
	}
	
	public function getId()
	{
		return 	$this->getAttribute('nvp_navi_page_id');
	}
	
	public function getBlog_id()
	{
		return $this->getAttribute('nvp_blog_id');
	}
	
	public function getWaiwaiId()
	{
		return $this->getAttribute('nvp_room_id');
	}
	
	public function getChannelId()
	{
		return $this->getAttribute('nvp_channel_id');
	}
	public function getSubChannelId()
	{
		return $this->getAttribute('nvp_subchannel_id');
	}
	
	public function getTitle()
	{
		return $this->getAttribute('nvp_title');
	}
	
	public function getDescription()
	{
		return $this->getAttribute('nvp_outline');
	}
	
	public function getNaviName()
	{
//		static $name; // static の使い方まずい
		if(!isset($name)){
			$row = $this->db->GetRow('SELECT nav_name1 ,nav_name2 FROM t_navi WHERE nav_navi_id='.$this->getAttribute('nvp_navi_id'));
			if(empty($row)){
				$name = HNb::tr('名前未設定のナビ');
			}else{
				$name = $row['nav_name1'].$row['nav_name2'];
			}
		}
		return $name;
	}
	public function getNaviEmail()
	{
		if(empty($this->naviEmail)){
			$this->naviEmail = $this->db->GetOne("SELECT mbr_mail_addr FROM t_member" .
					" JOIN t_navi ON mbr_member_id=nav_member_id" .
					" WHERE nav_navi_id=".$this->getAttribute('nvp_navi_id'));
		}
		return $this->naviEmail; 
	}
	
	public function getPhotoId()
	{
		return $this->getAttribute('nvp_image_id');
	}
	public function getMelMagaStatus()
	{
		$status = $this->db->GetOne('SELECT mmg_status FROM t_melmaga WHERE mmg_melmaga_id='.$this->getAttribute('nvp_melmaga_id'));
		return $status;
	}
	public function getRoomStatus()
	{
		$status = $this->db->GetOne('SELECT rom_status FROM t_room WHERE rom_room_id='.$this->getAttribute('nvp_room_id'));
		return $status;
	}
	
	/**
	 * Blogの最新記事取得
	 */
	public function getLastStory()
	{
		$blogManager = new BlogOneBlogStoryManager($this->getBlog_id());
		return $blogManager->getLastStory();
	}
	
	public function getAssistNavi($where='')
	{
		$manager = new assistNaviManager();
		return $manager->getObjectByNaviPageId($this->getId(), $where);
	}
	/**
	 * 指定されたナビIDのナビが、このナビページのアシストナビか？
	 * @return boolian TRUE:アシストナビ FALSE:アシストナビではない
	 */
	public function hasAssistNaviByNaviId($naviId)
	{
		$manager = new assistNaviManager();
		$criteria = new HNbCriteria();
		$criteria->setWhere('anv_navi_id='.$naviId.' AND anv_navi_page_id='.$this->getId(). '');
		$manager->getCount($criteria);
		if($manager->getCount($criteria)){
			return TRUE;
		}else{
			return FALSE;
		}		
	}
	
	/**
	 * バナーが設定されているか？
	 * @return boolian TRUE: 設定されている FALSE:未設定
	 */
	public function hasBanner()
	{
		if($this->getAttribute('nvp_banner_id') > 0){
			$criteria = new HNbCriteria();
			$criteria->setWhere('bnr_image_url IS NOT NULL AND bnr_banner_id='.$this->getAttribute('nvp_banner_id'));
			$bannerHandler = HNb::getDBHandler('t_banner');
			if($bannerHandler->getCount($criteria)){
				return TRUE;
			}			
		}
		return FALSE;
	}
	/**
	 * バナーの情報を連想配列で返す
	 * @return array('image'=> 'http://imageurl', 'url' => 'banner.php?id=banner_id');
	 */
	public function getBanner()
	{
		$bannerHandler = HNb::getDBHandler('t_banner');
		$banner = $bannerHandler->get($this->getAttribute('nvp_banner_id'));
		return array('image' => $banner->getAttribute('bnr_image_url'), 'url' => 'banner.php?id='.$banner->getId());
	}	
}

class hitobitoNavipageManager extends HNbValueObjectHandler
{
	protected	$pkey = 'nvp_navi_page_id',
				$table = 't_navi_page',
				$class = 'hitobitoNavipage';
	public function getNavipageBySubChannelId($subChannelId)
	{
		$criteria = new HNbCriteria();
		$criteria->setWhere('nvp_subchannel_id='.$subChannelId);
		$criteria->addWhere('nvp_open_flag=1');// 公開中のナビページだけをリストアップ
		$criteria->addWhere('nvp_open_date < now()');
		return $this->getObjects($criteria);
	}
	
	/**
	 * ナビランキング
	 */
	public function getNaviRanking()
	{
		$sql = 'SELECT nvr_navi_page_id, nvr_rank_no FROM t_navi_ranking WHERE nvr_rank_no >= 1 AND nvr_rank_no <=10';
		$result = $this->db->GetAll($sql);
		$result = $this->db->SelectLimit($sql, 10);
		
		$ranking = array();
		$rank = 1;
		foreach($result as $row){
			$ranking[$rank] = $this->get($row['nvr_navi_page_id']);
			$rank++;
		}
		ksort($ranking);
		return $ranking;
	}

	/**
	 * ナビランキング（トップページ）
	 */
	public function getNaviRankingTopScreen()
	{
		$sql = 'SELECT nvr_navi_page_id FROM t_navi_ranking ' .
				' JOIN t_navi_page ON nvp_navi_page_id=nvr_navi_page_id' .
				' WHERE nvp_open_flag=1' .
				' ORDER BY nvr_rank_no ASC' ;
		$result = $this->db->SelectLimit($sql, 5);
		
		$ranking = array();
		$rank = 1;
		foreach($result as $row){
			$ranking[$rank] = $this->get($row['nvr_navi_page_id']);
			$rank++;
		}
		ksort($ranking);
		return $ranking;
	}

	
	/**
	 * 本日の一押しナビ
	 */
	public function getTodaysPushNavipage()
	{
		$ret = array();
		$channelId = hitobito::getChannelId();
		$sql = "SELECT * FROM ".$this->table
			. " JOIN t_ichioshi_navi ON nvp_navi_page_id=inv_current_navi_page_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE inv_position IN (1,2,3) ORDER BY inv_position ASC";
		$result = $this->db->GetAll($sql);
		foreach($result as $row){
			$blogManager = new BlogOneBlogStoryManager($row['nvp_blog_id']);
			$lastStory = $blogManager->getLastStory();
			
			$ret[$row['inv_position']] = array(
				'photo_id' => $row['nvp_image_id'],
				'title' => $row['nvp_title'],
				'navipage_id' => $row['nvp_navi_page_id'],
				'navi_name' => $row['nav_name1'].$row['nav_name2'],
				'navi_outline' => $row['nvp_outline'],
				'last_blog_id' => (is_object($lastStory)) ? $lastStory->getId() : '',
				'last_title' => (is_object($lastStory)) ? $lastStory->getTitle() : '',
				'last_body' => (is_object($lastStory)) ? $lastStory->getBody() : '',
				'last_update' => (is_object($lastStory)) ? $lastStory->getAttribute('bla_date') : ''
			);
		}
		return $ret;
	}
	





	/**
	 * ナビタイトル一覧
	 */
	public function getNaviTitle($channel_id)
	{
		$sql = 'SELECT nvr_navi_page_id, nvp_title, chn_channel_id, chn_channel_name'
				. ' FROM t_navi_ranking'
				. ' JOIN t_navi_page ON nvp_navi_page_id=nvr_navi_page_id'
				. ' JOIN m_channel ON nvp_channel_id = chn_channel_id'
				. ' WHERE nvp_status=1 AND nvp_open_flag=1' . ($channel_id ? ' AND nvp_channel_id='.$channel_id.' ' : '')
				. ' ORDER BY chn_channel_id, nvr_rank_no';

		$result = $this->db->GetAll($sql);
		foreach($result as $row){
			$ret[] = array(
				'id' => $row['nvr_navi_page_id'],
				'title' => $row['nvp_title'],
				'channel_id' => $row['chn_channel_id'],
				'channel_name' => $row['chn_channel_name']
			);
		}
		return $ret;
	}






	public function getNaviPagesByMemberId($memberId)
	{
		$navi_id = $this->getNaviIdByMemberId($memberId);
		$list = $this->getObjects('nvp_navi_id='.$navi_id.' AND nvp_status=1 AND nvp_open_flag !=9');
		return $list;
	}
	private function getNaviIdByMemberId($memberId)
	{
		$navi_id = $this->db->GetOne('SELECT nav_navi_id FROM t_navi WHERE nav_member_id='.$memberId);
		return $navi_id; 
	}
	
	public function getNaviPagesByAssistNaviMemberId($memberId)
	{
		$sql = "SELECT t_navi_page.* FROM t_navi_page " .
				" JOIN t_assist_navi ON nvp_navi_page_id=anv_navi_page_id" .
				" JOIN t_navi ON anv_navi_id=nav_navi_id" . 
				" JOIN t_member ON mbr_member_id=nav_member_id" .
				" WHERE mbr_member_id=" .$memberId . 
//				" AND nav_status=1" .
//				" AND anv_status=1"
				'';
		$limit = -1;
		$start = 0;
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
	
	/**
	 * 関連テーマナビページを返す
	 * $navipage と同一サブチャネルに属する$navipage以外のナビページをランダムで最大3件まで返す
	 * 
	 * @param hitobitoNavipage 
	 */
	public function getRelationNavipages($navipage, $num=3)
	{
		
		$criteria = $this->getOpenNavipageCriteria();

		$id = $navipage->getId();
		$subChannelId = $navipage->getSubChannelId();
		$criteria->addWhere('nvp_navi_page_id != '.$id);
		$criteria->addWhere('nvp_subchannel_id = '.$subChannelId);
		
		$criteria->setOrder('ORDER BY Random()');
		$criteria->setLimit($num);
		return $this->getObjects($criteria);
		
	}
	
	protected function getOpenNavipageCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setWhere('nvp_status = 1');
		$criteria->addWhere('nvp_open_date < now()');
		$criteria->addWhere('nvp_open_flag = 1');
		return $criteria;
	}	
}
?>