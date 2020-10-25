<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_page_daily_report.class.php,v 1.2 2006/05/06 04:57:12 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_page_daily_reportObject extends HNbValueObject
{
	protected $pkey = 'ndr_navi_page_id';
	protected $table = 't_navi_page_daily_report';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['ndr_navi_page_id'] = 0 ;
				$this->attribute['ndr_status'] = 0 ;
				$this->attribute['ndr_comment_report'] = 0 ;
				$this->attribute['ndr_trackback_report'] = 0 ;
				$this->attribute['ndr_contact_report'] = 0 ;
				$this->attribute['ndr_page_view_report'] = 0 ;
				$this->attribute['ndr_visiter_report'] = 0 ;
				$this->attribute['ndr_answer_report'] = 0 ;
				$this->attribute['ndr_last_guest_report'] = 0 ;
				$this->attribute['ndr_last_regist_report'] = 0 ;
				$this->attribute['ndr_last_resign_report'] = 0 ;
				$this->attribute['ndr_poster_report'] = 0 ;
				$this->attribute['ndr_posts_report'] = 0 ;
				$this->attribute['ndr_last_report_time'] = date("Y-m-d H:i:s") ;
			}
}

class t_navi_page_daily_reportObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'ndr_navi_page_id';
	protected $table = 't_navi_page_daily_report';
	protected $class = 't_navi_page_daily_reportObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>