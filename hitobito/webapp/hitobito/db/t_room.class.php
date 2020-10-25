<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_room.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_roomObject extends HNbValueObject
{
	protected $pkey = 'rom_room_id';
	protected $table = 't_room';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rom_room_id'] = 0 ;
				$this->attribute['rom_status'] = 0 ;
				$this->attribute['rom_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rom_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rom_open_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rom_name'] = "" ;
				$this->attribute['rom_outline'] = "" ;
				$this->attribute['rom_banner_id'] = 0 ;
				$this->attribute['rom_room_code'] = "" ;
				$this->attribute['rom_regist_mail_flag'] = 1 ;
				$this->attribute['rom_resign_mail_flag'] = 1 ;
				$this->attribute['rom_header_flag'] = 1 ;
				$this->attribute['rom_footer_flag'] = 1 ;
				$this->attribute['rom_guest_login_flag'] = 1 ;
				$this->attribute['rom_guest_regist_notify_flag'] = 1 ;
				$this->attribute['rom_public_user_notify_flag'] = 1 ;
				$this->attribute['rom_header'] = "" ;
				$this->attribute['rom_footer'] = "" ;
				$this->attribute['rom_regist_mail'] = "" ;
				$this->attribute['rom_resign_mail'] = "" ;
				$this->attribute['rom_guest_regist_mail'] = "" ;
			}
}

class t_roomObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rom_room_id';
	protected $table = 't_room';
	protected $class = 't_roomObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>