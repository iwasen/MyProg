<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_banner_click.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_banner_clickObject extends HNbValueObject
{
	protected $pkey = 'bnc_banner_id';
	protected $table = 'c_banner_click';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bnc_banner_id'] = 0 ;
				$this->attribute['bnc_click_count'] = 0 ;
			}
}

class c_banner_clickObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bnc_banner_id';
	protected $table = 'c_banner_click';
	protected $class = 'c_banner_clickObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>