<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_ngword.class.php,v 1.1 2006/01/09 14:31:36 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_ngwordObject extends HNbValueObject
{
	protected $pkey = 'bng_blog_ngword_id';
	protected $table = 't_blog_ngword';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bng_navi_page_id'] = 0 ;
				$this->attribute['bng_type'] = 1 ;
				$this->attribute['bng_word'] = "" ;
				$this->attribute['bng_status'] = 1 ;
				$this->attribute['bng_blog_ngword_id'] = 0 ;
				$this->attribute['bng_date'] = date("Y-m-d H:i:s") ;
			}
}

class t_blog_ngwordObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bng_blog_ngword_id';
	protected $table = 't_blog_ngword';
	protected $class = 't_blog_ngwordObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>