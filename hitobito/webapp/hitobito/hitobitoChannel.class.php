<?php
/**
 * 
 * @package hitobito
 * @author Ryuji
 * @version $id$
 */

require_once MO_WEBAPP_DIR.'/lib/HNbValueObject.class.php';
require_once MO_WEBAPP_DIR.'/lib/HNbValueObjectHandler.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';

 
class hitobitoChannel extends HNbValueObject
{
	protected $pkey = 'chn_channel_id',
				$table = 'm_channel';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['chn_channel_id']= 0;
		$this->attribute['chn_status']= 0;	//ステータス（1：有効，9：無効）
		$this->attribute['chn_channel_name']= '';
		$this->attribute['chn_order']= 0;
		$this->attribute['chn_banner_url']= '';
		$this->attribute['chn_banner_link_url']= '';
	}
	
	public function load($channel_id)
	{
		// TODO エラー処理
		$rs = $this->db->GetRow("SELECT * FROM m_channel WHERE chn_channel_id=".$channel_id);
		$this->setAttributes($rs);
		return TRUE;
	}
	
	public function getId()
	{
		return 	$this->getAttribute('chn_channel_id');
	}

	public function getBanner()
	{
		$ret = array('image' => $this->getAttribute('chn_banner_url'), 'url' => $this->getAttribute('chn_banner_link_url'));
		return $ret;
	}
	
	public function getTitle()
	{
		return $this->getAttribute('chn_channel_name');
	}
}

class hitobitoChannelManager extends HNbValueObjectHandler
{
	protected 	$pkey = 'chn_channel_id', 
				$table = 'm_channel',
				$class = 'hitobitoChannel';
	public function getMainChannelList()
	{
		$result = $this->db->GetAll("SELECT * FROM m_channel WHERE chn_status=1 AND chn_channel_id != 0 ORDER BY chn_order"); // 0はトップページなので除外
		$ret = array();
		foreach($result as $vars){
			$obj= new hitobitoChannel();
			$obj->setAttributes($vars);
			$ret[] = $obj;
		}
		return $ret;
	}
	
}

class hitobitoSubChannel extends HNbValueObject
{
	protected 	$pkey = 'sch_subchannel_id', 
				$table = 'm_sub_channel';
	public function __construct()
	{
		parent::__construct();
		$this->attribute['sch_subchannel_id']= 0;
		$this->attribute['sch_channel_id']= 0;
		$this->attribute['sch_status']= 0;	//ステータス（1：有効，9：無効）
		$this->attribute['sch_subchannel_name']= '';
		$this->attribute['sch_order']= 0;
	}
	

	
	public function getId()
	{
		return 	$this->getAttribute('sch_subchannel_id');
	}

	
	public function getTitle()
	{
		return $this->getAttribute('sch_subchannel_name');
	}
	
	public function getNavipages()
	{
		$navipageManager = new hitobitoNavipageManager();
		$list = $navipageManager->getNavipageBySubChannelId($this->getId());
		return $list;
	}
}

class hitobitoSubChannelManager extends HNbValueObjectHandler
{
	protected 	$pkey = 'sch_subchannel_id', 
				$table = 'm_sub_channel',
				$class = 'hitobitoSubChannel';
	public function getSubChannelList($mainCategoryId)
	{
		$result = $this->db->GetAll("SELECT * FROM ".$this->table." WHERE sch_status=1 AND sch_channel_id=".$mainCategoryId ." ORDER BY sch_order");
		$ret = array();
		foreach($result as $vars){
			$obj= new $this->class();
			$obj->setAttributes($vars);
			$ret[] = $obj;
		}
		return $ret;
	}
	
}

?>