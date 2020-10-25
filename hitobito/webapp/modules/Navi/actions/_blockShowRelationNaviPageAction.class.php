<?php
/**
 * 関連テーマナビブロック
 *
 * 現在表示中のナビページと同じサブチャネルのナビページからランダムで3件表示
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowRelationNaviPageAction.class.php,v 1.1 2006/01/13 13:36:29 ryu Exp $
 */

class _blockShowRelationNaviPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $CurrentNaviPage = hitobito::getNaviPageInfo();
        if($CurrentNaviPage->getId() > 0){
        	$manager = new hitobitoNavipageManager();
        	$navipageList = $manager->getRelationNavipages($CurrentNaviPage);
        	if(count($navipageList) > 0){
        		$request->setAttribute('itemlist', $navipageList);
        		return View::SUCCESS;
        	}
        }
        // ナビページでなければ非表示
        return View::NONE;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>