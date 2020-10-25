<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RequexxxloseNaviPageAction.class.php,v 1.3 2006/03/02 06:04:36 ryu Exp $
 */
// TODO navipage_id で、自分のナビページかチェックする

class RequexxxloseNaviPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
        
        $mailer = hitobito::getMailer();
        $mailer->loadTemplate('ReqEndNaviPage');
        
        $navipage_id = intval($request->getParameter('navipage_id'));
        
        $navipageHandler = new hitobitoNavipageManager();
        $naviPage = $navipageHandler->get($navipage_id);
        
        
        // NAMEセット
        $mailer->setAttribute('NAME', $naviPage->getNaviName()); 
        // ナビページ名セット
        $mailer->setAttribute('NAVIPAGE', $naviPage->getTitle()) ;
        
        $systemMaster = HNbSystemMaster::getInstance();
        $notifyAddr = $systemMaster->getAttribute('notify_addr');
        $mailer->send($notifyAddr);
        
        $request->setAttribute('message', HNb::tr('閉鎖申請を受け付けました。<br />事務局確認後に閉鎖となります。<br />今しばらくお待ちください。'));
        $request->setAttribute('title', HNb::tr('ナビページ閉鎖申請'));
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