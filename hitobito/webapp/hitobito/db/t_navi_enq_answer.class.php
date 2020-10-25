<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_enq_answer.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_enq_answerObject extends HNbValueObject
{
	protected $pkey = array('nea_enquete_id','nea_option_no');
	protected $table = 't_navi_enq_answer';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nea_enquete_id'] = 0 ;
				$this->attribute['nea_option_no'] = 0 ;
				$this->attribute['nea_answer_count'] = 0 ;
			}
}

class t_navi_enq_answerObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('nea_enquete_id','nea_option_no');
	protected $table = 't_navi_enq_answer';
	protected $class = 't_navi_enq_answerObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>