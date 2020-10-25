<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_comment.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_commentObject extends HNbValueObject
{
	protected $pkey = array('blo_article_id','blo_comment_no');
	protected $table = 't_blog_comment';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['blo_article_id'] = 0 ;
				$this->attribute['blo_comment_no'] = 0 ;
				$this->attribute['blo_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blo_user_name'] = "" ;
				$this->attribute['blo_comment'] = "" ;
				$this->attribute['blo_ng_word'] = "" ;
				$this->attribute['blo_patrol_check'] = 1 ;
				$this->attribute['blo_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blo_patrol_id'] = 0 ;
				$this->attribute['blo_admin_check'] = 1 ;
				$this->attribute['blo_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blo_admin_id'] = 0 ;
				$this->attribute['blo_open_flag'] = 1 ;
				$this->attribute['blo_pv_id'] = 0 ;
			}
}

class t_blog_commentObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('blo_article_id','blo_comment_no');
	protected $table = 't_blog_comment';
	protected $class = 't_blog_commentObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>