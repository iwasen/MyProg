<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_sub_channel.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_sub_channelObject extends HNbValueObject
{
	protected $pkey = 'sch_subchannel_id';
	protected $table = 'm_sub_channel';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sch_subchannel_id'] = 0 ;
				$this->attribute['sch_channel_id'] = 0 ;
				$this->attribute['sch_status'] = 0 ;
				$this->attribute['sch_subchannel_name'] = "" ;
				$this->attribute['sch_order'] = 0 ;
			}
}

class m_sub_channelObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'sch_subchannel_id';
	protected $table = 'm_sub_channel';
	protected $class = 'm_sub_channelObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>