<?php

/**
 * 
 *
 *  
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexAction.class.php,v 1.16 2006/06/12 07:15:20 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogBaseAction.class.php';

class IndexAction extends BlogBaseAction
{
    protected $category_id;
    public $limit = 10;

    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        if(hitobito::getNaviPageInfo()->getId()){
	
			// navipage 内
	        if ($request->hasParameter('blog_date'))
	        {
	            $action = new BlogShowStoriesByDate($this->getContext());
	        }
	        elseif ($request->hasParameter('blog_year_month'))
	        {
	            $action = new BlogShowStoriesByYexxxonth($this->getContext());
	        } else
	        {
	            $action = new BlogShowStoriesDefault($this->getContext());

	        }
	                
	    }else{
	    	// navipage 外
	    	$action = new BlogShowStoriesByCategory($this->getContext());
        }

        $manager = new BlogStoryManager();

        $action->fetch();


        $criteria = $action->getCriteria();
        
        $stories = $manager->getOpenStories($criteria);

        $request->setAttribute('stories', $stories);

        $request->setAttribute('listTitle', $action->getListTitle());

        $request->setAttribute('navigation', $action->getNavigation());
        return View :: SUCCESS;
    }

    public function isSecure()
    {
        return false;
    }

 
}
abstract class BlogShowStories
{
    protected $start;
    protected $limit = 10;
    protected $blog_id;

    private $context;
    public function __construct($context)
    {
        $this->context = $context;
    }
    protected function getContext()
    {
        return $this->context;
    }
    protected function fetch()
    {
        $this->blog_id = hitobito :: getNaviPageInfo()->getBlog_id();
    }
    protected function getBaseWhereList()
    {
        $whereList = array ();
        $whereList[] = 'bla_open_flag=1';
        $whereList[] = 'bla_navi_select_flag!=2';
        if ($this->blog_id > 0)
        {
            $whereList[] = 'bla_blog_id='.$this->blog_id;
        }
        return $whereList;
    }
}


/**
 * default BLOGカテゴリ内では並び順表示
 * それ以外は最新順表示
 */
class BlogShowStoriesDefault extends BlogShowStories
{
    private $category_id;

    public function fetch()
    {
        parent :: fetch();
        $request = $this->getContext()->getRequest();
        $this->category_id = intval($request->getParameter('blog_category_id'));

        $this->start = intval($request->getParameter('blog_list_start', 0));
    }
    public function getCriteria()
    {

        $criteria = new HNbCriteria();
        $criteria->setLimit($this->limit);
        $criteria->setStart($this->start);
        
        if($this->category_id > 0){
        	$criteria->setOrder('ORDER BY bla_order ASC, bla_date DESC');
        }else{
        	$criteria->setOrder('ORDER BY bla_date DESC');
        }

		$whereList = $this->getBaseWhereList();
        if ($this->category_id > 0)
        {
            $whereList[] = 'bla_blog_category_id='.$this->category_id;
        }

        $where = implode(' AND ', $whereList);
        $criteria->setWhere($where);

        return $criteria;
    }
    public function getListTitle()
    {
        return '';
    }
    public function getNavigation()
    {
    	
        $baseUrl = 'index.php?module=Blog&amp;action=Index';
        if(hitobito::getNaviPageInfo()->getId()>0){
			$baseUrl .='&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        }
        if($this->category_id > 0){
        	$baseUrl .='&amp;blog_category_id='.$this->category_id;
        }
	    $manager = new BlogStoryManager();
	    $total = $manager->getOpenCount($this->getCriteria());
	    
		if(($this->start + $this->limit) < $total ){
        	$prev = array ('url' => $baseUrl.'&amp;blog_list_start='. ($this->start + $this->limit), 'text' => HNb :: tr('前の記事'));
		}else{
            $prev = array ('url' => '', 'text' => '');
		}
        if (($this->start - $this->limit) >= 0)
        {
            $next = array ('url' => $baseUrl.'&amp;blog_list_start='. ($this->start - $this->limit), 'text' => HNb :: tr('次の記事'));
        } else
        {
            $next = array ('url' => '', 'text' => '');
        }
        return array ('prev' => $prev, 'next' => $next);
    }

}
class BlogShowStoriesByYexxxonth extends BlogShowStories
{
    private $year, $month;
    public function fetch()
    {
        parent :: fetch();
        $request = $this->getContext()->getRequest();

        list ($year, $month) = explode('-', $request->getParameter('blog_year_month'));
        $this->year = intval($year);
        $this->month = intval($month);

    }
    public function getCriteria()
    {

        $whereList = $this->getBaseWhereList();
        
        $whereList[] = "date_part('year', bla_date) = ".$this->year." AND date_part('month', bla_date) = ".$this->month;
        $criteria = new HNbCriteria();
        $criteria->setLimit(-1);
        $criteria->setStart(0);
        $criteria->setOrder('ORDER BY bla_date DESC');

        $where = implode(' AND ', $whereList);
        $criteria->setWhere($where);

        return $criteria;
    }
    public function getListTitle()
    {
        return sprintf(HNb :: tr('%d年%d月の記事'), $this->year, $this->month);

    }
    public function getNavigation()
    {
        $baseUrl = 'index.php?module=Blog&amp;action=Index&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
            // 年月モード
            $prev_year = $next_year = $this->year;
            $prev_month = $next_month = $this->month;
            $prev_month --;
            if ($prev_month < 1)
            {
                $prev_month = 12;
                $prev_year --;
            }
            $next_month ++;
            if ($next_month > 12)
            {
                $next_month = 1;
                $next_year ++;
            }
            $prev = array ('url' => $baseUrl.'&amp;blog_year_month='.$prev_year.'-'.$prev_month, 'text' => HNb :: tr('前の月'));
            $next = array ('url' => $baseUrl.'&amp;blog_year_month='.$next_year.'-'.$next_month, 'text' => HNb :: tr('次の月'));
        return array ('prev' => $prev, 'next' => $next);
    }
}
class BlogShowStoriesByDate extends BlogShowStories
{
    private $year, $month, $day;
    public function fetch()
    {
        parent :: fetch();
        $request = $this->getContext()->getRequest();
        list ($year, $month, $day) = explode('-', $request->getParameter('blog_date'));
        $this->year = intval($year);
        $this->month = intval($month);
        $this->day = intval($day);
    }
    public function getListTitle()
    {
        return sprintf(HNb :: tr('%d年%d月%d日の記事'), $this->year, $this->month, $this->day);
    }
	public function getCriteria()
	{
        $whereList = $this->getBaseWhereList();
        
        $whereList[] = sprintf("date_part('year', bla_date) = %d AND date_part('month', bla_date) = %d AND date_part('day', bla_date)=%d",
        	$this->year,
        	$this->month,
        	$this->day);
        $criteria = new HNbCriteria();
        $criteria->setLimit(-1);
        $criteria->setStart(0);
        $criteria->setOrder('ORDER BY bla_date DESC');

        $where = implode(' AND ', $whereList);
        $criteria->setWhere($where);

        return $criteria;
	}
	public function getNavigation()
	{
        $baseUrl = 'index.php?module=Blog&amp;action=Index&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
		$db = HNb::getAdodb();
		$prevWhereList = $this->getBaseWhereList();
		$prevWhereList[] = 'bla_date < '.$db->qstr($this->year.'-'.$this->month.'-'.$this->day.' 00:00:00');
		$sql = "SELECT max(bla_date) FROM t_blog_article WHERE ".implode(' AND ', $prevWhereList) ;
		if($date = $db->GetOne($sql)){
			$prev = array('url' => $baseUrl.'&amp;blog_date='.date("Y-m-d", strtotime($date)), 'text' => HNb::tr('前の日付'));
		}else{
			$prev = array('url'=> '', 'text'=>'');
		}
		$nextWhereList = $this->getBaseWhereList();
		$nextWhereList[] = 'bla_date > '.$db->qstr($this->year.'-'.$this->month.'-'.$this->day.' 23:59:59');
		$sql = "SELECT min(bla_date) FROM t_blog_article WHERE ".implode(' AND ', $nextWhereList);
		if($date = $db->GetOne($sql)){
			$next = array('url' => $baseUrl.'&amp;blog_date='.date("Y-m-d", strtotime($date)), 'text' => HNb::tr('次の日付'));
		}else{
			$next = array('url'=> '', 'text'=>'');
		}
		return array('prev' => $prev, 'next'=> $next);
	}
}

class BlogShowStoriesByCategory extends BlogShowStories
{
	private $channelId;
    public function fetch()
    {
        parent :: fetch();
        $request = $this->getContext()->getRequest();
        $this->channelId = intval($request->getParameter('category_id'));
        $this->start = intval($request->getParameter('blog_list_start', 0));
    }
    public function getListTitle()
    {
        return '';
    }
	public function getCriteria()
	{
        $whereList = $this->getBaseWhereList();
        $criteria = new HNbCriteria();
        $criteria->setLimit($this->limit);
        $criteria->setStart($this->start);
        $criteria->setOrder('ORDER BY bla_date DESC');

        $where = implode(' AND ', $whereList);
        $criteria->setWhere($where);
        
        if($this->channelId){
        	$criteria->addWhere('nvp_channel_id='.$this->channelId);
        }

        return $criteria;
	}
	public function getNavigation()
	{
        $baseUrl = 'index.php?module=Blog&amp;action=Index';
        if(hitobito::getNaviPageInfo()->getId()>0){
			$baseUrl .='&amp;navipage_id='.hitobito::getNaviPageInfo()->getId();
        }
        if($this->channelId > 0){
        	$baseUrl .='&amp;category_id='.$this->channelId;
        }
	    $manager = new BlogStoryManager();
	    $total = $manager->getOpenCount($this->getCriteria());
	    
		if(($this->start + $this->limit) < $total ){
        	$prev = array ('url' => $baseUrl.'&amp;blog_list_start='. ($this->start + $this->limit), 'text' => HNb :: tr('前の記事'));
		}else{
            $prev = array ('url' => '', 'text' => '');
		}
        if (($this->start - $this->limit) >= 0)
        {
            $next = array ('url' => $baseUrl.'&amp;blog_list_start='. ($this->start - $this->limit), 'text' => HNb :: tr('次の記事'));
        } else
        {
            $next = array ('url' => '', 'text' => '');
        }
        return array ('prev' => $prev, 'next' => $next);
	}
}

?>