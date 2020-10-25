<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_hitobito_letter_count.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_hitobito_letter_countObject extends HNbValueObject
{
	protected $pkey = 'hlc_letter_id';
	protected $table = 'c_hitobito_letter_count';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['hlc_letter_id'] = 0 ;
				$this->attribute['hlc_answer_count'] = 0 ;
				$this->attribute['hlc_click_count'] = 0 ;
			}
}

class c_hitobito_letter_countObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'hlc_letter_id';
	protected $table = 'c_hitobito_letter_count';
	protected $class = 'c_hitobito_letter_countObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>