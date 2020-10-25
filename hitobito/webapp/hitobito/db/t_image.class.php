<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_image.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_imageObject extends HNbValueObject
{
	protected $pkey = 'img_image_id';
	protected $table = 't_image';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['img_image_id'] = 0 ;
				$this->attribute['img_data_size'] = 0 ;
				$this->attribute['img_file_name'] = "" ;
				$this->attribute['img_content_type'] = "" ;
				$this->attribute['img_image_data'] = "" ;
			}
}

class t_imageObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'img_image_id';
	protected $table = 't_image';
	protected $class = 't_imageObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>