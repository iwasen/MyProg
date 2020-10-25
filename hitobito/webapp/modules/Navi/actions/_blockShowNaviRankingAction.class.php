<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNaviRankingAction.class.php,v 1.2 2006/01/12 11:10:16 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
require_once MO_WEBAPP_DIR. '/modules/Blog/lib/BlogStoryManager.class.php';
class _blockShowNaviRankingAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        // ナビランキング
        $naviManager = new hitobitoNavipageManager();
        $naviranking = $naviManager->getNaviRanking();
        if(empty($naviranking)){
        	return View::NONE;
        }
        $request->setAttribute('navi_navi_ranking', $naviranking);
        
        // 記事ランキング
        $blogManager = new BlogStoryManager();
        $storyRanking = $blogManager->getStoryRanking();
        $request->setAttribute('navi_story_ranking', $storyRanking);
        
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>