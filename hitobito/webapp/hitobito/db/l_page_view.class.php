<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_page_view.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_page_viewObject extends HNbValueObject
{
	protected $pkey = 'pvl_seq_no';
	protected $table = 'l_page_view';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['pvl_seq_no'] = 0 ;
				$this->attribute['pvl_pv_id'] = 0 ;
				$this->attribute['pvl_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['pvl_ip_addr'] = "" ;
			}
}

class l_page_viewObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'pvl_seq_no';
	protected $table = 'l_page_view';
	protected $class = 'l_page_viewObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>