<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_feature_articles.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_feature_articlesObject extends HNbValueObject
{
	protected $pkey = 'far_article_id';
	protected $table = 't_feature_articles';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['far_article_id'] = 0 ;
				$this->attribute['far_status'] = 0 ;
				$this->attribute['far_regist_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['far_update_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['far_order'] = 0 ;
				$this->attribute['far_image_id'] = 0 ;
				$this->attribute['far_image_alt'] = "" ;
				$this->attribute['far_link_url'] = "" ;
				$this->attribute['far_link_page'] = 0 ;
				$this->attribute['far_start_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['far_end_date'] = date("Y-m-d H:i:s") ;
				$this->attribute['far_description'] = "" ;
			}
}

class t_feature_articlesObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'far_article_id';
	protected $table = 't_feature_articles';
	protected $class = 't_feature_articlesObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>