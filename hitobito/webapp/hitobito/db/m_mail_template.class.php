<?php
/**
  *
  * @@author Ryuji
  * @version $Id: m_mail_template.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class m_mail_templateObject extends HNbValueObject
{
	protected $pkey = 'mtm_template_id';
	protected $table = 'm_mail_template';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['mtm_template_id'] = 0 ;
				$this->attribute['mtm_guide'] = "" ;
				$this->attribute['mtm_subject'] = "" ;
				$this->attribute['mtm_from'] = "" ;
				$this->attribute['mtm_cc'] = "" ;
				$this->attribute['mtm_bcc'] = "" ;
				$this->attribute['mtm_reply_to'] = "" ;
				$this->attribute['mtm_body'] = "" ;
			}
}

class m_mail_templateObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'mtm_template_id';
	protected $table = 'm_mail_template';
	protected $class = 'm_mail_templateObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>