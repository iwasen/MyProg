<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_daily_count.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_daily_countObject extends HNbValueObject
{
	protected $pkey = array('day_date','day_navi_page_id');
	protected $table = 't_daily_count';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['day_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['day_navi_page_id'] = 0 ;
				$this->attribute['day_member_num'] = 0 ;
				$this->attribute['day_letter_num'] = 0 ;
				$this->attribute['day_ml_num'] = 0 ;
				$this->attribute['day_blog_num'] = 0 ;
				$this->attribute['day_article_num'] = 0 ;
				$this->attribute['day_reader_num'] = 0 ;
			}
}

class t_daily_countObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('day_date','day_navi_page_id');
	protected $table = 't_daily_count';
	protected $class = 't_daily_countObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>