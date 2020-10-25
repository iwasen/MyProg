<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_navi_osusume_link.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_navi_osusume_linkObject extends HNbValueObject
{
	protected $pkey = 'noc_osusume_link_id';
	protected $table = 'c_navi_osusume_link';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['noc_osusume_link_id'] = 0 ;
				$this->attribute['noc_click_count'] = 0 ;
			}
}

class c_navi_osusume_linkObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'noc_osusume_link_id';
	protected $table = 'c_navi_osusume_link';
	protected $class = 'c_navi_osusume_linkObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>