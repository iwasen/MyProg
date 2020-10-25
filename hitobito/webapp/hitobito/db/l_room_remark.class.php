<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_room_remark.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_room_remarkObject extends HNbValueObject
{
	protected $pkey = 'rmk_remark_id';
	protected $table = 'l_room_remark';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rmk_remark_id'] = 0 ;
				$this->attribute['rmk_room_id'] = 0 ;
				$this->attribute['rmk_member_id'] = 0 ;
				$this->attribute['rmk_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmk_status'] = 1 ;
				$this->attribute['rmk_remark_type'] = 0 ;
				$this->attribute['rmk_disp_type'] = 0 ;
				$this->attribute['rmk_disp_member_id'] = 0 ;
				$this->attribute['rmk_seq_no'] = 0 ;
				$this->attribute['rmk_parent_remark_id'] = 0 ;
				$this->attribute['rmk_child_num'] = 0 ;
				$this->attribute['rmk_from'] = "" ;
				$this->attribute['rmk_message_id'] = "" ;
				$this->attribute['rmk_mail_header'] = "" ;
				$this->attribute['rmk_subject'] = "" ;
				$this->attribute['rmk_content'] = "" ;
				$this->attribute['rmk_room_header'] = "" ;
				$this->attribute['rmk_room_footer'] = "" ;
				$this->attribute['rmk_web_mail'] = 0 ;
				$this->attribute['rmk_send_flag'] = 0 ;
				$this->attribute['rmk_ng_word'] = "" ;
				$this->attribute['rmk_patrol_check'] = 1 ;
				$this->attribute['rmk_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmk_patrol_id'] = 0 ;
				$this->attribute['rmk_admin_check'] = 1 ;
				$this->attribute['rmk_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmk_admin_id'] = 0 ;
				$this->attribute['rmk_open_flag'] = 1 ;
				$this->attribute['rmk_communication_msg'] = "" ;
				$this->attribute['rmk_pickup'] = 0 ;
				$this->attribute['rmk_keisai_flag'] = 1 ;
				$this->attribute['rmk_send_num'] = 0 ;
				$this->attribute['rmk_pv_id'] = 0 ;
				$this->attribute['rmk_nickname'] = "" ;
			}
}

class l_room_remarkObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rmk_remark_id';
	protected $table = 'l_room_remark';
	protected $class = 'l_room_remarkObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>