<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_hitobito_letter.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_hitobito_letterObject extends HNbValueObject
{
	protected $pkey = 'hbl_letter_id';
	protected $table = 't_hitobito_letter';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['hbl_letter_id'] = 0 ;
				$this->attribute['hbl_status'] = 1 ;
				$this->attribute['hbl_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['hbl_job_name'] = "" ;
				$this->attribute['hbl_target'] = 0 ;
				$this->attribute['hbl_mail_format'] = 1 ;
				$this->attribute['hbl_subject'] = "" ;
				$this->attribute['hbl_from_addr'] = "" ;
				$this->attribute['hbl_header'] = "" ;
				$this->attribute['hbl_body'] = "" ;
				$this->attribute['hbl_footer'] = "" ;
				$this->attribute['hbl_send_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['hbl_end_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['hbl_send_num'] = 0 ;
				$this->attribute['hbl_enquete_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['hbl_backnumber'] = 0 ;
				$this->attribute['hbl_enq_title'] = "" ;
				$this->attribute['hbl_enq_question'] = "" ;
				$this->attribute['hbl_enq_nickname'] = "" ;
				$this->attribute['hbl_enq_comment'] = "" ;
			}
}

class t_hitobito_letterObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'hbl_letter_id';
	protected $table = 't_hitobito_letter';
	protected $class = 't_hitobito_letterObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>