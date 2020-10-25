<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_batch.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_batchObject extends HNbValueObject
{
	protected $pkey = 'lbt_seq_no';
	protected $table = 'l_batch';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['lbt_seq_no'] = 0 ;
				$this->attribute['lbt_status'] = 0 ;
				$this->attribute['lbt_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['lbt_file_name'] = "" ;
				$this->attribute['lbt_description'] = "" ;
				$this->attribute['lbt_processing_time'] = "" ;
			}
}

class l_batchObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'lbt_seq_no';
	protected $table = 'l_batch';
	protected $class = 'l_batchObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>