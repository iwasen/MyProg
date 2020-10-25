<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_room_public.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_room_publicObject extends HNbValueObject
{
	protected $pkey = array('rmp_room_id','rmp_seq_no');
	protected $table = 't_room_public';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rmp_room_id'] = 0 ;
				$this->attribute['rmp_seq_no'] = 0 ;
				$this->attribute['rmp_status'] = 1 ;
				$this->attribute['rmp_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmp_mail_addr'] = "" ;
				$this->attribute['rmp_nickname'] = "" ;
				$this->attribute['rmp_title'] = "" ;
				$this->attribute['rmp_content'] = "" ;
			}
}

class t_room_publicObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('rmp_room_id','rmp_seq_no');
	protected $table = 't_room_public';
	protected $class = 't_room_publicObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>