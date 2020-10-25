<?php

class _blockShowCarendarSuccessView extends SmartyView
{
	public function execute()
	{
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('_blockBlogCarendar.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
		$year_month_options = array();
		if($request->hasParameter('blog_year_month')){
			list($year, $month) = explode('-', $request->getParameter('blog_year_month'));
    		$startYear = $year = intval($year);
    		$startMonth = $month = intval($month);
    		$startMonth++;
    		if($startMonth > 12){
    			$startMonth = 1;
    			$startYear++;
    		}
    		$this->setAttribute('blog_year_month', $request->getParameter('blog_year_month'));
		}else{
			$startYear = date("Y");
			$startMonth = date("m");
		}
//    	$this->setAttribute('year_month_options', $this->getYexxxonthOptions($startYear, $startMonth));
		$endYear = $request->getAttribute('endYear');			
		$endMonth = $request->getAttribute('endMonth');			
    	$this->setAttribute('year_month_options', $this->getYexxxonthOptions($endYear, $endMonth));			
	}
	
	private function getYexxxonthOptions($endYear, $endMonth)
	{
		// 今月から一番古い記事のある年月までのリストに変更
		$ret = array();
		$year = date("Y");
		$month = date("m");
		while(1){
			$ret[$year.'-'.$month] = sprintf(HNb::tr('%04d年%02d月'), $year, $month);
			if($year == $endYear && $month==$endMonth){
				break;
			}
			$month--;
			if($month < 1){
				$month = 12;
				$year--;
			}
			
		}
		return $ret;
//		$ret = array();
//		for($count = 0;$count<4; $count++){
//			$ret[$year.'-'.$month] = sprintf(HNb::tr('%04d年%02d月'), $year, $month);
//			$month--;
//			if($month < 1){
//				$month = 12;
//				$year--;
//			}
//		}
//		return $ret;
	}
}
?>