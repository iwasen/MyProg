<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_room_enquete.class.php,v 1.1 2006/06/05 13:44:01 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_room_enqueteObject extends HNbValueObject
{
	protected $pkey = 'ren_enquete_id';
	protected $table = 't_room_enquete';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['ren_enquete_id'] = 0 ;
				$this->attribute['ren_status'] = 1 ;
				$this->attribute['ren_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['ren_job_name'] = "" ;
				$this->attribute['ren_start_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['ren_end_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['ren_room_id'] = 0 ;
				$this->attribute['ren_target'] = 0 ;
				$this->attribute['ren_title'] = "" ;
				$this->attribute['ren_enquete_text'] = "" ;
			}
}

class t_room_enqueteObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'ren_enquete_id';
	protected $table = 't_room_enquete';
	protected $class = 't_room_enqueteObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>