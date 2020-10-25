<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_navi_enq_comment.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_navi_enq_commentObject extends HNbValueObject
{
	protected $pkey = array('neo_enquete_id','neo_comment_no');
	protected $table = 'l_navi_enq_comment';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['neo_enquete_id'] = 0 ;
				$this->attribute['neo_comment_no'] = 0 ;
				$this->attribute['neo_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['neo_user_name'] = "" ;
				$this->attribute['neo_comment'] = "" ;
				$this->attribute['neo_ng_word'] = "" ;
				$this->attribute['neo_patrol_check'] = 1 ;
				$this->attribute['neo_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['neo_patrol_id'] = 0 ;
				$this->attribute['neo_admin_check'] = 1 ;
				$this->attribute['neo_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['neo_admin_id'] = 0 ;
				$this->attribute['neo_open_flag'] = 1 ;
				$this->attribute['neo_pv_id'] = 0 ;
			}
}

class l_navi_enq_commentObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('neo_enquete_id','neo_comment_no');
	protected $table = 'l_navi_enq_comment';
	protected $class = 'l_navi_enq_commentObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>