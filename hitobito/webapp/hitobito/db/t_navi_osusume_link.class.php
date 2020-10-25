<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_osusume_link.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_osusume_linkObject extends HNbValueObject
{
	protected $pkey = 'nol_osusume_link_id';
	protected $table = 't_navi_osusume_link';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nol_osusume_link_id'] = 0 ;
				$this->attribute['nol_navi_page_id'] = 0 ;
				$this->attribute['nol_order'] = 0 ;
				$this->attribute['nol_link_category_id'] = 0 ;
				$this->attribute['nol_link_url'] = "" ;
				$this->attribute['nol_link_name'] = "" ;
				$this->attribute['nol_link_outline'] = "" ;
				$this->attribute['nol_ng_word'] = "" ;
				$this->attribute['nol_patrol_check'] = 1 ;
				$this->attribute['nol_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nol_patrol_id'] = 0 ;
				$this->attribute['nol_admin_check'] = 1 ;
				$this->attribute['nol_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nol_admin_id'] = 0 ;
				$this->attribute['nol_open_flag'] = 1 ;
				$this->attribute['nol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nol_communication_msg'] = "" ;
				$this->attribute['nol_pickup'] = 0 ;
				$this->attribute['nol_pv_id'] = 0 ;
			}
}

class t_navi_osusume_linkObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nol_osusume_link_id';
	protected $table = 't_navi_osusume_link';
	protected $class = 't_navi_osusume_linkObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>