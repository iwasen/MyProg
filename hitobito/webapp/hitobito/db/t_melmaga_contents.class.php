<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_melmaga_contents.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_melmaga_contentsObject extends HNbValueObject
{
	protected $pkey = 'mmc_melmaga_contents_id';
	protected $table = 't_melmaga_contents';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmc_melmaga_contents_id'] = 0 ;
				$this->attribute['mmc_melmaga_id'] = 0 ;
				$this->attribute['mmc_status'] = 0 ;
				$this->attribute['mmc_send_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmc_subject'] = "" ;
				$this->attribute['mmc_body'] = "" ;
				$this->attribute['mmc_footer'] = "" ;
				$this->attribute['mmc_comment_flag'] = 1 ;
				$this->attribute['mmc_ng_word'] = "" ;
				$this->attribute['mmc_patrol_check'] = 1 ;
				$this->attribute['mmc_patrol_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmc_patrol_id'] = 0 ;
				$this->attribute['mmc_admin_check'] = 1 ;
				$this->attribute['mmc_admin_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmc_admin_id'] = 0 ;
				$this->attribute['mmc_open_flag'] = 1 ;
				$this->attribute['mmc_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['mmc_communication_msg'] = "" ;
				$this->attribute['mmc_pickup'] = 0 ;
				$this->attribute['mmc_send_num'] = 0 ;
				$this->attribute['mmc_pv_id'] = 0 ;
			}
}

class t_melmaga_contentsObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'mmc_melmaga_contents_id';
	protected $table = 't_melmaga_contents';
	protected $class = 't_melmaga_contentsObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>