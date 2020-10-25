<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blogObject extends HNbValueObject
{
	protected $pkey = 'blg_blog_id';
	protected $table = 't_blog';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['blg_blog_id'] = 0 ;
				$this->attribute['blg_status'] = 1 ;
				$this->attribute['blg_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blg_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['blg_title'] = "" ;
			}
}

class t_blogObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'blg_blog_id';
	protected $table = 't_blog';
	protected $class = 't_blogObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>