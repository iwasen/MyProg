<?php
/**
 * ナビページヘッダの表示
 *
 * 
 *  
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowHeaderAction.class.php,v 1.9 2007/11/14 09:00:35 cvshitobito Exp $
 */
require_once MO_WEBAPP_DIR. '/modules/Waiwai/lib/WaiwaiDatabase.class.php';
require_once MO_WEBAPP_DIR.'/modules/Navi/lib/NaviPageLogger.class.php';

class _blockShowHeaderAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $navipage = hitobito::getNaviPageInfo();
        
        $request->setAttribute('navi_navipage', $navipage);
        
        // 表示するタブリストを得る
        $tabList = $this->getTabList();
        // アクティブにするタブを決定する
        $activeTabName = $this->getActiveTabName();
        // 特定のタブをアクティブにする
        if(isset($tabList[$activeTabName])){
            $tabList[$activeTabName]->setActive();
        }
        $request->setAttribute('navipage_header_tablist', $tabList);
        
        //ページビューと訪問者数をインクリメント
        NaviPageLogger::incrementPageView($navipage->getId(), time());
        NaviPageLogger::incrementVisiters($navipage->getId(), time());
        
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    /**
    * 表示するタブリストを返す
    * @return array タブネームをキーとした連想配列。値はNaviPageHeaderTabインスタンス
    */
    private function getTabList()
    {
        $navipage_id = hitobito::getNaviPageInfo()->getId();
        $tabList = array();
        if($this->isVisibleNaviEditTab()){
            $tabList['NaviEdit']   = new NaviPageHeaderTab('navi_menu_edit.gif',   'navi_menu_edit_on.gif',    'index.php?module=Navi&amp;action=ShowNaviEdit&amp;navipage_id=' . $navipage_id , '編集する');
        }
        $tabList['Navi']    = new NaviPageHeaderTab('navi_menu_top.gif',    'navi_menu_top_on.gif',     'index.php?module=Navi&amp;action=ShowNaviPage&amp;navipage_id=' . $navipage_id , 'ナビページの最新情報がここでわかります');
        $tabList['Blog']       = new NaviPageHeaderTab('navi_menu_blog.gif',   'navi_menu_blog_on.gif',    'index.php?module=Blog&amp;action=Index&amp;navipage_id=' . $navipage_id , 'ナビ記事はこちらから');
        //$tabList['NaviSelect'] = new NaviPageHeaderTab('navi_menu_select.gif', 'navi_menu_select_on.gif',  'index.php?module=NaviSelect&amp;navipage_id=' . $navipage_id , 'ナビおすすめのこの一品！'); // 2007-11-14 Coment out by fujimori
        if($this->isVisibleWaiwaiTab()){
            $tabList['Waiwai']     = new NaviPageHeaderTab('navi_menu_board.gif',  'navi_menu_board_on.gif',   'index.php?module=Waiwai&amp;action=Browse&amp;navipage_id=' . $navipage_id , 'このテーマについて皆でワイワイ話しましょう♪');
        }
        $tabList['Enquete']    = new NaviPageHeaderTab('navi_menu_enq.gif',    'navi_menu_enq_on.gif',     'index.php?module=Enquete&action=ListEnquete&navipage_id=' . $navipage_id , '皆はどう考えてる？教えてあなたの考え！');
        if(hitobito::getNaviPageInfo()->getMelmagaStatus() ==1){
            $tabList['MailMag']    = new NaviPageHeaderTab('navi_menu_mel.gif',    'navi_menu_mel_on.gif',     'index.php?module=MailMag&action=BacknumberList&navipage_id=' . $navipage_id , 'メルマガ');
        }
        $tabList['Weblinks']   = new NaviPageHeaderTab('navi_menu_links.gif',  'navi_menu_links_on.gif',   'index.php?module=Weblinks&amp;navipage_id=' . $navipage_id , 'このテーマに関するリンクです');
        return $tabList;
    }
    private function isVisibleNaviEditTab()
    {
        $user = $this->getContext()->getUser();
		if($user->isMyNaviPage()){
		    return TRUE;
		}else{
		    return FALSE;
		}			
    }
    private function isVisibleWaiwaiTab()
    {
        // ワイワイ会議室タブ制御
        $navipage = hitobito::getNaviPageInfo();
        $user = $this->getContext()->getUser();
        switch($navipage->getRoomStatus()){
        	case 1:
        		// 公開
        		return TRUE;
        		break;
        	case 2:
        		// 非公開：会議室に本登録しているユーザのみ閲覧可能
        		if($user->isAuthenticated()){
	        		$roomDB = new WaiwaiDatabase();
	        		if($roomDB->checkRoomMember($navipage->getWaiwaiId(), $user->getAttribute('mbr_member_id'))){
	        		    return TRUE;
	        		}else{
	        		    return FALSE;
	        		}
        		}else{
        		    return FALSE;
        		}
        		break;
        	case 9:
        		// 閉鎖も非表示
        		return FALSE;
        		break;
        	default:
        		// その他はタブ非表示としておく
        		return FALSE;
        }
    }
    
    /**
    * アクティブにするタブネームを返す
    * @return string
    */
    private function getActiveTabName()
    {
        if(!Navi::isNaviEditActionExecution()){
            return $_REQUEST['module'];
        }else{
            return 'NaviEdit';
        }
    }
}



class NaviPageHeaderTab
{
    private $activeImage; 
    private $notActiveImage;
    private $url;
    private $name;
    private $isActive = FALSE;
    /**
    * @param string アクティブになったときの画像ファイル名
    * @param string 通常の画像ファイル名
    * @param string タブのリンク先URL
    * @param string タブ名
    */
    public function __construct($notActiveImage, $activeImage, $url, $name)
    {
        $this->notActiveImage = $notActiveImage;
        $this->activeImage = $activeImage;
        $this->url = $url;
        $this->name = $name;
    }
    public function getImageFileName()
    {
        if($this->isActive){
            return $this->activeImage;
        }else{
            return $this->notActiveImage;
        }
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setActive()
    {
        $this->isActive = TRUE;
    }
}

?>