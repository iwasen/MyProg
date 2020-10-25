<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_assist_navi.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_assist_naviObject extends HNbValueObject
{
	protected $pkey = array('anv_navi_id','anv_navi_page_id');
	protected $table = 't_assist_navi';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['anv_navi_id'] = 0 ;
				$this->attribute['anv_navi_page_id'] = 0 ;
				$this->attribute['anv_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['anv_order'] = 0 ;
			}
}

class t_assist_naviObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('anv_navi_id','anv_navi_page_id');
	protected $table = 't_assist_navi';
	protected $class = 't_assist_naviObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>