<?php
/**
  *
  * @@author Ryuji
  * @version $Id: pre_confirm_member.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class pre_confirm_memberObject extends HNbValueObject
{
	protected $pkey = 'pre_confirm_member_id';
	protected $table = 'pre_confirm_member';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['pre_confirm_member_id'] = "" ;
				$this->attribute['mail'] = "" ;
				$this->attribute['password'] = "" ;
				$this->attribute['hitobito_letter'] = 0 ;
				$this->attribute['status'] = 0 ;
				$this->attribute['regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['actkey'] = "" ;
			}
}

class pre_confirm_memberObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'pre_confirm_member_id';
	protected $table = 'pre_confirm_member';
	protected $class = 'pre_confirm_memberObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>