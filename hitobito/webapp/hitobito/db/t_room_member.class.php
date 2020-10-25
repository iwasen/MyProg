<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_room_member.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_room_memberObject extends HNbValueObject
{
	protected $pkey = array('rmm_room_id','rmm_member_id');
	protected $table = 't_room_member';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rmm_room_id'] = 0 ;
				$this->attribute['rmm_member_id'] = 0 ;
				$this->attribute['rmm_status'] = 0 ;
				$this->attribute['rmm_kari_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmm_guest_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmm_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmm_kari_varify_mail'] = 0 ;
				$this->attribute['rmm_regist_verify_mail'] = 0 ;
				$this->attribute['rmm_nickname'] = "" ;
				$this->attribute['rmm_member_code'] = "" ;
				$this->attribute['rmm_admim_flag'] = 0 ;
				$this->attribute['rmm_resign_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmm_mail_send_flag'] = 1 ;
			}
}

class t_room_memberObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('rmm_room_id','rmm_member_id');
	protected $table = 't_room_member';
	protected $class = 't_room_memberObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>