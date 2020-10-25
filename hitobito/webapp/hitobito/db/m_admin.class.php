<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_admin.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_adminObject extends HNbValueObject
{
	protected $pkey = 'adm_admin_id';
	protected $table = 'm_admin';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['adm_admin_id'] = 0 ;
				$this->attribute['adm_status'] = 1 ;
				$this->attribute['adm_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['adm_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['adm_type'] = 0 ;
				$this->attribute['adm_name1'] = "" ;
				$this->attribute['adm_name2'] = "" ;
				$this->attribute['adm_name1_kana'] = "" ;
				$this->attribute['adm_name2_kana'] = "" ;
				$this->attribute['adm_unit'] = "" ;
				$this->attribute['adm_mail_addr'] = "" ;
				$this->attribute['adm_password'] = "" ;
				$this->attribute['adm_picture_id'] = 0 ;
			}
}

class m_adminObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'adm_admin_id';
	protected $table = 'm_admin';
	protected $class = 'm_adminObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>