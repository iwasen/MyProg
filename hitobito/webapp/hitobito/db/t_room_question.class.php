<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_room_question.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_room_questionObject extends HNbValueObject
{
	protected $pkey = 'rmq_seq_no';
	protected $table = 't_room_question';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['rmq_seq_no'] = 0 ;
				$this->attribute['rmq_room_id'] = 0 ;
				$this->attribute['rmq_status'] = 0 ;
				$this->attribute['rmq_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['rmq_mail_addr'] = "" ;
				$this->attribute['rmq_nickname'] = "" ;
				$this->attribute['rmq_title'] = "" ;
				$this->attribute['rmq_content'] = "" ;
			}
}

class t_room_questionObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'rmq_seq_no';
	protected $table = 't_room_question';
	protected $class = 't_room_questionObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>