<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_room_header.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_room_headerObject extends HNbValueObject
{
	protected $pkey = 'rmh_header_id';
	protected $table = 'm_room_header';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rmh_header_id'] = 0 ;
				$this->attribute['rmh_header'] = "" ;
				$this->attribute['rmh_footer'] = "" ;
			}
}

class m_room_headerObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rmh_header_id';
	protected $table = 'm_room_header';
	protected $class = 'm_room_headerObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>