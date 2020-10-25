<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowNaviEditView.class.php,v 1.3 2006/01/09 17:00:25 ryu Exp $
 */

class ShowNaviEditSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		if(hitobito::getNaviPageInfo()->getId()>0){
        	$this->setTemplate('NaviEdit.html');
		}else{
        	$navipageManager = new hitobitoNavipageManager();
        	$naviPages = $navipageManager->getNaviPagesByMemberId($user->getAttribute('mbr_member_id'));
        	$this->setAttribute('member_my_navipages', $naviPages);

        	$assistPages = $navipageManager->getNaviPagesByAssistNaviMemberId($user->getAttribute('mbr_member_id'));
        	$this->setAttribute('member_assist_navipages', $assistPages);
			
			$this->setTemplate('NaviEditTop.html');
		}
        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

?>