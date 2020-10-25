<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_click_count.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_click_countObject extends HNbValueObject
{
	protected $pkey = 'tcc_click_id';
	protected $table = 't_click_count';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['tcc_click_id'] = 0 ;
				$this->attribute['tcc_letter_id'] = 0 ;
				$this->attribute['tcc_url_name'] = "" ;
				$this->attribute['tcc_jump_url'] = "" ;
				$this->attribute['tcc_start_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['tcc_end_date'] = date("Y-m-d H:i:s") ;
			}
}

class t_click_countObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'tcc_click_id';
	protected $table = 't_click_count';
	protected $class = 't_click_countObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>