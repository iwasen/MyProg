<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_answer.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_answerObject extends HNbValueObject
{
	protected $pkey = array('nva_inquiry_id','nva_seq_no');
	protected $table = 't_navi_answer';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nva_inquiry_id'] = 0 ;
				$this->attribute['nva_seq_no'] = 0 ;
				$this->attribute['nva_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['nva_mail_addr'] = "" ;
				$this->attribute['nva_subject'] = "" ;
				$this->attribute['nva_content'] = "" ;
			}
}

class t_navi_answerObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('nva_inquiry_id','nva_seq_no');
	protected $table = 't_navi_answer';
	protected $class = 't_navi_answerObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>