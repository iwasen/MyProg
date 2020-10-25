<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_melmaga_trackback.class.php,v 1.1 2006/01/11 13:53:13 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_melmaga_trackbackObject extends HNbValueObject
{
	protected $pkey = array('mmt_melmaga_contents_id','mmt_trackback_no');
	protected $table = 'l_melmaga_trackback';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmt_melmaga_contents_id'] = 0 ;
				$this->attribute['mmt_trackback_no'] = 0 ;
				$this->attribute['mmt_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmt_title'] = "" ;
				$this->attribute['mmt_excerpt'] = "" ;
				$this->attribute['mmt_url'] = "" ;
				$this->attribute['mmt_blog_name'] = "" ;
				$this->attribute['mmt_ng_word'] = "" ;
				$this->attribute['mmt_patrol_check'] = 1 ;
				$this->attribute['mmt_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmt_patrol_id'] = 0 ;
				$this->attribute['mmt_admin_check'] = 1 ;
				$this->attribute['mmt_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmt_admin_id'] = 0 ;
				$this->attribute['mmt_open_flag'] = 1 ;
				$this->attribute['mmt_pv_id'] = 0 ;
			}
}

class l_melmaga_trackbackObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('mmt_melmaga_contents_id','mmt_trackback_no');
	protected $table = 'l_melmaga_trackback';
	protected $class = 'l_melmaga_trackbackObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>