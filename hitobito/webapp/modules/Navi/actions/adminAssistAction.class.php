<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminAssistAction.class.php,v 1.3 2006/01/05 09:08:18 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/assistNavi.class.php';
class adminAssistAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		$id = $request->getParameter('navi_navi_id');
		if($id > 0){
			$manager = new assistNaviManager();
			$obj = $manager->get(array('anv_navi_page_id' => hitobito::getNaviPageInfo()->getId(),'anv_navi_id'=>$id));
			if($request->hasParameter('navi_modify_x')){
				$url = 'index.php?module=Navi&action=modifyAssist&navipage_id='.hitobito::getNaviPageInfo()->getId().'&navi_navi_id='.$id;
				$controller->redirect($url);
				return View::NONE;
			}
			if($request->hasParameter('navi_orderUp_x')){
				$manager->orderUp($obj);
				$request->setAttribute('message', '上へ移動しました');
				return View::SUCCESS;
			}
			if($request->hasParameter('navi_orderDown_x')){
				$manager->orderDown($obj);
				$request->setAttribute('message', '下へ移動しました');
				return View::SUCCESS;
			}
			if($request->hasParameter('navi_delete_x')){
					$manager->delete($obj);
					$request->setAttribute('message', '削除しました');
					return View::SUCCESS;
			}
		}

		return $this->getDefaultView();
    }


    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());

		$Manager = new assistNaviManager();

		$itemlist = $Manager->getObjectByNaviPageId(hitobito::getNaviPageInfo()->getId());
		        
        $request->setAttribute('itemlist', $itemlist);
        
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

?>