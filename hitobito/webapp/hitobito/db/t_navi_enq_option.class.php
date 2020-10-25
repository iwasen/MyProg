<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_enq_option.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_enq_optionObject extends HNbValueObject
{
	protected $pkey = array('neo_enquete_id','neo_option_no');
	protected $table = 't_navi_enq_option';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['neo_enquete_id'] = 0 ;
				$this->attribute['neo_option_no'] = 0 ;
				$this->attribute['neo_option_text'] = "" ;
			}
}

class t_navi_enq_optionObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('neo_enquete_id','neo_option_no');
	protected $table = 't_navi_enq_option';
	protected $class = 't_navi_enq_optionObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>