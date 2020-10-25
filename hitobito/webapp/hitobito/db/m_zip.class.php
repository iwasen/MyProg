<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_zip.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_zipObject extends HNbValueObject
{
	protected $pkey = 'zip_seq_no';
	protected $table = 'm_zip';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['zip_seq_no'] = 0 ;
				$this->attribute['zip_zip_cd'] = 0 ;
				$this->attribute['zip_todoufuken'] = "" ;
				$this->attribute['zip_shikuchouson'] = "" ;
				$this->attribute['zip_chouiki'] = "" ;
				$this->attribute['zip_zip_uniq'] = "" ;
			}
}

class m_zipObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'zip_seq_no';
	protected $table = 'm_zip';
	protected $class = 'm_zipObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>