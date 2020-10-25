<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_channel.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_channelObject extends HNbValueObject
{
	protected $pkey = 'chn_channel_id';
	protected $table = 'm_channel';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['chn_channel_id'] = 0 ;
				$this->attribute['chn_status'] = 0 ;
				$this->attribute['chn_channel_name'] = "" ;
				$this->attribute['chn_order'] = 0 ;
				$this->attribute['chn_banner_url'] = "" ;
				$this->attribute['chn_banner_link_url'] = "" ;
			}
}

class m_channelObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'chn_channel_id';
	protected $table = 'm_channel';
	protected $class = 'm_channelObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>