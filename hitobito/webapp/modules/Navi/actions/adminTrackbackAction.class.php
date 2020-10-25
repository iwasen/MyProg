<?php
/**
 * トラックバック管理　ステータス変更、削除
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminTrackbackAction.class.php,v 1.2 2006/01/11 13:53:13 ryu Exp $
 */

class adminTrackbackAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
		$item_id = ($request->getParameter('navi_item_id'));
		list($parentContentsId, $trackbackNo) = explode('-', $item_id);
		$listTarget = $request->getParameter('navi_list_target', 'blog');
		
		if($parentContentsId > 0 && $trackbackNo > 0){
			if($request->hasParameter('navi_modify_item_x')){
				$url = 'index.php?module=Navi&action=modifyTrackback&navi_modify_target='.$listTarget.'&navipage_id='.hitobito::getNaviPageInfo()->getId().'&navi_item_id='.$item_id;
				$controller->redirect($url);
			}
			if($request->hasParameter('navi_delete_item_x')){
				switch($listTarget){
					case 'melmaga':
						$deleteSql = "DELETE FROM l_melmaga_trackback WHERE mmt_melmaga_contents_id=".$parentContentsId." AND mmt_trackback_no=".$trackbackNo;
						break;
					case 'enquete':
						$deleteSql = "DELETE FROM l_navi_enq_trackback WHERE net_enquete_id=".$parentContentsId." AND net_trackback_no=".$trackbackNo;
						break;
					case 'blog':
					default:
						$deleteSql = "DELETE FROM t_blog_trackback WHERE blt_article_id=".$parentContentsId." AND blt_trackback_no=".$trackbackNo;
						break;
				}
				$db = HNb::getAdodb();
				if($db->Execute($deleteSql)){
					$request->setAttribute('message', HNb::tr('削除しました'));
					return View::SUCCESS;
				}else{
					$request->setAttribute('message', HNb::tr('削除失敗'));
					return View::ERROR;
				}
			}
			if($request->hasParameter('navi_close_item_x')){
				switch($listTarget){
					case 'melmaga':
						$updateSql = "UPDATE l_melmaga_trackback SET mmt_open_flag=0 WHERE mmt_melmaga_contents_id=".$parentContentsId." AND mmt_trackback_no=".$trackbackNo;
						break;
					case 'enquete':
						$updateSql = "UPDATE l_navi_enq_trackback SET net_open_flag=0 WHERE net_enquete_id=".$parentContentsId." AND net_trackback_no=".$trackbackNo;
						break;
					case 'blog':
					default:
						$updateSql = "UPDATE t_blog_trackback SET blt_open_flag=0 WHERE blt_article_id=".$parentContentsId." AND blt_trackback_no=".$trackbackNo;
						break;
				}
				$db = HNb::getAdodb();
				if($db->Execute($updateSql)){
					$request->setAttribute('message', HNb::tr('非公開にしました'));
					return View::SUCCESS;
				}else{
					$request->setAttribute('message', HNb::tr('ステータス変更失敗'));
					return View::ERROR;
				}
			}
		}
		return $this->getDefaultView();
    }


    public function getDefaultView ()
    {
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
		 $request =$this->getContext()->getRequest();
		 $navipage_id = hitobito::getNaviPageInfo()->getId();
		switch($request->getParameter('navi_list_target')){
			case 'melmaga':
		 		$list = new MelmagaTrackbackList($this->getContext());
		 		$request->setAttribute('view_url', 'index.php?module=MailMag&amp;action=BacknumberContents&amp;navipage_id='.$navipage_id.'&amp;contents_id=');
		 		$request->setAttribute('list_target', 'melmaga');
				break;
			case 'enquete':
		 		$list = new EnqueteTrackbackList($this->getContext());
		 		$request->setAttribute('view_url', 'index.php?module=Enquete&amp;action=ShowResult&amp;navipage_id='.$navipage_id.'&amp;enquete_id=');
		 		$request->setAttribute('list_target', 'enquete');
				break;
			case 'blog':
			default:
		 		$list = new BlogTrackbackList($this->getContext());
		 		$request->setAttribute('view_url', 'index.php?module=Blog&amp;action=ViewStory&amp;blog_story_id=');
		 		$request->setAttribute('list_target', 'blog');
		}
 		$list->execute();
        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }

    public function isSecure()
    {
        return true;
    }
}

abstract class NaviPageTrackback
{
	protected $attributes = array();
	abstract public function getParentContentsId();
	abstract public function getTrackbackType();
	public function setAttributes($row)
	{
		foreach($row as $key => $var){
			$nonePrefixKey = substr($key, 4);
			$this->attributes[$nonePrefixKey] = $var;
		}
	}
	public function getTitle()
	{
		return $this->attributes['title'];
	}
	public function getShortText()
	{
		return mb_substr(strip_tags($this->attributes['excerpt']),0, 20);
	}
	public function getDate()
	{
		return $this->attributes['date'];
	}
	public function getStatus()
	{
		return $this->attributes['open_flag'];
	}
	public function getTrackbackNo()
	{
		return $this->attributes['trackback_no'];
	}
}
class NaviPageBlogTrackback extends NaviPageTrackback
{
	public function getTrackbackType()
	{
		return HNb::tr('ナビ記事');
	}
	public function getParentContentsId()
	{
		return $this->attributes['article_id'];
	}
}	
class NaviPageMelmagaTrackback extends NaviPageTrackback
{
	public function getTrackbackType()
	{
		return HNb::tr('メルマガ');
	}
	
	public function getParentContentsId()
	{
		return $this->attributes['melmaga_contents_id'];
	}
}	
class NaviPageEnqueteTrackback extends NaviPageTrackback
{
	public function getTrackbackType()
	{
		return HNb::tr('アンケート');
	}
	
	public function getParentContentsId()
	{
		return $this->attributes['enquete_id'];
	}
}	


require_once MO_WEBAPP_DIR.'/modules/Navi/filters/trackbackFilter.class.php';
abstract class NaviTrackbackList extends HNbAbstractListTemplate
{
	public function getCount($criteria)
	{
		$sql = $this->getSelectSqlWithNaviPage('count(*)');
	 	$sql .= ' WHERE '.$criteria->getWhere();
	 	$db = HNb::getAdodb();
	 	return $db->GetOne($sql);
	}
	public function getItemList($criteria)
	{
		$sql = $this->getSelectSql();
	 	$sql .= ' WHERE '.$criteria->render();
	 	$db = HNb::getAdodb();

        $rs = $db->SelectLimit($sql, $criteria->getLimit(), $criteria->getStart());
		$ret = array();
		if(!is_object($rs)){
			return $ret;
		}
		$rows = $rs->GetRows();
		foreach($rows as $row){
			$obj = $this->getTrackbackObject();
			$obj->setAttributes($row);
			$ret[] = $obj;
		}
	 	return $ret;
	}
	abstract protected function getSelectSqlWithNaviPage($select);
	abstract protected function getSelectSql();
	abstract protected function getTrackbackObject();
	public function getLimit()
	{
		return 10;
	}
}
class BlogTrackbackList extends NaviTrackbackList
{
	public function getFilter()
	{
		return new BlogTrackbackFilter();
	}
	protected function getSelectSql()
	{
		$sql = $this->getSelectSqlWithNaviPage('t_blog_trackback.*');
		return $sql;
	}
	protected function getSelectSqlWithNaviPage($select)
	{
	 	$sql = "SELECT ".$select." FROM t_blog_trackback " .
	 			" JOIN t_blog_article ON blt_article_id=bla_article_id" .
	 			" JOIN t_blog ON bla_blog_id=blg_blog_id" .
	 			" JOIN t_navi_page ON blg_blog_id=nvp_blog_id";
	 	return $sql;
	}
	protected function getTrackbackObject()
	{
		return new NaviPageBlogTrackback();
	}
}
class MelmagaTrackbackList extends NaviTrackbackList
{
	public function getFilter()
	{
		return new MelmagaTrackbackFilter();
	}
	public function getSelectSql()
	{
		$sql = $this->getSelectSqlWithNaviPage('l_melmaga_trackback.*');
		return $sql;
	}
	protected function getSelectSqlWithNaviPage($select)
	{
	 	$sql = "SELECT ".$select." FROM l_melmaga_trackback " .
	 			" JOIN t_melmaga_contents ON mmt_melmaga_contents_id=mmc_melmaga_contents_id" .
	 			" JOIN t_melmaga ON mmc_melmaga_id=mmg_melmaga_id" .
	 			" JOIN t_navi_page ON mmg_melmaga_id=nvp_melmaga_id";
	 	return $sql;
	}
	protected function getTrackbackObject()
	{
		return  new NaviPageMelmagaTrackback();
	}
}
class EnqueteTrackbackList extends NaviTrackbackList
{
	public function getFilter()
	{
		return new EnqueteTrackbackFilter();
	}
	public function getSelectSql()
	{
		$sql = $this->getSelectSqlWithNaviPage('l_navi_enq_trackback.*');
		return $sql;
	}
	protected function getSelectSqlWithNaviPage($select)
	{
	 	$sql = "SELECT ".$select." FROM l_navi_enq_trackback " .
	 			" JOIN t_navi_enquete ON net_enquete_id=nen_enquete_id" ;
	 	return $sql;
	}
	protected function getTrackbackObject()
	{
		return  new NaviPageEnqueteTrackback();
	}
}
?>