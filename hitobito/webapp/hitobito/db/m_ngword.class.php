<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_ngword.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_ngwordObject extends HNbValueObject
{
	protected $pkey = 'ngw_ngword_id';
	protected $table = 'm_ngword';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['ngw_ngword_id'] = 0 ;
				$this->attribute['ngw_status'] = 1 ;
				$this->attribute['ngw_word'] = "" ;
			}
}

class m_ngwordObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'ngw_ngword_id';
	protected $table = 'm_ngword';
	protected $class = 'm_ngwordObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>