<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_navi_ranking_param.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_navi_ranking_paramObject extends HNbValueObject
{
	protected $pkey = 'nrp_param_id';
	protected $table = 't_navi_ranking_param';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['nrp_param_id'] = 0 ;
				$this->attribute['nrp_ml_member_num'] = 0 ;
				$this->attribute['nrp_ml_member_up'] = 0 ;
				$this->attribute['nrp_ml_total_remark'] = 0 ;
				$this->attribute['nrp_ml_page_view'] = 0 ;
				$this->attribute['nrp_ml_member_remark'] = 0 ;
				$this->attribute['nrp_blog_new_post'] = 0 ;
				$this->attribute['nrp_blog_page_view'] = 0 ;
				$this->attribute['nrp_blog_trackback'] = 0 ;
				$this->attribute['nrp_blog_comment'] = 0 ;
				$this->attribute['nrp_update_mode'] = 0 ;
			}
}

class t_navi_ranking_paramObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'nrp_param_id';
	protected $table = 't_navi_ranking_param';
	protected $class = 't_navi_ranking_paramObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>