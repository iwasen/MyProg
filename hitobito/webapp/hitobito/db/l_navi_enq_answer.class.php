<?php
/**
  *
  * @@author Ryuji
  * @version $Id: l_navi_enq_answer.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class l_navi_enq_answerObject extends HNbValueObject
{
	protected $pkey = 'nel_seq_no';
	protected $table = 'l_navi_enq_answer';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nel_seq_no'] = 0 ;
				$this->attribute['nel_enquete_id'] = 0 ;
				$this->attribute['nel_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nel_options'] = "" ;
			}
}

class l_navi_enq_answerObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nel_seq_no';
	protected $table = 'l_navi_enq_answer';
	protected $class = 'l_navi_enq_answerObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>