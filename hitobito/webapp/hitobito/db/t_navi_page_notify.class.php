<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_page_notify.class.php,v 1.2 2006/04/30 16:09:44 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_page_notifyObject extends HNbValueObject
{
	protected $pkey = 'nnt_navi_page_id';
	protected $table = 't_navi_page_notify';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nnt_navi_page_id'] = 0 ;
				$this->attribute['nnt_comment_notify'] = 0 ;
				$this->attribute['nnt_trackback_notify'] = 0 ;
				$this->attribute['nnt_contact_notify'] = 1 ;
			}
}

class t_navi_page_notifyObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nnt_navi_page_id';
	protected $table = 't_navi_page_notify';
	protected $class = 't_navi_page_notifyObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>