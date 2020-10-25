<?php
/**
  *
  * @@author Ryuji
  * @version $Id: t_blog_ranking.class.php,v 1.4 2005/12/05 21:29:16 ryu Exp $
  */

require_once MO_WEBAPP_DIR . '/lib/HNbValueObject.class.php';

class t_blog_rankingObject extends HNbValueObject
{
	protected $pkey = 'blr_article_id';
	protected $table = 't_blog_ranking';
	public function __construct()
	{
		parent::__construct();
				$this->attribute['blr_article_id'] = 0 ;
				$this->attribute['blr_channel_id'] = 0 ;
				$this->attribute['blr_index'] = 0 ;
				$this->attribute['blr_page_view'] = 0 ;
				$this->attribute['blr_trackback'] = 0 ;
				$this->attribute['blr_comment'] = 0 ;
			}
}

class t_blog_rankingObjectHandler extends HNbValueObjectHandler
{
	protected $pkey = 'blr_article_id';
	protected $table = 't_blog_ranking';
	protected $class = 't_blog_rankingObject';
	
	public function __construct()
	{
		parent::__construct();
	}
    
}

?>