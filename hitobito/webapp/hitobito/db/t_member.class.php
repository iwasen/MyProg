<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_member.class.php,v 1.5 2006/06/05 13:44:01 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_memberObject extends HNbValueObject
{
	protected $pkey = 'mbr_member_id';
	protected $table = 't_member';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mbr_member_id'] = 0 ;
				$this->attribute['mbr_status'] = 1 ;
				$this->attribute['mbr_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mbr_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mbr_resign_id'] = 0 ;
				$this->attribute['mbr_resign_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mbr_mail_addr'] = "" ;
				$this->attribute['mbr_mail_addr_low'] = "" ;
				$this->attribute['mbr_password'] = "" ;
				$this->attribute['mbr_hitobito_letter'] = 0 ;
				$this->attribute['mbr_recruit_id'] = 0 ;
				$this->attribute['mbr_actkey'] = "" ;
				$this->attribute['mbr_name'] = "" ;
				$this->attribute['mbr_name_kana'] = "" ;
				$this->attribute['mbr_sex_cd'] = "" ;
				$this->attribute['mbr_birthday'] = "" ;
				$this->attribute['mbr_zip_cd'] = "" ;
				$this->attribute['mbr_address1'] = 0 ;
				$this->attribute['mbr_address2'] = "" ;
				$this->attribute['mbr_tel'] = "" ;
				$this->attribute['mbr_self_introduction'] = "" ;
			}
}

class t_memberObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'mbr_member_id';
	protected $table = 't_member';
	protected $class = 't_memberObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>