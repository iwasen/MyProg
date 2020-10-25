<?php
/**
 *
 *
 * 
 * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: AdminListAction.class.php,v 1.4 2006/01/19 05:19:55 ryu Exp $
 */
//require_once MO_WEBAPP_DIR.'/modules/Contact/lib/t_navi_inquiry.class.php';
require_once MO_WEBAPP_DIR.'/modules/Contact/filters/inquiryFilter.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';
class AdminListAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		if($request->hasParameter('contact_inquiry_id')){
			$id_list = $request->getParameter('contact_inquiry_id');
			$id_list = array_map("intval", $id_list);
		}
		if(empty($id_list)){
			$request->setAttribute('message', HNb::tr('問い合わせが選択されていません'));
			return View::ERROR;
		}
		
    	if($request->hasParameter('done_answer_x')){
    		$db = HNb::getAdodb();
    		$sql = sprintf("UPDATE t_navi_inquiry SET nvi_status=2 WHERE nvi_inquiry_id IN (%s) ", 
    			implode(',', $id_list));
    		$db->Execute($sql);
    		$request->setAttribute('message', HNb::tr('回答済みにしました'));
    		return View::SUCCESS;
    	}
    	if($request->hasParameter('yet_answer_x')){
    		$db = HNb::getAdodb();
    		$sql = sprintf("UPDATE t_navi_inquiry SET nvi_status=1 WHERE nvi_inquiry_id IN (%s) ", 
    			implode(',', $id_list));
    		$db->Execute($sql);
    		$request->setAttribute('message', HNb::tr('未回答にしました'));
    		return View::SUCCESS;
    	}
    	if($request->hasParameter('delete_inquiry_x')){
    		$db = HNb::getAdodb();
    		$sql = sprintf("DELETE FROM t_navi_answer WHERE nva_inquiry_id IN (%s)",
    			implode(',', $id_list));
    		$db->Execute($sql);
    		$sql = sprintf("DELETE FROM t_navi_inquiry WHERE nvi_inquiry_id IN (%s) ", 
    			implode(',', $id_list));
    		$db->Execute($sql);
    		$request->setAttribute('message', HNb::tr('削除しました'));
    		return View::SUCCESS;
    	}
    	
    	return View::ERROR;
    }
    
    public function isSecure()
    {
        return TRUE;
    }
    
   public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    	Navi::GuardNaviEdit($this->getContext()->getUser());
    
        $manager = HNb::getDBHandler('t_navi_inquiry');
        
        $filter = new ContactInquiryFilter();
        $start = intval($request->getParameter('contact_start'));
        $limit = 15;
        
        $filter->fetch($this->getContext());
        $criteria = $filter->getCriteria();
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        
        $total = $manager->getCount($criteria);
        $request->setAttribute('total', $total);
        
        $stories = $manager->getObjects($criteria);        
        $request->setAttribute('itemlist', $stories);
        
        $request->setAttribute('start_num', $start + 1);
        $request->setAttribute('end_num', $start + count($stories));
        
        $navigation = new hitobitoPageNavigation($total, $limit, $start, 'contact_start');
        $navigation->addParameter('module', 'Contact');
        $navigation->addParameters($filter->getExtraParameters());
        $request->setAttribute('navigation', $navigation);
        
        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }    
}




?>