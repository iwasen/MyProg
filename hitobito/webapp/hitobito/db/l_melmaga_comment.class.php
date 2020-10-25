<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_melmaga_comment.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_melmaga_commentObject extends HNbValueObject
{
	protected $pkey = array('mmo_melmaga_contents_id','mmo_comment_no');
	protected $table = 'l_melmaga_comment';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmo_melmaga_contents_id'] = 0 ;
				$this->attribute['mmo_comment_no'] = 0 ;
				$this->attribute['mmo_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmo_user_name'] = "" ;
				$this->attribute['mmo_comment'] = "" ;
				$this->attribute['mmo_ng_word'] = "" ;
				$this->attribute['mmo_patrol_check'] = 1 ;
				$this->attribute['mmo_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmo_patrol_id'] = 0 ;
				$this->attribute['mmo_admin_check'] = 1 ;
				$this->attribute['mmo_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmo_admin_id'] = 0 ;
				$this->attribute['mmo_open_flag'] = 1 ;
				$this->attribute['mmo_pv_id'] = 0 ;
			}
}

class l_melmaga_commentObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('mmo_melmaga_contents_id','mmo_comment_no');
	protected $table = 'l_melmaga_comment';
	protected $class = 'l_melmaga_commentObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>