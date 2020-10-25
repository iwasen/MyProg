<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_send_mail_embed.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_send_mail_embedObject extends HNbValueObject
{
	protected $pkey = array('se_send_mail_id','se_sub_seq','se_variable_name');
	protected $table = 't_send_mail_embed';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['se_send_mail_id'] = 0 ;
				$this->attribute['se_sub_seq'] = 0 ;
				$this->attribute['se_variable_name'] = 0 ;
				$this->attribute['se_embed_text'] = "" ;
			}
}

class t_send_mail_embedObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('se_send_mail_id','se_sub_seq','se_variable_name');
	protected $table = 't_send_mail_embed';
	protected $class = 't_send_mail_embedObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>