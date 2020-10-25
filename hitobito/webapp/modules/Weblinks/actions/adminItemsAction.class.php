<?php
/**
 * �����ѹ��ˤĤ��Ի���
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminItemsAction.class.php,v 1.7 2006/05/08 19:32:23 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/category.class.php';
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/link.class.php';
class adminItemsAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		$category_id = $request->getParameter('weblinks_category_id');
		$categoryManager = new WeblinksCategoryManager();
		if($request->hasParameter('weblinks_filter_category_x')){
			$url = 'index.php?module=Weblinks&action=adminItems&navipage_id='.hitobito::getNaviPageInfo()->getId();
			$url .=($category_id > 0) ? '&weblinks_category_id='.$category_id : '';
			$controller->redirect($url);
			return View::NONE;
		}
		$category = $categoryManager->get($category_id);
		if($category_id > 0){
			if($request->hasParameter('weblinks_modifyCategory_x')){
				$url = 'index.php?module=Weblinks&action=modifyCategory&navipage_id='.hitobito::getNaviPageInfo()->getId().'&weblinks_category_id='.$category_id;
				$controller->redirect($url);
				return View::NONE;
			}
			if($request->hasParameter('weblinks_orderUpCategory_x')){
				$categoryManager->orderUp($category);
				$controller->redirect('index.php?module=Weblinks&action=adminItems&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
//				$request->setAttribute('message', '��ذ�ư���ޤ���');
//				return View::SUCCESS;
			}
			if($request->hasParameter('weblinks_orderDownCategory_x')){
				$categoryManager->orderDown($category);
				$controller->redirect('index.php?module=Weblinks&action=adminItems&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
//				$request->setAttribute('message', '���ذ�ư���ޤ���');
//				return View::SUCCESS;
			}
			if($request->hasParameter('weblinks_deleteCategory_x')){
				if($category->canDelete()){
					$categoryManager->delete($category);
					$request->setAttribute('message', '������ޤ���');
					return View::SUCCESS;
				}else{
					$request->setAttribute('message', HNb::tr('���Υ��ƥ���ϥ�󥯤���Ͽ����Ƥ���ΤǺ���Ǥ��ޤ���'));
					return View::ERROR;
				}
			}
		}
		$link_id = intval($request->getParameter('weblinks_link_id'));
		if($link_id > 0){
			$manager = new WeblinksLinkManager();
			$obj = $manager->get($link_id);
			if($request->hasParameter('weblinks_modifyLink_x')){
				$url = 'index.php?module=Weblinks&action=modifyLink&navipage_id='.hitobito::getNaviPageInfo()->getId().'&weblinks_link_id='.$link_id;
				$controller->redirect($url);
			}
			if($request->hasParameter('weblinks_orderUpLink_x')){
				$manager->orderUp($obj);
				$controller->redirect('index.php?module=Weblinks&action=adminItems&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
//				$request->setAttribute('message', '��ذ�ư���ޤ���');
//				return View::SUCCESS;
			}
			if($request->hasParameter('weblinks_orderDownLink_x')){
				$manager->orderDown($obj);
				$controller->redirect('index.php?module=Weblinks&action=adminItems&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
//				$request->setAttribute('message', '���ذ�ư���ޤ���');
//				return View::SUCCESS;
			}
			if($request->hasParameter('weblinks_deleteLink_x')){
				$manager->delete($obj);
				$request->setAttribute('message', '������ޤ���');
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
		$categoryManager = new WeblinksCategoryManager();

		// ���ƥ�������ˤϸ��ߤΥʥӥڡ����Ǻ������줿���ƥ���Τߤ�ɽ������
		$categories = $categoryManager->getCategoriesByModifyNavipageId(hitobito::getNaviPageInfo()->getId());
		        
        $request->setAttribute('categories', $categories);
        
        $itemManager = new WeblinksLinkManager();
        $category_id = intval($request->getParameter('weblinks_category_id'));
        $itemlist = $itemManager->getItemsByCategoryId($category_id);
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