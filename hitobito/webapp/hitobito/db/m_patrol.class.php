<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_patrol.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_patrolObject extends HNbValueObject
{
	protected $pkey = 'ptl_patrol_id';
	protected $table = 'm_patrol';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['ptl_patrol_id'] = 0 ;
				$this->attribute['ptl_status'] = 1 ;
				$this->attribute['ptl_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['ptl_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['ptl_mail_addr'] = "" ;
				$this->attribute['ptl_password'] = "" ;
				$this->attribute['ptl_name1'] = "" ;
				$this->attribute['ptl_name2'] = "" ;
				$this->attribute['ptl_name1_kana'] = "" ;
				$this->attribute['ptl_name2_kana'] = "" ;
			}
}

class m_patrolObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'ptl_patrol_id';
	protected $table = 'm_patrol';
	protected $class = 'm_patrolObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>