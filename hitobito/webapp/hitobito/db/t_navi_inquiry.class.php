<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_inquiry.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_inquiryObject extends HNbValueObject
{
	protected $pkey = 'nvi_inquiry_id';
	protected $table = 't_navi_inquiry';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nvi_inquiry_id'] = 0 ;
				$this->attribute['nvi_navi_page_id'] = 0 ;
				$this->attribute['nvi_status'] = 1 ;
				$this->attribute['nvi_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvi_name'] = "" ;
				$this->attribute['nvi_mail_addr'] = "" ;
				$this->attribute['nvi_inquiry_type'] = 0 ;
				$this->attribute['nvi_content'] = "" ;
				$this->attribute['nvi_company_name'] = "" ;
				$this->attribute['nvi_section_name'] = "" ;
				$this->attribute['nvi_tel_no'] = "" ;
				$this->attribute['nvi_fax_no'] = "" ;
				$this->attribute['nvi_admin_check'] = 1 ;
				$this->attribute['nvi_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvi_admin_id'] = 0 ;
				$this->attribute['nvi_communication_msg'] = "" ;
			}
}

class t_navi_inquiryObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nvi_inquiry_id';
	protected $table = 't_navi_inquiry';
	protected $class = 't_navi_inquiryObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>