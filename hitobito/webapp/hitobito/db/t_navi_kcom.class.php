<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_kcom.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_kcomObject extends HNbValueObject
{
	protected $pkey = array('nvk_navi_page_id','nvk_kcom_l1','nvk_kcom_l2','nvk_kcom_l3','nvk_kcom_l4','nvk_kcom_l5');
	protected $table = 't_navi_kcom';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nvk_navi_page_id'] = 0 ;
				$this->attribute['nvk_kcom_l1'] = 0 ;
				$this->attribute['nvk_kcom_l2'] = 0 ;
				$this->attribute['nvk_kcom_l3'] = 0 ;
				$this->attribute['nvk_kcom_l4'] = 0 ;
				$this->attribute['nvk_kcom_l5'] = 0 ;
			}
}

class t_navi_kcomObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('nvk_navi_page_id','nvk_kcom_l1','nvk_kcom_l2','nvk_kcom_l3','nvk_kcom_l4','nvk_kcom_l5');
	protected $table = 't_navi_kcom';
	protected $class = 't_navi_kcomObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>