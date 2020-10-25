<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_navi_enquete.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_navi_enqueteObject extends HNbValueObject
{
	protected $pkey = 'xxx_enquete_id';
	protected $table = 'c_navi_enquete';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['xxx_enquete_id'] = 0 ;
				$this->attribute['xxx_count'] = 0 ;
			}
}

class c_navi_enqueteObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'xxx_enquete_id';
	protected $table = 'c_navi_enquete';
	protected $class = 'c_navi_enqueteObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>