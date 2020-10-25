<?php
/**
 * ナビページ編集画面トップ
 *
 * navipage_idで指定されたナビページの編集メニューを表示する。
 * 各ナビページモジュールからお知らせを取得して表示する。
 *  
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowNaviEditAction.class.php,v 1.5 2006/02/21 13:31:07 ryu Exp $
 */

class ShowNaviEditAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        
        if($user->isNavi()){
        	if(hitobito::getNaviPageInfo()->getId() > 0){
				Navi::GuardNaviEdit($this->getContext()->getUser());
        	}
            // 各モジュールからお知らせを取得する
            $navipageId = hitobito::getNaviPageInfo()->getId();
    		if($navipageId){        
	            $NaviPageModules = array('Contact', 'Waiwai');
	            $messages = array();
	            foreach($NaviPageModules as $moduleName){
	                $libPath = MO_WEBAPP_DIR . '/modules/'.$moduleName. '/lib/'.$moduleName.'Interface.class.php';
	                if(file_exists($libPath)){
	                    require_once ($libPath);
	                    $interfaceClassName = $moduleName.'Interface2NaviPage';
	                    $messages = array_merge($messages, call_user_func(array($interfaceClassName,'getNotify2Navi'),$navipageId));
	                }
	            }
	            $request->setAttribute('navi_notify', $messages);
    		}
        	return View::SUCCESS;
        }else{
        	$controller->redirect('index.php');
        	return View::NONE;
        }
    }
    
    public function isSecure()
    {
        return TRUE;
    }
}

?>