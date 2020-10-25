<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_enquete.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_enqueteObject extends HNbValueObject
{
	protected $pkey = 'nen_enquete_id';
	protected $table = 't_navi_enquete';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nen_enquete_id'] = 0 ;
				$this->attribute['nen_status'] = 1 ;
				$this->attribute['nen_navi_page_id'] = 0 ;
				$this->attribute['nen_title'] = "" ;
				$this->attribute['nen_question_text'] = "" ;
				$this->attribute['nen_question_type'] = 0 ;
				$this->attribute['nen_comment_flag'] = 0 ;
				$this->attribute['nen_trackback_flag'] = 0 ;
				$this->attribute['nen_start_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nen_end_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nen_order'] = 0 ;
				$this->attribute['nen_ng_word'] = "" ;
				$this->attribute['nen_patrol_check'] = 1 ;
				$this->attribute['nen_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nen_patrol_id'] = 0 ;
				$this->attribute['nen_admin_check'] = 1 ;
				$this->attribute['nen_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nen_admin_id'] = 0 ;
				$this->attribute['nen_open_flag'] = 1 ;
				$this->attribute['nen_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nen_communication_msg'] = "" ;
				$this->attribute['nen_pickup'] = 0 ;
				$this->attribute['nen_pv_id'] = 0 ;
				$this->attribute['nen_update_date'] = date("Y-m-d H:i:s") ;
			}
}

class t_navi_enqueteObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nen_enquete_id';
	protected $table = 't_navi_enquete';
	protected $class = 't_navi_enqueteObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>