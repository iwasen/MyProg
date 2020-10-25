<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminNgwordAction.class.php,v 1.1 2006/01/09 14:31:36 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/db/t_blog_ngword.class.php';

class adminNgwordAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		
		$item_id = intval($request->getParameter('blog_ngword_id'));
		if($item_id > 0){
			$manager = new t_blog_ngwordObjectHandler();
			$obj = $manager->get($item_id);
			if($request->hasParameter('blog_modifyNgword_x')){
				$url = 'index.php?module=Blog&action=modifyNgword&navipage_id='.hitobito::getNaviPageInfo()->getId().'&blog_ngword_id='.$item_id;
				$controller->redirect($url);
			}
			if($request->hasParameter('blog_deleteNgword_x')){
				$manager->delete($obj);
				$request->setAttribute('message', '削除しました');
				$request->setAttribute('return_url', 'index.php?module=Blog&amp;action=adminNgword&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
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

        $itemManager = new t_blog_ngwordObjectHandler();
        $navipage_id = hitobito::getNaviPageInfo()->getId();
        $criteria = new HNbCriteria();
        $criteria->setWhere('bng_navi_page_id='.$navipage_id);
        $criteria->setOrder('ORDER BY bng_date DESC');
        $itemlist = $itemManager->getObjects($criteria);
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