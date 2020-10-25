<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_ranking.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_rankingObject extends HNbValueObject
{
	protected $pkey = 'nvr_navi_page_id';
	protected $table = 't_navi_ranking';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nvr_navi_page_id'] = 0 ;
				$this->attribute['nvr_rank_no'] = 0 ;
				$this->attribute['nvr_ml_member_num'] = 0 ;
				$this->attribute['nvr_ml_member_up'] = 0 ;
				$this->attribute['nvr_ml_total_remark'] = 0 ;
				$this->attribute['nvr_ml_page_view'] = 0 ;
				$this->attribute['nvr_ml_member_remark'] = 0 ;
				$this->attribute['nvr_blog_new_post'] = 0 ;
				$this->attribute['nvr_blog_page_view'] = 0 ;
				$this->attribute['nvr_blog_trackback'] = 0 ;
				$this->attribute['nvr_blog_comment'] = 0 ;
			}
}

class t_navi_rankingObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nvr_navi_page_id';
	protected $table = 't_navi_ranking';
	protected $class = 't_navi_rankingObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>