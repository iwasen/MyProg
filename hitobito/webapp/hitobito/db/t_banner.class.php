<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_banner.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_bannerObject extends HNbValueObject
{
	protected $pkey = 'bnr_banner_id';
	protected $table = 't_banner';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['bnr_banner_id'] = 0 ;
				$this->attribute['bnr_image_url'] = "" ;
				$this->attribute['bnr_link_url'] = "" ;
			}
}

class t_bannerObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'bnr_banner_id';
	protected $table = 't_banner';
	protected $class = 't_bannerObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>