<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_banner_click.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_banner_clickObject extends HNbValueObject
{
	protected $pkey = 'bnl_seq_no';
	protected $table = 'l_banner_click';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bnl_seq_no'] = 0 ;
				$this->attribute['bnl_banner_id'] = 0 ;
				$this->attribute['bnl_ip_adr'] = "" ;
				$this->attribute['bnl_date'] = date("Y-m-d H:i:s") ;
			}
}

class l_banner_clickObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bnl_seq_no';
	protected $table = 'l_banner_click';
	protected $class = 'l_banner_clickObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>