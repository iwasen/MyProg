<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_hitobito_letter_option.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_hitobito_letter_optionObject extends HNbValueObject
{
	protected $pkey = array('hlo_letter_id','hlo_option_no');
	protected $table = 't_hitobito_letter_option';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['hlo_letter_id'] = 0 ;
				$this->attribute['hlo_option_no'] = 0 ;
				$this->attribute['hlo_option_text'] = "" ;
			}
}

class t_hitobito_letter_optionObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('hlo_letter_id','hlo_option_no');
	protected $table = 't_hitobito_letter_option';
	protected $class = 't_hitobito_letter_optionObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>