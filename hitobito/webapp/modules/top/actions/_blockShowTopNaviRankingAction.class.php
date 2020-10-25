<?php
/**
 * ナビランキング 表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';

class _blockShowTopNaviRankingAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        // ナビランキング
        $naviManager = new hitobitoNavipageManager();
        $naviranking = $naviManager->getNaviRankingTopScreen();
        if(empty($naviranking)){
        	return View::NONE;
        }
        $request->setAttribute('navi_navi_ranking', $naviranking);
        
        return View::SUCCESS;
    }
}

?>