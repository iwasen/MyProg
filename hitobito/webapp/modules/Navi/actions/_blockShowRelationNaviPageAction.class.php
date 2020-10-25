<?php
/**
 * ��Ϣ�ơ��ޥʥӥ֥�å�
 *
 * ����ɽ����Υʥӥڡ�����Ʊ�����֥���ͥ�Υʥӥڡ�������������3��ɽ��
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
        // �ʥӥڡ����Ǥʤ������ɽ��
        return View::NONE;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>