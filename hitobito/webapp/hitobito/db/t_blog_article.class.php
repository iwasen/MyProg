<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_article.class.php,v 1.5 2006/06/07 09:08:32 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_articleObject extends HNbValueObject
{
	protected $pkey = 'bla_article_id';
	protected $table = 't_blog_article';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bla_article_id'] = 0 ;
				$this->attribute['bla_blog_id'] = 0 ;
				$this->attribute['bla_status'] = 0 ;
				$this->attribute['bla_title'] = "" ;
				$this->attribute['bla_article_text'] = "" ;
				$this->attribute['bla_ng_word'] = "" ;
				$this->attribute['bla_patrol_check'] = 1 ;
				$this->attribute['bla_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['bla_patrol_id'] = 0 ;
				$this->attribute['bla_admin_check'] = 1 ;
				$this->attribute['bla_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['bla_admin_id'] = 0 ;
				$this->attribute['bla_open_flag'] = 1 ;
				$this->attribute['bla_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['bla_communication_msg'] = "" ;
				$this->attribute['bla_pickup'] = 0 ;
				$this->attribute['bla_keisai_flag'] = 1 ;
				$this->attribute['bla_navi_select_flag'] = 0 ;
				$this->attribute['bla_comment_flag'] = 1 ;
				$this->attribute['bla_trackback_flag'] = 1 ;
				$this->attribute['bla_pv_id'] = 0 ;
				$this->attribute['bla_blog_category_id'] = 0 ;
				$this->attribute['bla_member_id'] = 0 ;
				$this->attribute['bla_order'] = 0 ;
				$this->attribute['bla_blog_image_id'] = 0 ;
				$this->attribute['bla_ping_flag'] = 0 ;
			}
}

class t_blog_articleObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bla_article_id';
	protected $table = 't_blog_article';
	protected $class = 't_blog_articleObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>