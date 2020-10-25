<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_send_mail_data.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_send_mail_dataObject extends HNbValueObject
{
	protected $pkey = 'sd_send_mail_id';
	protected $table = 't_send_mail_data';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sd_send_mail_id'] = 0 ;
				$this->attribute['sd_subject'] = "" ;
				$this->attribute['sd_from'] = "" ;
				$this->attribute['sd_reply_to'] = "" ;
				$this->attribute['sd_body'] = "" ;
				$this->attribute['sd_status'] = "" ;
				$this->attribute['sd_start_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['sd_end_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['sd_mail_header'] = "" ;
				$this->attribute['sd_send_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['sd_server'] = "" ;
				$this->attribute['sd_personal'] = "" ;
				$this->attribute['sd_mail_type'] = "" ;
				$this->attribute['sd_end_sql'] = "" ;
			}
}

class t_send_mail_dataObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'sd_send_mail_id';
	protected $table = 't_send_mail_data';
	protected $class = 't_send_mail_dataObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>