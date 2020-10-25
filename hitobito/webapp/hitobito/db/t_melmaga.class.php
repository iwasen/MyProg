<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_melmaga.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_melmagaObject extends HNbValueObject
{
	protected $pkey = 'mmg_melmaga_id';
	protected $table = 't_melmaga';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmg_melmaga_id'] = 0 ;
				$this->attribute['mmg_status'] = 1 ;
				$this->attribute['mmg_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmg_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmg_melmaga_name'] = "" ;
				$this->attribute['mmg_melmaga_intro'] = "" ;
				$this->attribute['mmg_melmaga_footer'] = "" ;
				$this->attribute['mmg_backnumber'] = 1 ;
				$this->attribute['mmg_notify_mail'] = 1 ;
			}
}

class t_melmagaObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'mmg_melmaga_id';
	protected $table = 't_melmaga';
	protected $class = 't_melmagaObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>