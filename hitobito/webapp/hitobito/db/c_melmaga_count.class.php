<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_melmaga_count.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_melmaga_countObject extends HNbValueObject
{
	protected $pkey = 'mmn_melmaga_id';
	protected $table = 'c_melmaga_count';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmn_melmaga_id'] = 0 ;
				$this->attribute['mmn_reader_cnt'] = 0 ;
				$this->attribute['mmn_publish_cnt'] = 0 ;
				$this->attribute['mmn_last_publish_date'] = date("Y-m-d H:i:s") ;
			}
}

class c_melmaga_countObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'mmn_melmaga_id';
	protected $table = 'c_melmaga_count';
	protected $class = 'c_melmaga_countObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>