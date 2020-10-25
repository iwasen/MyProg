<?php
/**
 * �ʥӥڡ����إå���ɽ��
 *
 * 
 *  
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowHeaderAction.class.php,v 1.9 2007/11/14 09:00:35 cvshitobito Exp $
 */
require_once MO_WEBAPP_DIR. '/modules/Waiwai/lib/WaiwaiDatabase.class.php';
require_once MO_WEBAPP_DIR.'/modules/Navi/lib/NaviPageLogger.class.php';

class _blockShowHeaderAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $navipage = hitobito::getNaviPageInfo();
        
        $request->setAttribute('navi_navipage', $navipage);
        
        // ɽ�����륿�֥ꥹ�Ȥ�����
        $tabList = $this->getTabList();
        // �����ƥ��֤ˤ��륿�֤���ꤹ��
        $activeTabName = $this->getActiveTabName();
        // ����Υ��֤򥢥��ƥ��֤ˤ���
        if(isset($tabList[$activeTabName])){
            $tabList[$activeTabName]->setActive();
        }
        $request->setAttribute('navipage_header_tablist', $tabList);
        
        //�ڡ����ӥ塼��ˬ��Կ��򥤥󥯥����
        NaviPageLogger::incrementPageView($navipage->getId(), time());
        NaviPageLogger::incrementVisiters($navipage->getId(), time());
        
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    /**
    * ɽ�����륿�֥ꥹ�Ȥ��֤�
    * @return array ���֥͡���򥭡��Ȥ���Ϣ�������ͤ�NaviPageHeaderTab���󥹥���
    */
    private function getTabList()
    {
        $navipage_id = hitobito::getNaviPageInfo()->getId();
        $tabList = array();
        if($this->isVisibleNaviEditTab()){
            $tabList['NaviEdit']   = new NaviPageHeaderTab('navi_menu_edit.gif',   'navi_menu_edit_on.gif',    'index.php?module=Navi&amp;action=ShowNaviEdit&amp;navipage_id=' . $navipage_id , '�Խ�����');
        }
        $tabList['Navi']    = new NaviPageHeaderTab('navi_menu_top.gif',    'navi_menu_top_on.gif',     'index.php?module=Navi&amp;action=ShowNaviPage&amp;navipage_id=' . $navipage_id , '�ʥӥڡ����κǿ����󤬤����Ǥ狼��ޤ�');
        $tabList['Blog']       = new NaviPageHeaderTab('navi_menu_blog.gif',   'navi_menu_blog_on.gif',    'index.php?module=Blog&amp;action=Index&amp;navipage_id=' . $navipage_id , '�ʥӵ����Ϥ����餫��');
        //$tabList['NaviSelect'] = new NaviPageHeaderTab('navi_menu_select.gif', 'navi_menu_select_on.gif',  'index.php?module=NaviSelect&amp;navipage_id=' . $navipage_id , '�ʥӤ�������Τ��ΰ��ʡ�'); // 2007-11-14 Coment out by fujimori
        if($this->isVisibleWaiwaiTab()){
            $tabList['Waiwai']     = new NaviPageHeaderTab('navi_menu_board.gif',  'navi_menu_board_on.gif',   'index.php?module=Waiwai&amp;action=Browse&amp;navipage_id=' . $navipage_id , '���Υơ��ޤˤĤ��Ƴ��ǥ磻�磻�ä��ޤ��礦��');
        }
        $tabList['Enquete']    = new NaviPageHeaderTab('navi_menu_enq.gif',    'navi_menu_enq_on.gif',     'index.php?module=Enquete&action=ListEnquete&navipage_id=' . $navipage_id , '���Ϥɤ��ͤ��Ƥ롩�����Ƥ��ʤ��ιͤ���');
        if(hitobito::getNaviPageInfo()->getMelmagaStatus() ==1){
            $tabList['MailMag']    = new NaviPageHeaderTab('navi_menu_mel.gif',    'navi_menu_mel_on.gif',     'index.php?module=MailMag&action=BacknumberList&navipage_id=' . $navipage_id , '���ޥ�');
        }
        $tabList['Weblinks']   = new NaviPageHeaderTab('navi_menu_links.gif',  'navi_menu_links_on.gif',   'index.php?module=Weblinks&amp;navipage_id=' . $navipage_id , '���Υơ��ޤ˴ؤ����󥯤Ǥ�');
        return $tabList;
    }
    private function isVisibleNaviEditTab()
    {
        $user = $this->getContext()->getUser();
		if($user->isMyNaviPage()){
		    return TRUE;
		}else{
		    return FALSE;
		}			
    }
    private function isVisibleWaiwaiTab()
    {
        // �磻�磻��ļ���������
        $navipage = hitobito::getNaviPageInfo();
        $user = $this->getContext()->getUser();
        switch($navipage->getRoomStatus()){
        	case 1:
        		// ����
        		return TRUE;
        		break;
        	case 2:
        		// ���������ļ�������Ͽ���Ƥ���桼���Τ߱�����ǽ
        		if($user->isAuthenticated()){
	        		$roomDB = new WaiwaiDatabase();
	        		if($roomDB->checkRoomMember($navipage->getWaiwaiId(), $user->getAttribute('mbr_member_id'))){
	        		    return TRUE;
	        		}else{
	        		    return FALSE;
	        		}
        		}else{
        		    return FALSE;
        		}
        		break;
        	case 9:
        		// �ĺ�����ɽ��
        		return FALSE;
        		break;
        	default:
        		// ����¾�ϥ�����ɽ���Ȥ��Ƥ���
        		return FALSE;
        }
    }
    
    /**
    * �����ƥ��֤ˤ��륿�֥͡�����֤�
    * @return string
    */
    private function getActiveTabName()
    {
        if(!Navi::isNaviEditActionExecution()){
            return $_REQUEST['module'];
        }else{
            return 'NaviEdit';
        }
    }
}



class NaviPageHeaderTab
{
    private $activeImage; 
    private $notActiveImage;
    private $url;
    private $name;
    private $isActive = FALSE;
    /**
    * @param string �����ƥ��֤ˤʤä��Ȥ��β����ե�����̾
    * @param string �̾�β����ե�����̾
    * @param string ���֤Υ����URL
    * @param string ����̾
    */
    public function __construct($notActiveImage, $activeImage, $url, $name)
    {
        $this->notActiveImage = $notActiveImage;
        $this->activeImage = $activeImage;
        $this->url = $url;
        $this->name = $name;
    }
    public function getImageFileName()
    {
        if($this->isActive){
            return $this->activeImage;
        }else{
            return $this->notActiveImage;
        }
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setActive()
    {
        $this->isActive = TRUE;
    }
}

?>