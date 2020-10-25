<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_recruit_click.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_recruit_clickObject extends HNbValueObject
{
	protected $pkey = 'rcl_seq_no';
	protected $table = 'l_recruit_click';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rcl_seq_no'] = 0 ;
				$this->attribute['rcl_recruit_id'] = 0 ;
				$this->attribute['rcl_ip_adr'] = "" ;
				$this->attribute['rcl_date'] = date("Y-m-d H:i:s") ;
			}
}

class l_recruit_clickObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rcl_seq_no';
	protected $table = 'l_recruit_click';
	protected $class = 'l_recruit_clickObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>