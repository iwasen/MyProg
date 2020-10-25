<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_system.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_systemObject extends HNbValueObject
{
	protected $pkey = 'sys_key';
	protected $table = 'm_system';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sys_key'] = 0 ;
				$this->attribute['sys_value'] = "" ;
				$this->attribute['sys_caption'] = "" ;
			}
}

class m_systemObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'sys_key';
	protected $table = 'm_system';
	protected $class = 'm_systemObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>