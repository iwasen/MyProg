<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_resign_reason.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_resign_reasonObject extends HNbValueObject
{
	protected $pkey = 'rsg_resign_id';
	protected $table = 'm_resign_reason';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rsg_resign_id'] = 0 ;
				$this->attribute['rsg_status'] = 1 ;
				$this->attribute['rsg_reason_text'] = "" ;
			}
}

class m_resign_reasonObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rsg_resign_id';
	protected $table = 'm_resign_reason';
	protected $class = 'm_resign_reasonObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>