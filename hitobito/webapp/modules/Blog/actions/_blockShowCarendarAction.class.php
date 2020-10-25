<?php
require_once MO_WEBAPP_DIR.'/pear/Calendar/Month/Weekdays.php';
require_once MO_WEBAPP_DIR.'/pear/Calendar/Day.php';
class _blockShowCarendarAction extends Action
{
    private $year, $month;
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->fetch();
        $Month = new Calendar_Month_Weekdays($this->year, $this->month, 0);

        $Month->build(array (new Calendar_Day(date('Y'), date('m'), date('d'))));
        $request->setAttribute('month', $Month);

        $prev_year = $next_year = $this->year;
        $prev_month = $next_month = $this->month;
        $prev_month --;
        if ($prev_month < 1)
        {
            $prev_month = 12;
            $prev_year --;
        }
        $request->setAttribute('prev_year', $prev_year);
        $request->setAttribute('prev_month', $prev_month);
        
        $next_month ++;
        if ($next_month > 12)
        {
            $next_month = 1;
            $next_year ++;
        }
        $request->setAttribute('next_year', $next_year);
        $request->setAttribute('next_month', $next_month);

		$request->setAttribute('day_list', $this->getDayList());
		
		list($endYear, $endMonth) = $this->getFirstStoryYexxxonth();
		$request->setAttribute('endYear', $endYear);
		$request->setAttribute('endMonth', $endMonth);

        //		$navipage = hitobito::getNaviPageInfo();
        //		$blogId = $navipage->getBlog_id();
        //		$handler = HNb::getDBHandler('blog_category');
        //		
        //		$criteria = new HNbCriteria();
        //		$criteria->setWhere('blog_id='.$blogId);
        //		$criteria->setOrder('ORDER BY category_order ASC');
        //		
        //		$list = $handler->getObjects($criteria);
        //		$request->setAttribute('blog_category_list', $list);
        return View :: SUCCESS;

    }
    private function getFirstStoryYexxxonth()
    {
    	$blogId = hitobito::getNaviPageInfo()->getBlog_id();
    	$sql = "SELECT min(bla_date) FROM t_blog_article" .
    			"  WHERE bla_open_flag=1" .
    			" AND bla_navi_select_flag != 2" .
    			" AND bla_blog_id=" .$blogId  
    			;
    	$db = HNb::getAdodb();
    	if($firstDate = $db->GetOne($sql)){
	    	$unixTime = strtotime($firstDate);
    	}else{
    		$unixTime = time();
    	}
	    return array(date("Y", $unixTime), date("m", $unixTime));
    }
    private function fetch()
    {
        // default
        $this->year = date('Y');
        $this->month = date('m');

        $request = $this->getContext()->getRequest();

        if ($request->hasParameter('blog_year_month'))
        {
            list ($year, $month) = explode('-', $request->getParameter('blog_year_month'));
            $this->year = intval($year);
            $this->month = intval($month);
        }
        if($request->hasParameter('blog_date')){
	        list ($year, $month, $day) = explode('-', $request->getParameter('blog_date'));
	        $this->year = intval($year);
	        $this->month = intval($month);
        }

    }
    private function getDayList()
    {
    	$sql = sprintf("SELECT date_part('day', bla_date) AS day FROM t_blog_article" .
    			" WHERE bla_open_flag=1" .
    			" AND bla_navi_select_flag!=2" .
    			" AND bla_blog_id=%d" .
    			" AND date_part('year', bla_date)=%d" .
    			" AND date_part('month', bla_date)=%d" .
    			" GROUP BY date_part('day', bla_date)",
    			hitobito::getNaviPageInfo()->getBlog_id(),
    			$this->year,
    			$this->month);
    	$db = HNb::getAdodb();
    	$rs = $db->Execute($sql);
    	$dayList = array();
    	if($rs){
    		while($row = $rs->FetchRow()){
    			$dayList[] = $row['day'];
    		}
    	}
    	return $dayList;
    }
}
?>