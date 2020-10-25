<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_tantosha.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_tantoshaObject extends HNbValueObject
{
	protected $pkey = 'tts_tantosha_id';
	protected $table = 'm_tantosha';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['tts_tantosha_id'] = 0 ;
				$this->attribute['tts_company_id'] = 0 ;
				$this->attribute['tts_name1'] = "" ;
				$this->attribute['tts_name2'] = "" ;
				$this->attribute['tts_name1_kana'] = "" ;
				$this->attribute['tts_name2_kana'] = "" ;
				$this->attribute['tts_mail_addr'] = "" ;
				$this->attribute['tts_password'] = "" ;
				$this->attribute['tts_tel'] = "" ;
				$this->attribute['tts_fax'] = "" ;
				$this->attribute['tts_busho'] = "" ;
				$this->attribute['tts_yakushoku'] = "" ;
			}
}

class m_tantoshaObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'tts_tantosha_id';
	protected $table = 'm_tantosha';
	protected $class = 'm_tantoshaObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>