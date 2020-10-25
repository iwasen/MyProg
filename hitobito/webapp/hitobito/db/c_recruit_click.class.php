<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_recruit_click.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_recruit_clickObject extends HNbValueObject
{
	protected $pkey = 'rcc_recruit_id';
	protected $table = 'c_recruit_click';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rcc_recruit_id'] = 0 ;
				$this->attribute['rcc_click_count'] = 0 ;
				$this->attribute['rcc_regist_count'] = 0 ;
			}
}

class c_recruit_clickObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rcc_recruit_id';
	protected $table = 'c_recruit_click';
	protected $class = 'c_recruit_clickObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>