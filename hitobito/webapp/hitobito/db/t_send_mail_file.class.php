<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_send_mail_file.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_send_mail_fileObject extends HNbValueObject
{
	protected $pkey = array('sf_send_mail_id','sf_file_id');
	protected $table = 't_send_mail_file';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['sf_send_mail_id'] = 0 ;
				$this->attribute['sf_file_id'] = 0 ;
				$this->attribute['sf_file_name'] = "" ;
				$this->attribute['sf_content_id'] = "" ;
				$this->attribute['sf_file_content'] = "" ;
			}
}

class t_send_mail_fileObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = array('sf_send_mail_id','sf_file_id');
	protected $table = 't_send_mail_file';
	protected $class = 't_send_mail_fileObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>