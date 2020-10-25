<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_click_count.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_click_countObject extends HNbValueObject
{
	protected $pkey = 'lcc_seq_no';
	protected $table = 'l_click_count';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['lcc_seq_no'] = 0 ;
				$this->attribute['lcc_click_id'] = 0 ;
				$this->attribute['lcc_member_id'] = 0 ;
				$this->attribute['lcc_ip_adr'] = "" ;
				$this->attribute['lcc_date'] = date("Y-m-d H:i:s") ;
			}
}

class l_click_countObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'lcc_seq_no';
	protected $table = 'l_click_count';
	protected $class = 'l_click_countObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>