<?php
/**
  *
  * @@author Ryuji
  * @version $Id: blog_category.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class blog_categoryObject extends HNbValueObject
{
	protected $pkey = 'blog_category_id';
	protected $table = 'blog_category';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['blog_category_id'] = 0 ;
				$this->attribute['blog_id'] = 0 ;
				$this->attribute['category_name'] = "" ;
				$this->attribute['category_order'] = 0 ;
			}
}

class blog_categoryObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'blog_category_id';
	protected $table = 'blog_category';
	protected $class = 'blog_categoryObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>