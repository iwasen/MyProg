<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_kcom_category.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_kcom_categoryObject extends HNbValueObject
{
	protected $pkey = array('kcg_l1','kcg_l2','kcg_l3','kcg_l4','kcg_l5');
	protected $table = 'm_kcom_category';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['kcg_l1'] = 0 ;
				$this->attribute['kcg_l2'] = 0 ;
				$this->attribute['kcg_l3'] = 0 ;
				$this->attribute['kcg_l4'] = 0 ;
				$this->attribute['kcg_l5'] = 0 ;
				$this->attribute['kcg_status'] = 0 ;
				$this->attribute['kcg_name'] = "" ;
			}
}

class m_kcom_categoryObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('kcg_l1','kcg_l2','kcg_l3','kcg_l4','kcg_l5');
	protected $table = 'm_kcom_category';
	protected $class = 'm_kcom_categoryObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>