<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_channel_kcom.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_channel_kcomObject extends HNbValueObject
{
	protected $pkey = array('chk_channel_id','chk_l1','chk_l2','chk_l3','chk_l4','chk_l5');
	protected $table = 'm_channel_kcom';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['chk_channel_id'] = 0 ;
				$this->attribute['chk_l1'] = 0 ;
				$this->attribute['chk_l2'] = 0 ;
				$this->attribute['chk_l3'] = 0 ;
				$this->attribute['chk_l4'] = 0 ;
				$this->attribute['chk_l5'] = 0 ;
			}
}

class m_channel_kcomObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('chk_channel_id','chk_l1','chk_l2','chk_l3','chk_l4','chk_l5');
	protected $table = 'm_channel_kcom';
	protected $class = 'm_channel_kcomObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>