<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowAssistNaviAction.class.php,v 1.3 2006/01/18 09:26:19 ryu Exp $
 */

class _blockShowAssistNaviAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $navipage = hitobito::getNaviPageInfo();
        $list = $navipage->getAssistNavi('anv_status=1');
        if(empty($list)){
        	return View::NONE;
        }
        	
        $request->setAttribute('navi_assist_navi', $list);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>