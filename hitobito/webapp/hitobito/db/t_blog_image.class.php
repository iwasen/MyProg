<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_image.class.php,v 1.1 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_imageObject extends HNbValueObject
{
	protected $pkey = 'bli_blog_image_id';
	protected $table = 't_blog_image';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bli_blog_image_id'] = 0 ;
				$this->attribute['bli_blog_id'] = 0 ;
				$this->attribute['bli_data_size'] = 0 ;
				$this->attribute['bli_file_name'] = "" ;
				$this->attribute['bli_content_type'] = "" ;
				$this->attribute['bli_width'] = 0 ;
				$this->attribute['bli_height'] = 0 ;
				$this->attribute['bli_image_data'] = 0 ;
				$this->attribute['bli_title'] = "" ;
			}
}

class t_blog_imageObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bli_blog_image_id';
	protected $table = 't_blog_image';
	protected $class = 't_blog_imageObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>