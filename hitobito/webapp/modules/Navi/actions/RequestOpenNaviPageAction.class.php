<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RequestOpenNaviPageAction.class.php,v 1.3 2006/03/02 06:04:36 ryu Exp $
 */

// TODO navipage_id �ǡ���ʬ�Υʥӥڡ����������å�����
class RequestOpenNaviPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
 		Navi::GuardNaviEdit($this->getContext()->getUser());
        
        $mailer = hitobito::getMailer();
        $mailer->loadTemplate('ReqOpenNaviPage');
        
        $navipage_id = intval($request->getParameter('navipage_id'));
        
        $navipageHandler = new hitobitoNavipageManager();
        $naviPage = $navipageHandler->get($navipage_id);
        
        
        // NAME���å�
        $mailer->setAttribute('NAME', $naviPage->getNaviName()); 
        // �ʥӥڡ���̾���å�
        $mailer->setAttribute('NAVIPAGE', $naviPage->getTitle()) ;
        
        $systemMaster = HNbSystemMaster::getInstance();
        $notifyAddr = $systemMaster->getAttribute('notify_addr');
        $mailer->send($notifyAddr);
        
//        $adminHandler = HNb::getDBHandler('m_admin');
//		$adminList = $adminHandler->getObjects('adm_status=1 AND adm_type=2'); // ͭ���ǡ���̳�ɥ���
//		foreach($adminList as $admin){
//			$mailer->send($admin->getAttribute('adm_mail_addr'));
//		}
        
        // �ʥӥڡ����򥳥�ƥ�ĥ����å�������ˤ���
        $naviPage->setAttribute('nvp_contents_check', 1);
        $navipageHandler->insert($naviPage);
        
        
        $request->setAttribute('message', HNb::tr('��������������դ��ޤ�����<br />��̳�ɳ�ǧ��˸����Ȥʤ�ޤ���<br />�����Ф餯���Ԥ�����������'));
        $request->setAttribute('title', HNb::tr('�ʥӥڡ�����������'));
        
        return View::SUCCESS;
    }


    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());

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