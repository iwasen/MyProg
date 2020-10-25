<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_ichioshi_navi_update.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_ichioshi_navi_updateObject extends HNbValueObject
{
	protected $pkey = 'inu_update_mode';
	protected $table = 't_ichioshi_navi_update';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['inu_update_mode'] = 0 ;
				$this->attribute['inu_update_time'] = 0 ;
			}
}

class t_ichioshi_navi_updateObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'inu_update_mode';
	protected $table = 't_ichioshi_navi_update';
	protected $class = 't_ichioshi_navi_updateObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>