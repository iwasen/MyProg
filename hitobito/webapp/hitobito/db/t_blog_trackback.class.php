<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_trackback.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_trackbackObject extends HNbValueObject
{
	protected $pkey = array('blt_article_id','blt_trackback_no');
	protected $table = 't_blog_trackback';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['blt_article_id'] = 0 ;
				$this->attribute['blt_trackback_no'] = 0 ;
				$this->attribute['blt_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blt_title'] = "" ;
				$this->attribute['blt_excerpt'] = "" ;
				$this->attribute['blt_url'] = "" ;
				$this->attribute['blt_blog_name'] = "" ;
				$this->attribute['blt_ng_word'] = "" ;
				$this->attribute['blt_patrol_check'] = 1 ;
				$this->attribute['blt_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blt_patrol_id'] = 0 ;
				$this->attribute['blt_admin_check'] = 1 ;
				$this->attribute['blt_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blt_admin_id'] = 0 ;
				$this->attribute['blt_open_flag'] = 1 ;
				$this->attribute['blt_pv_id'] = 0 ;
			}
}

class t_blog_trackbackObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('blt_article_id','blt_trackback_no');
	protected $table = 't_blog_trackback';
	protected $class = 't_blog_trackbackObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>