<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_link_category.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_link_categoryObject extends HNbValueObject
{
	protected $pkey = 'lct_link_category_id';
	protected $table = 'm_link_category';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['lct_link_category_id'] = 0 ;
				$this->attribute['lct_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['lct_navi_page_id'] = 0 ;
				$this->attribute['lct_category_name'] = "" ;
				$this->attribute['lct_link_num'] = 0 ;
			}
}

class m_link_categoryObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'lct_link_category_id';
	protected $table = 'm_link_category';
	protected $class = 'm_link_categoryObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>