<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi.class.php,v 1.6 2006/06/06 15:56:03 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_naviObject extends HNbValueObject
{
	protected $pkey = 'nav_navi_id';
	protected $table = 't_navi';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nav_navi_id'] = 0 ;
				$this->attribute['nav_member_id'] = 0 ;
				$this->attribute['nav_status'] = 1 ;
				$this->attribute['nav_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nav_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nav_resign_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nav_project_id'] = 0 ;
				$this->attribute['nav_name1'] = "" ;
				$this->attribute['nav_name2'] = "" ;
				$this->attribute['nav_name1_kana'] = "" ;
				$this->attribute['nav_name2_kana'] = "" ;
				$this->attribute['nav_birthday'] = "" ;
				$this->attribute['nav_sex_cd'] = 0 ;
				$this->attribute['nav_zip_cd'] = 0 ;
				$this->attribute['nav_jitaku_tel'] = "" ;
				$this->attribute['nav_jitaku_fax'] = "" ;
				$this->attribute['nav_kinmu_tel'] = "" ;
				$this->attribute['nav_address1'] = 0 ;
				$this->attribute['nav_address2'] = "" ;
				$this->attribute['nav_address3'] = "" ;
				$this->attribute['nav_company'] = "" ;
				$this->attribute['nav_shokugyo_id'] = 0 ;
				$this->attribute['nav_patrol_id'] = 0 ;
				$this->attribute['nav_hnb_id'] = 0 ;
				$this->attribute['nav_navi_email'] = "" ;
				$this->attribute['nav_forward_flag'] = 0 ;
				$this->attribute['nav_forward_id'] = "" ;
			}
}

class t_naviObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nav_navi_id';
	protected $table = 't_navi';
	protected $class = 't_naviObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>