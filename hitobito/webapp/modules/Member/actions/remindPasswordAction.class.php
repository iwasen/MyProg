<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: remindPasswordAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class RemindPasswordAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $manager = HNb::getHandler('MemberManager');
        $mail = $request->getParameter('member_mail');
        $mail = trim($mail);
        if(empty($mail)){
        	$request->setAttribute('error', '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ޤ���');
        	return View::INPUT;
        }
        	
        if(!$manager->inMemberByEmail($mail)){
        	$request->setAttribute('error', '�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���');
        	return View::INPUT;
        }
       	// ��ȯ��
		$manager->remindPasssword($mail);       	
        
        return View::SUCCESS;
    }


    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }

    public function isSecure()
    {
        return false;
    }
}

?>