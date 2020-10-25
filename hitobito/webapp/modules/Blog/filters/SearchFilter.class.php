<?php
/**
 *
 *
 * 
 * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: SearchFilter.class.php,v 1.3 2006/01/19 06:44:19 ryu Exp $
 */

require_once MO_WEBAPP_DIR .'/lib/HNbActionFilter.class.php';
class BlogSearchFilter extends HNbActionFilter
{
//	private $searchWordList = array();
//	private $searchWord;
	public function fetch($context)
	{
		$this->context = $context;
		$request = $context->getRequest();
		$this->addParameter('search_word', $request->getParameter('search_word'));
		// channel
		if($request->hasParameter('category_id')){
			$this->addParameter('category_id', intval($request->getParameter('category_id')));
		}
		// navipage		
		if($request->hasParameter('navipage_id')){
			$this->addParameter('navipage_id', intval($request->getParameter('navipage_id')));
		}
	}
	public function getCriteria()
	{
		$criteria = $this->getDefaultCriteria();
		if($this->hasParameter('search_word')){
			$searchWordList = preg_split('/( |¡¡)/', $this->getParameter('search_word'));
			foreach($searchWordList as $word){
				$word = addslashes($word);
				$criteria->addWhere("(bla_title LIKE '%".$word."%' OR bla_article_text LIKE '%".$word."%')");
			}
		}
		if($this->hasParameter('category_id')){
			$criteria->addWhere('nvp_channel_id='.$this->getParameter('category_id'));
		}
		if($this->hasParameter('navipage_id')){
			$criteria->addWhere('nvp_navi_page_id='.$this->getParameter('navipage_id'));
		}
		return $criteria;
	}
	
	protected function getDefaultCriteria()
	{
		$criteria = new HNbCriteria();
		$criteria->setWhere('bla_navi_select_flag < 2');
		$criteria->setOrder("ORDER BY bla_date DESC");
		return $criteria;
	}

}
?>