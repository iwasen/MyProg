<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_schedule.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_scheduleObject extends HNbValueObject
{
	protected $pkey = 'sch_date';
	protected $table = 't_schedule';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sch_date'] = "" ;
				$this->attribute['sch_blog_total'] = 0 ;
				$this->attribute['sch_blog_nocheck'] = 0 ;
				$this->attribute['sch_blog_problem'] = 0 ;
				$this->attribute['sch_blog_pickup'] = 0 ;
				$this->attribute['sch_ml_total'] = 0 ;
				$this->attribute['sch_ml_nocheck'] = 0 ;
				$this->attribute['sch_ml_problem'] = 0 ;
				$this->attribute['sch_ml_pickup'] = 0 ;
				$this->attribute['sch_melmaga_total'] = 0 ;
				$this->attribute['sch_melmaga_nocheck'] = 0 ;
				$this->attribute['sch_melmaga_problem'] = 0 ;
				$this->attribute['sch_melmaga_pickup'] = 0 ;
				$this->attribute['sch_enquete_total'] = 0 ;
				$this->attribute['sch_enquete_nocheck'] = 0 ;
				$this->attribute['sch_enquete_problem'] = 0 ;
				$this->attribute['sch_enquete_pickup'] = 0 ;
				$this->attribute['sch_link_total'] = 0 ;
				$this->attribute['sch_link_nocheck'] = 0 ;
				$this->attribute['sch_link_problem'] = 0 ;
				$this->attribute['sch_link_pickup'] = 0 ;
			}
}

class t_scheduleObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'sch_date';
	protected $table = 't_schedule';
	protected $class = 't_scheduleObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>