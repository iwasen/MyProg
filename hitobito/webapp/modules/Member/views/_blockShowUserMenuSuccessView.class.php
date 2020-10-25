<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowUserMenuSuccessView.class.php,v 1.6 2006/02/06 06:18:55 ryu Exp $
 */

class _blockShowUserMenuSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
		$this->setAttribute('navipage_id', hitobito::getNaviPageInfo()->getId());

		if($user->getAttribute('member_type') == 'navi'){
        	$this->setTemplate('_blockNaviMenu.html');
        	
        	$navipageManager = new hitobitoNavipageManager();
        	$naviPages = $navipageManager->getNaviPagesByMemberId($user->getAttribute('mbr_member_id'));
        	$this->setAttribute('member_my_navipages', $naviPages);

        	$assistPages = $navipageManager->getNaviPagesByAssistNaviMemberId($user->getAttribute('mbr_member_id'));
        	$this->setAttribute('member_assist_navipages', $assistPages);
        	
        	if($user->isMyNaviPage() || $user->isMyAssistNaviPage()){
        		$this->setAttribute('show_edit_menu', TRUE);
        	}else{
        		$this->setAttribute('show_edit_menu', FALSE);
        	}
		}else{
        	$this->setTemplate('_blockUserMenu.html');
		}
    }
}

?>