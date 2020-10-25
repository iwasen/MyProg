<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_kuchikomi_channel.class.php,v 1.3 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_kuchikomi_channelObject extends HNbValueObject
{
	protected $pkey = array('kch_channel_id','kch_kuchikomi_id');
	protected $table = 't_kuchikomi_channel';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['kch_channel_id'] = 0 ;
				$this->attribute['kch_kuchikomi_id'] = 0 ;
			}
}

class t_kuchikomi_channelObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('kch_channel_id','kch_kuchikomi_id');
	protected $table = 't_kuchikomi_channel';
	protected $class = 't_kuchikomi_channelObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>