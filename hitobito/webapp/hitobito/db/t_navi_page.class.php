<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_page.class.php,v 1.5 2006/01/22 08:45:14 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_pageObject extends HNbValueObject
{
	protected $pkey = 'nvp_navi_page_id';
	protected $table = 't_navi_page';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nvp_navi_page_id'] = 0 ;
				$this->attribute['nvp_navi_id'] = 0 ;
				$this->attribute['nvp_status'] = 0 ;
				$this->attribute['nvp_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvp_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvp_open_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvp_resign_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nvp_theme'] = "" ;
				$this->attribute['nvp_channel_id'] = 0 ;
				$this->attribute['nvp_subchannel_id'] = 0 ;
				$this->attribute['nvp_type'] = 0 ;
				$this->attribute['nvp_outline'] = "" ;
				$this->attribute['nvp_blog_id'] = 0 ;
				$this->attribute['nvp_room_id'] = 0 ;
				$this->attribute['nvp_melmaga_id'] = 0 ;
				$this->attribute['nvp_title'] = "" ;
				$this->attribute['nvp_copy'] = "" ;
				$this->attribute['nvp_self_introduction'] = "" ;
				$this->attribute['nvp_image_id'] = 0 ;
				$this->attribute['nvp_open_flag'] = 0 ;
				$this->attribute['nvp_contents_check'] = 0 ;
				$this->attribute['nvp_banner_id'] = null;
			}
}

class t_navi_pageObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nvp_navi_page_id';
	protected $table = 't_navi_page';
	protected $class = 't_navi_pageObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>