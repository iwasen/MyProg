<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_login_member.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_login_memberObject extends HNbValueObject
{
	protected $pkey = 'lgm_seq_no';
	protected $table = 'l_login_member';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['lgm_seq_no'] = 0 ;
				$this->attribute['lgm_login_id'] = "" ;
				$this->attribute['lgm_password'] = "" ;
				$this->attribute['lgm_member_id'] = 0 ;
				$this->attribute['lgm_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['lgm_ip_addr'] = "" ;
			}
}

class l_login_memberObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'lgm_seq_no';
	protected $table = 'l_login_member';
	protected $class = 'l_login_memberObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>