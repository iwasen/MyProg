<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_hitobito_letter_choice.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_hitobito_letter_choiceObject extends HNbValueObject
{
	protected $pkey = array('hlo_letter_id','hlo_option_no');
	protected $table = 'c_hitobito_letter_choice';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['hlo_letter_id'] = 0 ;
				$this->attribute['hlo_option_no'] = 0 ;
				$this->attribute['hlo_count'] = 0 ;
			}
}

class c_hitobito_letter_choiceObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('hlo_letter_id','hlo_option_no');
	protected $table = 'c_hitobito_letter_choice';
	protected $class = 'c_hitobito_letter_choiceObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>