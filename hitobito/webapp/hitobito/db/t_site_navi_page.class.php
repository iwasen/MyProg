<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_site_navi_page.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_site_navi_pageObject extends HNbValueObject
{
	protected $pkey = array('snp_date','snp_channel_id');
	protected $table = 't_site_navi_page';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['snp_date'] = "" ;
				$this->attribute['snp_channel_id'] = 0 ;
				$this->attribute['snp_navi_page_num'] = 0 ;
			}
}

class t_site_navi_pageObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('snp_date','snp_channel_id');
	protected $table = 't_site_navi_page';
	protected $class = 't_site_navi_pageObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>