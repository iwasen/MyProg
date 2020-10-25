<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_send_mail_list.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_send_mail_listObject extends HNbValueObject
{
	protected $pkey = array('sl_send_mail_id','sl_to');
	protected $table = 't_send_mail_list';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sl_send_mail_id'] = 0 ;
				$this->attribute['sl_to'] = 0 ;
				$this->attribute['sl_sub_seq'] = 0 ;
				$this->attribute['sl_mail_header'] = "" ;
			}
}

class t_send_mail_listObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('sl_send_mail_id','sl_to');
	protected $table = 't_send_mail_list';
	protected $class = 't_send_mail_listObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>