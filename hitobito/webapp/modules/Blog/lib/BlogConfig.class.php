<?php
/**
 * BlogConfig
 *
 * 
 * 
 * @package Blog
 * @author  Ryuji<ryuji@rhym.biz>
 * @version $Id: BlogConfig.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class BlogConfig
{
	private $IndexStoriesNum = 5;
	
	public function setIndexStoriesNum($num)
	{
		$this->IndexStoriesNum = $num;
	}
	
	public function getIndexStoriesNum()
	{
		return $this->IndexStoriesNum;
	}
}
?>