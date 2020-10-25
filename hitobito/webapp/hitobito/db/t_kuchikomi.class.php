<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_kuchikomi.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_kuchikomiObject extends HNbValueObject
{
	protected $pkey = 'kck_kuchikomi_id';
	protected $table = 't_kuchikomi';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['kck_kuchikomi_id'] = 0 ;
				$this->attribute['kck_image_url'] = "" ;
				$this->attribute['kck_content'] = "" ;
				$this->attribute['kck_page_url'] = "" ;
				$this->attribute['kck_shouhin'] = "" ;
				$this->attribute['kck_nickname'] = "" ;
				$this->attribute['kck_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['kck_keisai_flag'] = 1 ;
				$this->attribute['kck_title'] = "" ;
			}
}

class t_kuchikomiObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'kck_kuchikomi_id';
	protected $table = 't_kuchikomi';
	protected $class = 't_kuchikomiObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>