<?php
/**
 * ブロックマネージャ
 * 
 * リクエストパラメータにあわせたブロックリストを返す
 * 
 * @author Ryuji 
 */

define('HIDE_BLOCK', FALSE); 

class HNbBlockManager
{
	private $context;
	
	/**
	 * @param	instance	mojavi context
	 * @return	void
	 */
	public function __construct($context)
	{
		$this->context = $context;
		$this->action = $this->context->getActionName();
		$this->module = $this->context->getModuleName();
	}
	
	
	/**
	 * @return	array	ブロックリストarray(array('module'=>MODULE_NAME, 'action'=>ACTION_NAME), array(...))
	 */
	public function getLeftBlocks()
	{
		if(HIDE_BLOCK) return array();
		$blocklist = array();
        
		$authenticated = $this->context->getUser()->isAuthenticated();
		
		
		// TOP画面ブロックリスト
		if($this->action=='Index' && $this->module=='Default'){
			$blocklist[] = array('module'=>'top', 'action'=> '_blockCorporateIntroduction');
			$blocklist[] = array('module'=>'top', 'action'=> '_blockShowTopTodaysPushNavipage');
			$blocklist[] = array('module'=>'top', 'action'=> '_blockShowTopNaviList');
			$blocklist[] = array('module'=>'top', 'action'=> '_blockShowTopVideo');
			
		// TOP画面以外のブロックリスト
		}else{
/* 2007-11-09 Coment out by fujimori
			
			if($this->module == 'contents'){
				$blocklist[] = array('module' => 'contents', 'action' => '_blockShowGuideMenu');
			}
			$blocklist[] = array('module'=>'Member', 'action'=> '_blockShowUserMenu');
			if(hitobito::getNaviPageInfo()->getId() > 0){
			    // NaviPage
				$blocklist[] = array('module'=> 'Navi', 'action'=> '_blockShowAssistNavi');
				$blocklist[] = array('module'=> 'Blog', 'action'=> '_blockShowCategoryMenu');
				$blocklist[] = array('module'=> 'Weblinks', 'action'=> '_blockCategoryMenu');
				// TODO Dropdown
	//					$blocklist[] = array('module'=> 'Blog', 'action'=> '_blockShowCarendar');
				if($this->module == 'Navi' && ($this->action=='ShowNaviPage' || $this->action=='Index')){
					// hidden
				}else{
					$blocklist[] = array('module'=> 'Blog', 'action'=> '_blockShowNewStoriesList');
				}
				$blocklist[] = array('module'=> 'NaviSelect', 'action'=> '_blockShowNewNaviSelectList');
				// TODO comment
				// TODO Trackback
				$blocklist[] = array('module'=> 'MailMag', 'action'=> '_blockRegistMailMag');
	//					$blocklist[] = array('module'=>'Enquete', 'action'=> '_blockShowEnqList');
				$blocklist[] = array('module'=>'Enquete', 'action'=> '_blockEnqueteBacknumber');
				$blocklist[] = array('module'=>'MailMag', 'action'=> '_blockMailMagBacknumber2');
				$blocklist[] = array('module'=>'Waiwai', 'action'=> '_blockNewRemarks2');
				$blocklist[] = array('module'=>'Member', 'action'=> '_blockLogin');
			}else{
			    // other
				$blocklist = array_merge($blocklist, $this->getLeftBlocksByChannelView());
	        }
*/
            
            
            
            //$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowHeader'); // [ナビヘッダー]
            //$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowRelationNaviPage'); // [関連テーマナビ]
            //$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowNaviRanking'); // [ナビランキング|人気記事ランキング]
            //$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowAssistNavi'); // [!?]
            //$blocklist[] = array('module' => 'NaviSelect', 'action' => '_blockShowNewNaviSelectList'); // [最新ナビセレクト]
            //$blocklist[] = array('module' => 'Blog', 'action' => '_blockShowCategoryMenu'); // [ナビ記事カテゴリー]
            //$blocklist[] = array('module' => 'Blog', 'action' => '_blockShowCarendar'); // [カレンダー]
            //$blocklist[] = array('module' => 'Blog', 'action' => '_blockShowNewStoriesList'); // [最新記事]
            //$blocklist[] = array('module' => 'contents', 'action' => '_blockShowGuideMenu'); // [使い方ガイド]
            //$blocklist[] = array('module' => 'contents', 'action' => '_blockShowGoNavi'); // [メンバー登録]
            //$blocklist[] = array('module' => 'contents', 'action' => '_blockShowAdminBlogPR'); // [事務局ブログ公開中]
            //$blocklist[] = array('module' => 'hitobito2', 'action'=> '_blockShowSpecialStories'); // [特集記事]
            //$blocklist[] = array('module' => 'Enquete', 'action' => '_blockShowEnqList'); // [!?]
            //$blocklist[] = array('module' => 'Enquete', 'action' => '_blockEnqueteBacknumber'); // [!?]
            //$blocklist[] = array('module' => 'MailMag', 'action' => '_blockRegistMailMag'); // [メールマガジン]
            //$blocklist[] = array('module' => 'MailMag', 'action' => '_blockMailMagBacknumber2'); // [!?]
            //$blocklist[] = array('module' => 'Member', 'action' => '_blockLogin'); // [メンバーログイン]
            //$blocklist[] = array('module' => 'Waiwai', 'action' => '_blockNewRemarks2'); // [ワイワイ会議室最新投稿]
            //$blocklist[] = array('module' => 'Weblinks', 'action' => '_blockCategoryMenu'); // [リンク集カテゴリー]
            //$blocklist[] = array('module' => 'Category', 'action' => '_blockShowMainCategories'); // [チャネル一覧]
            //$blocklist[] = array('module' => 'Kuchikomi', 'action' => '_blockShowKuchikomi'); // [!?]
            
            if($authenticated == 1){
                $blocklist[] = array('module'=>'Member', 'action'=> '_blockShowUserMenu');
            }
            switch($this->module){
                case 'contents':
                    $blocklist[] = array('module' => 'contents', 'action' => '_blockShowGuideMenu');
                    break;
                case 'Navi':
                    $blocklist[] = array('module' => 'Blog', 'action' => '_blockShowCategoryMenu'); // [ナビ記事カテゴリー]
                    $blocklist[] = array('module' => 'Weblinks', 'action' => '_blockCategoryMenu'); // [リンク集カテゴリー]
                    if ($_REQUEST['action']=='ShowNaviEdit') {
                        $blocklist[] = array('module' => 'Blog', 'action' => '_blockShowNewStoriesList'); // [最新記事]
                        $blocklist[] = array('module' => 'Waiwai', 'action' => '_blockNewRemarks2'); // [ワイワイ会議室最新投稿]
                    }
                    break;
                case 'Blog':
                    if($_REQUEST['action']=='Index'){
                        $blocklist[] = array('module' => 'Blog', 'action' => '_blockShowCategoryMenu'); // [ナビ記事カテゴリー]
                    }
                    break;
                case 'Weblinks':
                    $blocklist[] = array('module' => 'Weblinks', 'action' => '_blockCategoryMenu'); // [リンク集カテゴリー]
                    break;
                case 'Waiwai':
                case 'NaviSelect':
                case 'Enquete':
                case 'MailMag':
                case 'Default':
                case 'Category':
                break;
            }
            
            if($authenticated != 1){
                $blocklist[] = array('module' => 'contents', 'action' => '_blockShowGoNavi'); // [メンバー登録]
            }
            $blocklist[] = array('module' => 'Navi', 'action' => '_blockShowRelationNaviPage'); // [関連テーマナビ]
		} // 2006/12/11 fujimori
		ksort($blocklist);
		return $blocklist;
	}

	private function getLeftBlocksByChannelView()
	{
		$blocklist = array();
			$blocklist[] = array('module'=>'Category', 'action'=> '_blockShowMainCategories');
			$blocklist[] = array('module'=>'Member', 'action'=> '_blockLogin');
			$blocklist[] = array('module'=>'Navi', 'action'=> '_blockShowNaviRanking');
			$blocklist[] = array('module'=>'Enquete', 'action'=> '_blockShowEnqList');
			return $blocklist;
	}
	
	public function getCenterBlocks()
	{
		if(HIDE_BLOCK) return array();
		$blocklist = array();
		switch($this->module){
			case 'Default':
				if($this->action == 'Error404') return array();
				$blocklist[] = array('module'=>'Navi', 'action'=>'_blockShowTodaysPushNavipage');
				$blocklist[] = array('module'=>'Blog', 'action' => '_blockShowNewStories');
//				$blocklist[] = array('module'=>'Waiwai', 'action' => '_blockNewRemarks');
				//$blocklist[] = array('module'=>'NaviSelect', 'action' => '_blockShowNewNaviSelect'); // 2007-11-14 Coment out by fujimori
				break;
			case 'Category':
				if($this->action == 'Error404') return array();
				$blocklist[] = array('module'=>'Navi', 'action'=>'_blockShowTodaysPushNavipage');
				$blocklist[] = array('module'=>'Blog', 'action' => '_blockShowNewStories');
				$blocklist[] = array('module'=>'Waiwai', 'action' => '_blockNewRemarks');
				//$blocklist[] = array('module'=>'NaviSelect', 'action' => '_blockShowNewNaviSelect'); // 2007-11-14 Coment out by fujimori
				break;
			case 'Navi':
				if(empty($_REQUEST['action']) || $_REQUEST['action']=='ShowNaviPage' || $_REQUEST['action']=='Index'){
					// TODO BLOG本文表示
					$blocklist[] = array('module'=>'Blog', 'action' => '_blockShowNewStories');
					// TODO comment
					// TODO trackback
					$blocklist[] = array('module'=>'Waiwai', 'action' => '_blockNewRemarks');
					//$blocklist[] = array('module'=>'NaviSelect', 'action' => '_blockShowNewNaviSelect'); // 2007-11-14 Coment out by fujimori
					$blocklist[] = array('module'=>'Enquete', 'action' => '_blockNewestEnquete');
					$blocklist[] = array('module'=>'Enquete', 'action' => '_blockEnqueteList');
					$blocklist[] = array('module'=>'MailMag', 'action' => '_blockMailMagBacknumber');
					// 
				}
				break;
		}
		return $blocklist;
	}
	
	public function getRightBlocks()
	{
		if(HIDE_BLOCK) return array();
		$blocklist = array();

		// TOP画面ブロックリスト
		if($this->action=='Index' && $this->module=='Default'){
			$blocklist[] = array('module' => 'top', 'action'=>'_blockTopGoNavi');
			$blocklist[] = array('module' => 'top', 'action'=>'_blockShowUserMenu');
			$blocklist[] = array('module' => 'top', 'action'=> '_blockAdminInformation');
			$blocklist[] = array('module' => 'top', 'action'=> '_blockSpecialStories');
			$blocklist[] = array('module'=>'top', 'action'=> '_blockShowTopNaviRanking');
			$blocklist[] = array('module'=>'top', 'action'=> '_blockEnqResultList');
		// TOP画面以外のブロックリスト
		}else{

			switch($this->module){
				case 'Navi':
					if(empty($_REQUEST['action']) || $_REQUEST['action']=='ShowNaviPage' || $_REQUEST['action']=='Index'){
						//$blocklist[] = array('module' => 'contents', 'action'=>'_blockShowGoNavi'); // 2007-11-09 removed by fujimori
						//$blocklist[] = array('module' => 'Navi', 'action'=>'_blockShowRelationNaviPage'); // 2007-11-09 removed by fujimori
						//$blocklist[] = array('module' => 'Kuchikomi', 'action'=> '_blockShowKuchikomi');
					} 
					break;
				case 'Blog':
				    if(hitobito::getNaviPageInfo()->getId() > 0){
				        // Navipage
				        // no right blocks
				    }else{
				        // blog recent list
						//$blocklist[] = array('module' => 'contents', 'action'=>'_blockShowGoNavi'); // 2007-11-09 removed by fujimori
						//$blocklist[] = array('module' => 'Kuchikomi', 'action'=> '_blockShowKuchikomi');
				    }
					break;
				case 'Default':
				case 'Category':
					//$blocklist[] = array('module' => 'contents', 'action'=>'_blockShowGoNavi'); // 2007-11-09 removed by fujimori
					//$blocklist[] = array('module' => 'hitobito2', 'action'=> '_blockShowSpecialStories'); // 2007-11-09 removed by fujimori
					//$blocklist[] = array('module' => 'Kuchikomi', 'action'=> '_blockShowKuchikomi');
					//$blocklist[] = array('module' => 'contents', 'action'=>'_blockShowAdminBlogPR'); // 2007-11-09 removed by fujimori
					break;
			}
		}
			return $blocklist;
		}
	
	public function getHeadBlocks()
	{
		if(HIDE_BLOCK) return array();
		$blocklist = array();

		switch($this->module){
			case 'Waiwai':
//				if($this->action != 'EntryList'){
//					$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowHeader');
//				}
//				break;
			case 'MailMag':
//				if($this->action != 'SubscriptionList'){
//					$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowHeader');
//				}
//				break;
			case 'Navi':
			case 'Blog':
			case 'Enquete':
			case 'NaviSelect':
			case 'Weblinks':
			case 'Contact':
			case 'contents':
			case 'hitobito2':
				if(hitobito::getNaviPageInfo()->getId() > 0){
					$blocklist[] = array('module' => 'Navi', 'action' => '_blockShowHeader');
				}
				break;
			default:
				break;
		}
		return $blocklist;

	}
	
	public function getHeader()
	{
		// TOP画面ブロックリスト
		if($this->action=='Index' && $this->module=='Default'){
			$header = array('module' => 'top', 'action'=>'_blockHeader');
		// TOP画面以外のブロックリスト
		}else{
            $header = array('module' => 'hitobito2', 'action'=>'_blockHeader');
		}
		return $header;
	}
}
 
?>