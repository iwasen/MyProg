<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_project.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_projectObject extends HNbValueObject
{
	protected $pkey = 'pjt_project_id';
	protected $table = 't_project';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['pjt_project_id'] = 0 ;
				$this->attribute['pjt_status'] = 1 ;
				$this->attribute['pjt_project_name'] = "" ;
				$this->attribute['pjt_tantosha_id'] = 0 ;
				$this->attribute['pjt_admin_id'] = 0 ;
				$this->attribute['pjt_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['pjt_outline'] = "" ;
			}
}

class t_projectObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'pjt_project_id';
	protected $table = 't_project';
	protected $class = 't_projectObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>