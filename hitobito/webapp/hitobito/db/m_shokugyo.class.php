<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_shokugyo.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_shokugyoObject extends HNbValueObject
{
	protected $pkey = 'skg_shokugyo_id';
	protected $table = 'm_shokugyo';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['skg_shokugyo_id'] = 0 ;
				$this->attribute['skg_shokugyo_name'] = "" ;
				$this->attribute['skg_order'] = 0 ;
			}
}

class m_shokugyoObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'skg_shokugyo_id';
	protected $table = 'm_shokugyo';
	protected $class = 'm_shokugyoObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>