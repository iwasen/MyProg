<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_ranking_param.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_ranking_paramObject extends HNbValueObject
{
	protected $pkey = 'brp_param_id';
	protected $table = 't_blog_ranking_param';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['brp_param_id'] = 0 ;
				$this->attribute['brp_page_view'] = 0 ;
				$this->attribute['brp_trackback'] = 0 ;
				$this->attribute['brp_comment'] = 0 ;
				$this->attribute['brp_update_period'] = 0 ;
			}
}

class t_blog_ranking_paramObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'brp_param_id';
	protected $table = 't_blog_ranking_param';
	protected $class = 't_blog_ranking_paramObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>