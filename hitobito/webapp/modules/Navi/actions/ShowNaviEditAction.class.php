<?php
/**
 * �ʥӥڡ����Խ����̥ȥå�
 *
 * navipage_id�ǻ��ꤵ�줿�ʥӥڡ������Խ���˥塼��ɽ�����롣
 * �ƥʥӥڡ����⥸�塼�뤫�餪�Τ餻���������ɽ�����롣
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
            // �ƥ⥸�塼�뤫�餪�Τ餻���������
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