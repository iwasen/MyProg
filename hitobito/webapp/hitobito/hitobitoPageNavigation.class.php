<?php
// TODO http://pear.php.net/manual/ja/package.html.pager.compare.php

define('HNB_MAX_PAGE_NAVIGATION', 15);
class hitobitoPageNavigation
{
	public	$total,
			$start,
			$limit,
			$start_parameter_name,
			$extra_parameter = array();
	private $maxPageNavigation = HNB_MAX_PAGE_NAVIGATION;
	private $delimiter = '...';
	
	public function __construct($total, $limit, $start, $start_parameter_name)
	{
		$this->total = $total;
		$this->limit = $limit;
		$this->start = $start;
		$this->start_parameter_name = $start_parameter_name;
	}
	public function addParameter($key, $var)
	{
		$this->extra_parameter[$key] = $var;
	}
	public function addParameters($values)
	{
		if(count($values)){
			foreach($values as $key=>$var){
				$this->addParameter($key, $var);
			}
		}
	}
	protected function getPageLink($page_counter)
	{
		if($page_counter == $this->getCurrentPage()){
			return $page_counter;
		}else{
			return sprintf('<a href="index.php?%s=%d&amp;%s">%d</a>',
				$this->start_parameter_name,
				($page_counter -1) * $this->limit,
				$this->getParameter(),
				$page_counter);
		}
	}
	public function getListNavigation()
	{
		if($this->total <= $this->limit){
			return '';
		}
		$total_page = ceil($this->total / $this->limit);
		
		$current_page = $this->getCurrentPage();
		$page_counter = 1;
		$page_list = array();
		// 最小ページナンバーを得る(カレントページから$maxPageNavigation/2 を引いたのが最小。0以下だったら1にする)
		$minPageNumber = $current_page - intval($this->maxPageNavigation/2);
		$minPageNumber = ($minPageNumber > 0) ? $minPageNumber : 1;
		
		// 最大ページナンバーを得る
		$maxPageNumber = $minPageNumber + ($this->maxPageNavigation -1);
		if($maxPageNumber > $total_page){
		    $maxPageNumber = $total_page;
		    $minPageNumber = $maxPageNumber - ($this->maxPageNavigation -1);
    		$minPageNumber = ($minPageNumber > 0) ? $minPageNumber : 1;
		}
		// 最小から最大までの配列を作成する
		$require_pages = range($minPageNumber, $maxPageNumber);
		// 最小ページナンバーが最初と違ったら最初を頭に足す
		if($minPageNumber != 1){
		    array_unshift($require_pages, 1);
		}
		// 最大ページナンバーが最後と違ったら最後をケツに足す
		if($maxPageNumber != $total_page){
		    $require_pages[] = $total_page;
		}
		foreach($require_pages as $page_counter){
		    $page_list[$page_counter] = $this->getPageLink($page_counter);
		}
		// 最初と最小の間にページが存在するならデリミタ挿入
		$firstNext = 2;
		if(!isset($page_list[$firstNext])){
		    $page_list[$firstNext] = $this->delimiter;
		}
		
		// 最後と最大の間にページが存在するならデリミタ挿入
   		$lastPrev = $total_page -1;
        if(!isset($page_list[$lastPrev]))	{
            $page_list[$lastPrev] = $this->delimiter;
        }	
        // キーでソート
        ksort($page_list);
		$list_navigation  = implode('&nbsp;', $page_list);
		return $list_navigation;
	}
	
	public function getMoveNavigation()
	{
		if($this->total <= $this->limit){
			return '';
		}
		$total_page = ceil($this->total / $this->limit);
		
		$current_page = $this->getCurrentPage();
		$prev ='';
		$next ='';
		if($current_page > 1){
			//prev
			$prev = sprintf('<img src="image/search_btn_pre_select.gif" width="12" height="12" align="absmiddle" hspace="2"><span class="char120"><a href="index.php?%s=%d&amp;%s" class="cblack">前へ</a></span>',
				$this->start_parameter_name,
				($current_page -2) * $this->limit,
				$this->getParameter()
				);
		}
		if($current_page < $total_page){
			// next
			$next = sprintf('<img src="image/search_btn_next_select.gif" width="12" height="12" align="absmiddle" hspace="2"><span class="char120"><a href="index.php?%s=%d&amp;%s" class="cblack">次へ</a></span>',
				$this->start_parameter_name,
				($current_page) * $this->limit,
				$this->getParameter()
				);
		}
		if($prev && $next){
			return  $prev .'<span class="char999999"> | </span>'.$next;
		}else{
			return $prev.$next;
		}
	}
	
	public function getCurrentPage()
	{
		$current_page = intval(floor(($this->start + $this->limit) / $this->limit));
		return $current_page;
	}
	private function getParameter()
	{
		$params = array();
		foreach($this->extra_parameter as $name => $var){
			$params[] = $name.'='.urlencode($var);
		}
		return implode('&amp;', $params);
	}
}
?>