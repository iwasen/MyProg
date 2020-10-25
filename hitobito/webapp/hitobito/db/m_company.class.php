<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_company.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_companyObject extends HNbValueObject
{
	protected $pkey = 'cmp_company_id';
	protected $table = 'm_company';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['cmp_company_id'] = 0 ;
				$this->attribute['cmp_name'] = "" ;
				$this->attribute['cmp_name_kana'] = "" ;
				$this->attribute['cmp_zip'] = "" ;
				$this->attribute['cmp_address1'] = 0 ;
				$this->attribute['cmp_address2'] = "" ;
				$this->attribute['cmp_address3'] = "" ;
				$this->attribute['cmp_address4'] = "" ;
			}
}

class m_companyObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'cmp_company_id';
	protected $table = 'm_company';
	protected $class = 'm_companyObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>