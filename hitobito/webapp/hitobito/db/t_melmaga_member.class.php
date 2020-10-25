<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_melmaga_member.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_melmaga_memberObject extends HNbValueObject
{
	protected $pkey = array('mmm_melmaga_id','mmm_member_id');
	protected $table = 't_melmaga_member';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mmm_melmaga_id'] = 0 ;
				$this->attribute['mmm_member_id'] = 0 ;
			}
}

class t_melmaga_memberObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('mmm_melmaga_id','mmm_member_id');
	protected $table = 't_melmaga_member';
	protected $class = 't_melmaga_memberObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>