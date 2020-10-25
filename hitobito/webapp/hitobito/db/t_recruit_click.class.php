<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_recruit_click.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_recruit_clickObject extends HNbValueObject
{
	protected $pkey = 'rct_recruit_id';
	protected $table = 't_recruit_click';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rct_recruit_id'] = 0 ;
				$this->attribute['rct_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rct_counter_name'] = "" ;
				$this->attribute['rct_jump_url'] = "" ;
				$this->attribute['rct_usage'] = "" ;
			}
}

class t_recruit_clickObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rct_recruit_id';
	protected $table = 't_recruit_click';
	protected $class = 't_recruit_clickObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>