<?php

/**
 * BlogStoryManager
 *
 * 
 * 
 * @package Blog
 * @author  Ryuji<ryuji@rhym.biz>
 * @version $Id: BlogStoryManager.class.php,v 1.16 2006/06/12 07:15:20 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStory.class.php';
require_once MO_WEBAPP_DIR.'/lib/HNbValueObjectHandler.class.php';
class BlogStoryManager extends HNbValueObjectHandler
{
    //    private $db;
    public function __construct()
    {
        parent :: __construct();
    }
    protected $pkey = 'bla_article_id', $table = 't_blog_article', $class = 'BlogStory';

    /**
     * @return	object BlogStory
     */
    public function get($story_id)
    {
        $obj = new BlogStory();
        $obj->setNew();
        if ($story_id > 0)
        {
            if ($row = $this->db->GetRow("SELECT * FROM t_blog_article WHERE bla_article_id=".$story_id))
            {
                $obj->setAttributes($row);
                $obj->unsetNew();
            }
        }
        return $obj;

    }

    public function getStoriesCount($blog_id = 0)
    {
        $base_sql = 'SELECT count(*) FROM t_blog_article WHERE bla_status = 1 ';
        if ($blog_id > 0)
        {
            $sql = $base_sql.sprintf('AND bla_blog_id=%d', $blog_id);
        } else
        {
            $sql = $base_sql;
        }
        $num = $this->db->GetOne($sql);
        return $num;
    }

    public function getStories($blog_id = 0)
    {
        $base_sql = 'SELECT * FROM t_blog_article WHERE bla_status = 1 ';
        $sql = ($blog_id > 0) ? $base_sql.'AND bla_blog_id='.$blog_id : $base_sql;
        $sql .= ' ORDER BY bla_date DESC';
        $rs = $this->db->GetAll($sql);
        //print_r($rs);

        $ret = array ();
        foreach ($rs as $item)
        {
            $obj = new BlogStory();
            $obj->setAttributes($item);
            //            $obj->setTitle($item['bla_title']);
            //            $obj->setBody($item['bla_article_text']);
            $ret[] = $obj;
        }
        return $ret;
    }

    /**
    * @param    BlogStory $story
    * @return   boolian
    */
    public function insert($story)
    {

        if ($story->getId() > 0)
        {
            $result = $this->db->AutoExecute('t_blog_article', $story->getAttributes(), 'UPDATE', 'bla_article_id='.$story->getId());
        } else
        {
            // ページビューカウンタ初期化
            $page_view_id = hitobito :: getNewPageViewId();
            $story->setAttribute('bla_pv_id', $page_view_id);
            // オーダーを自動的に最大にする
            /*
            $sql = "SELECT max(bla_order) FROM t_blog_article WHERE bla_blog_id=".$story->getBlog_id()."";
            $maxOrder = $this->db->GetOne($sql);
            if (empty ($maxOrder))
            {
                $maxOrder = 0;
            }
            $story->setAttribute('bla_order', $maxOrder +1);
            */
            $story->setAttribute('bla_order', 0);
            
            $vars = $story->getAttributes();
            //        	print_r($vars);
            unset ($vars['bla_article_id']);
            $result = $this->db->AutoExecute('t_blog_article', $vars, 'INSERT');
            if (!$result)
            {
                return FALSE;
            }
            $oid = $this->db->Insert_ID();
            $id = $this->db->GetOne("SELECT bla_article_id FROM t_blog_article  WHERE oid=".$oid);
            $story->setId($id);
        }
        return TRUE;
    }

    /**
     * 並び順をあげる
     * 
     * bla_orderの小さいカテゴリとbla_orderを入れ替える
     */
    public function orderUp($obj)
    {
        $this->exchangeOrder($obj, 'up');
        return;
    }

    /**
     * 並び順を下げる
     */
    public function orderDown($obj)
    {
        $this->exchangeOrder($obj, 'down');
        return;
    }

    /**
     * orderUp, orderDownから呼び出す
     * 
     * @param	BlogStory 並び順変更をする記事
     * @param	string	入れ替え対象記事　up or down
     */
    private function exchangeOrder($sourceStory, $targetOrdedr)
    {
        $blog_id = $sourceStory->getAttribute('bla_blog_id');
        $story_order = $sourceStory->getAttribute('bla_order');
        switch ($targetOrdedr)
        {
            case 'down' :
                $sql = sprintf('SELECT bla_article_id FROM %s WHERE bla_blog_id=%d AND bla_order < %d ORDER BY bla_order DESC', $this->table, $blog_id, $story_order);
                break;
            case 'up' :
                $sql = sprintf('SELECT bla_article_id FROM %s WHERE bla_blog_id=%d AND bla_order > %d ORDER BY bla_order ASC', $this->table, $blog_id, $story_order);
                break;
        }

        $exchangeStoryId = $this->db->GetOne($sql);
        if ($exchangeStoryId > 0)
        {
            $exchangeStory = $this->get($exchangeStoryId);
            $exchangeOrder = $exchangeStory->getAttribute('bla_order');
            $sourceOrder = $sourceStory->getAttribute('bla_order');
            $exchangeStory->setAttribute('bla_order', $sourceOrder);
            $sourceStory->setAttribute('bla_order', $exchangeOrder);
            $this->insert($sourceStory);
            $this->insert($exchangeStory);
        }

    }

    /**
     * 人気記事ランキングを返す
     */
    public function getStoryRanking()
    {
        $channel_id = hitobito :: getChannelId();
        // 下記SQLは、admin/site/ranking_article/data.phpよりロジック拝借
        $sql = "SELECT blr_article_id, bla_title, nvp_navi_page_id, nav_name1, nav_name2"." FROM t_blog_ranking"
        	." JOIN t_blog_article ON bla_article_id=blr_article_id"
        	." JOIN t_navi_page ON nvp_blog_id=bla_blog_id"
        	." JOIN m_channel ON chn_channel_id=blr_channel_id"
        	." JOIN t_navi ON nav_navi_id=nvp_navi_id"
        	." WHERE nvp_open_flag=1 AND bla_status=1 AND bla_open_flag=1 AND bla_keisai_flag=1"
        	. ($channel_id ? " AND blr_channel_id=$channel_id" : '')
        	." ORDER BY blr_index DESC"." LIMIT 5";

        $result = $this->db->GetAll($sql);
        //		print_r($result);
        $ranking = array ();
        $rank = 1;
        foreach ($result as $row)
        {
            $ranking[$rank] = array ('story_id' => $row['blr_article_id'], 'title' => $row['bla_title'], 'navipage_id' => $row['nvp_navi_page_id'], 'navi_name' => $row['nav_name1'].$row['nav_name2']);
            $rank ++;
        }
        ksort($ranking);
        return $ranking;

    }
    /**
     * 下記条件を全て満たすBLOGの記事のみを返す
     * ナビページが承認済み＆公開中 nvp_status=1 AND nvp_open_flag=1
     * BLOGステータスが有効 blg_status=1
     * 記事ステータスが有効＆公開フラグが公開　bla_status=1 AND bla_open_flag
     */
    public function getOpenCount($criteria)
    {
    	$sql = $this->getOpenStoriesSql('count(*)');
    	$sql .= " AND ". $criteria->getWhere();
    	return $this->db->GetOne($sql);
    }
    /**
     * 下記条件を全て満たすBLOGの記事のみを返す
     * ナビページが承認済み＆公開中 nvp_status=1 AND nvp_open_flag=1
     * BLOGステータスが有効 blg_status=1
     * 記事ステータスが有効＆公開フラグが公開　bla_status=1 AND bla_open_flag
     */
    public function getOpenStories($criteria)
    {
    	$sql = $this->getOpenStoriesSql('*');
    	$sql .= " AND ".$criteria->render();
        $start = $criteria->getStart();
		$limit = $criteria->getLimit();
        $rs = $this->db->SelectLimit($sql, $limit, $start);
		$ret = array();
		if(!is_object($rs)){
			return $ret;
		}
		$array = $rs->GetRows();
		foreach($array as $vars){
			$obj = new $this->class();
			$obj->setAttributes($vars);
			$ret[] = $obj;
		}
        return $ret;
    	
    }
	private function getOpenStoriesSql($select)
	{
    	$sql = "SELECT ".$select." FROM t_blog_article " .
    			" JOIN t_blog ON blg_blog_id=bla_blog_id" .
    			" JOIN t_navi_page ON blg_blog_id=nvp_blog_id" .
    			" WHERE nvp_status=1 AND nvp_open_flag=1" .
    			" AND blg_status=1" .
    			" AND bla_status=1 AND bla_open_flag=1 AND bla_date < now()" .
    			" AND nvp_open_date < now()";
    	return $sql;
	}
	
	public function getNextStoryId($story, $request)
	{
		$sql = $this->getOpenStoriesSql('bla_article_id');
		$sql .= ' AND bla_blog_id='.$story->getBlog_id();
	    $sql .= ' AND bla_date >= '.$this->db->qstr($story->getAttribute('bla_date'));
	    $sql .= ' AND bla_article_id != '.$story->getId();
	    $sql .= ' ORDER BY bla_date ASC';
		return $this->db->GetOne($sql);
	}
	public function getPrevStoryId($story, $request)
	{
		$sql = $this->getOpenStoriesSql('bla_article_id');
		$sql .= ' AND bla_blog_id='.$story->getBlog_id();
	    $sql .= ' AND bla_date <= '.$this->db->qstr($story->getAttribute('bla_date'));
	    $sql .= ' AND bla_article_id != '.$story->getId();
	    $sql .= ' ORDER BY bla_date DESC';
		return $this->db->GetOne($sql);
	}
}

/**
 * blog_id固定のBlogStoryManager
 * コンストラクタでblog_idを指定する
 */
class BlogOneBlogStoryManager extends BlogStoryManager
{
    private $blog_id;
    public function __construct($blog_id)
    {
        parent :: __construct();
        $this->blog_id = $blog_id;
    }

    public function getStoriesByCategoryId($category_id, $openOnly = FALSE, $limit = -1)
    {
        $where = 'bla_blog_id='.$this->blog_id;
        if ($category_id > 0)
        {
            $where .= ' AND bla_blog_category_id = '.$category_id;
        }
        if ($openOnly)
        {
            $where .= " AND bla_status=1 AND bla_open_flag=1 AND bla_date < now()" ;
        }
        $criteria = new HNbCriteria();
        $criteria->setWhere($where);
        // order
        if($category_id >0){
        	$criteria->setOrder('ORDER BY bla_order ASC, bla_date DESC');
        }else{
        	$criteria->setOrder('ORDER BY bla_date DESC');
        }
        if ($limit > 0)
        {
            $criteria->setLimit($limit);
        }
        return $this->getObjects($criteria);
    }

    /**
     * 最新記事一つだけを返す
     */
    public function getLastStory()
    {

        $list = $this->getStoriesByCategoryId(0, TRUE, 1); 
        if(count($list)){
        	return $list[0];
        }
    }

}

?>