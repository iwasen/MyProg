<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_undeliver.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_undeliverObject extends HNbValueObject
{
	protected $pkey = 'und_member_id';
	protected $table = 'c_undeliver';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['und_member_id'] = 0 ;
				$this->attribute['und_last_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['und_count'] = 0 ;
			}
}

class c_undeliverObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'und_member_id';
	protected $table = 'c_undeliver';
	protected $class = 'c_undeliverObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>