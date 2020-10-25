<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_ichioshi_navi.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_ichioshi_naviObject extends HNbValueObject
{
	protected $pkey = array('inv_channel_id','inv_position');
	protected $table = 't_ichioshi_navi';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['inv_channel_id'] = 0 ;
				$this->attribute['inv_position'] = 0 ;
				$this->attribute['inv_selection_mode'] = 0 ;
				$this->attribute['inv_fix_navi_page_id'] = 0 ;
				$this->attribute['inv_loop_navi_page_id'] = "" ;
				$this->attribute['inv_current_navi_page_id'] = 0 ;
			}
}

class t_ichioshi_naviObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('inv_channel_id','inv_position');
	protected $table = 't_ichioshi_navi';
	protected $class = 't_ichioshi_naviObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>