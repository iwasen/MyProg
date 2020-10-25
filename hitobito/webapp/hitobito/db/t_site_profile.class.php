<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_site_profile.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_site_profileObject extends HNbValueObject
{
	protected $pkey = 'stp_date';
	protected $table = 't_site_profile';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['stp_date'] = 0 ;
				$this->attribute['stp_navi_num'] = 0 ;
				$this->attribute['stp_member_num'] = 0 ;
				$this->attribute['stp_letter_member'] = 0 ;
				$this->attribute['stp_blog_num'] = 0 ;
				$this->attribute['stp_room_num'] = 0 ;
				$this->attribute['stp_article_num'] = 0 ;
				$this->attribute['stp_remark_num'] = 0 ;
				$this->attribute['stp_remark_count'] = 0 ;
				$this->attribute['stp_mail_num'] = 0 ;
				$this->attribute['stp_ram_rate'] = 0 ;
				$this->attribute['stp_page_view'] = 0 ;
				$this->attribute['stp_visitor_num'] = 0 ;
				$this->attribute['stp_click_num'] = 0 ;
				$this->attribute['stp_trackback_num'] = 0 ;
				$this->attribute['stp_comment_num'] = 0 ;
			}
}

class t_site_profileObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'stp_date';
	protected $table = 't_site_profile';
	protected $class = 't_site_profileObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>