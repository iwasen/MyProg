<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_page_view.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_page_viewObject extends HNbValueObject
{
	protected $pkey = 'pvc_pv_id';
	protected $table = 'c_page_view';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['pvc_pv_id'] = 0 ;
				$this->attribute['pvc_pv_count'] = 0 ;
				$this->attribute['pvc_log_flag'] = 0 ;
			}
}

class c_page_viewObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'pvc_pv_id';
	protected $table = 'c_page_view';
	protected $class = 'c_page_viewObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>