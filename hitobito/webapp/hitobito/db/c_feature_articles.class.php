<?php
/**
  *
  * @@author Ryuji
  * @version $Id: c_feature_articles.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class c_feature_articlesObject extends HNbValueObject
{
	protected $pkey = 'fac_article_id';
	protected $table = 'c_feature_articles';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['fac_article_id'] = 0 ;
				$this->attribute['fac_click_count'] = 0 ;
			}
}

class c_feature_articlesObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'fac_article_id';
	protected $table = 'c_feature_articles';
	protected $class = 'c_feature_articlesObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>