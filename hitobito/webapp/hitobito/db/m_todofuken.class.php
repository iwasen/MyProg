<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_todofuken.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_todofukenObject extends HNbValueObject
{
	protected $pkey = 'tdf_area_id';
	protected $table = 'm_todofuken';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['tdf_area_id'] = 0 ;
				$this->attribute['tdf_name'] = "" ;
			}
}

class m_todofukenObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'tdf_area_id';
	protected $table = 'm_todofuken';
	protected $class = 'm_todofukenObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>