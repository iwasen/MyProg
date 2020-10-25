<?php
/**
  *
  * @@author Ryuji
  * @version $Id: adodb_logsql.class.php,v 1.3 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class adodb_logsqlObject extends HNbValueObject
{
	protected $pkey = '';
	protected $table = 'adodb_logsql';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['created'] = date("Y-m-d H:i:s") ;
				$this->attribute['sql0'] = date("Y-m-d H:i:s") ;
				$this->attribute['sql1'] = "" ;
				$this->attribute['params'] = "" ;
				$this->attribute['tracer'] = "" ;
				$this->attribute['timer'] = "" ;
			}
}

class adodb_logsqlObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = '';
	protected $table = 'adodb_logsql';
	protected $class = 'adodb_logsqlObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>