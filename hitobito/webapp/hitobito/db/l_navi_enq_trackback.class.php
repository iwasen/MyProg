<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_navi_enq_trackback.class.php,v 1.5 2006/01/11 13:53:13 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_navi_enq_trackbackObject extends HNbValueObject
{
	protected $pkey = array('net_enquete_id','net_trackback_no');
	protected $table = 'l_navi_enq_trackback';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['net_enquete_id'] = 0 ;
				$this->attribute['net_trackback_no'] = 0 ;
				$this->attribute['net_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['net_title'] = "" ;
				$this->attribute['net_excerpt'] = "" ;
				$this->attribute['net_url'] = "" ;
				$this->attribute['net_blog_name'] = "" ;
				$this->attribute['net_ng_word'] = "" ;
				$this->attribute['net_patrol_check'] = 1 ;
				$this->attribute['net_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['net_patrol_id'] = 0 ;
				$this->attribute['net_admin_check'] = 1 ;
				$this->attribute['net_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['net_admin_id'] = 0 ;
				$this->attribute['net_open_flag'] = 1 ;
				$this->attribute['net_pv_id'] = 0 ;
			}
}

class l_navi_enq_trackbackObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('net_enquete_id','net_trackback_no');
	protected $table = 'l_navi_enq_trackback';
	protected $class = 'l_navi_enq_trackbackObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>