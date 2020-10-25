<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Mojavi package.                                  |
// | Copyright (c) 2003, 2004 Sean Kerr.                                       |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.mojavi.org.                             |
// +---------------------------------------------------------------------------+

class LoginAction extends Action
{
	private $mail = '';
	private $pass = '';
	private $errors = array();
	
    public function execute ()
    {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		
		//login前のページを保存
		if(!$user->hasAttribute('member_before_login_page')){
			$member_before_login_page = array(
				'module' => $request->getParameter('module'),
				'action' => $request->getParameter('action'));
			$user->setAttribute('member_before_login_page', $member_before_login_page);
		}

		// logincheck		
		
		$memberManager = HNb::getHandler('MemberManager');
		if(!$member = $memberManager->login($user, $this->mail, $this->pass)){
			$this->errors[] = 'メールアドレスかパスワードが正しくありません';
			$this->setDefault();
			return View::INPUT;
		}
//		$user->setAttributes($member->getAttributes());
//		// TODO NameSpace Memberに汎用名で属性セット
//		$user->setAttribute('id', $member->getId(), 'Member');
		
		
		
		
		// login ok
		if($request->hasParameter('redirect_url')){
			// TODO
			$http_url = preg_replace('/\/$/', '', hitobito::getUrl());
			$url = $http_url . $request->getParameter('redirect_url');
			$controller->redirect($url);
			exit();
		}
		$member_before_login_page = $user->getAttribute('member_before_login_page');
		if($member_before_login_page['module'] != 'Member' || $member_before_login_page['action'] != 'Login'){
			$controller->forward($member_before_login_page['module'],
				$member_before_login_page['action']);
		}else{
			// 直前がログインページだったときの飛ばし先
			$http_url = hitobito::getUrl();
			if($user->getAttribute('member_type')=='navi'){
				$controller->redirect($http_url.'index.php?module=Navi&action=ShowNaviEdit');
			}else{
				$controller->redirect($http_url.'index.php?module=Member&action=Index');
			}
			exit();
			$controller->forward('Member', 'Index');
		}
        return View::NONE;

    }

    public function getDefaultView ()
    {

        return View::INPUT;

    }
	
	public function getRequestMethods()
	{
		
		return Request::POST;
	}

	public function validate()
	{
		$request = $this->getContext()->getRequest();
		$this->mail = trim($request->getParameter('member_login_mail'));
		$this->pass = trim($request->getParameter('member_login_pass'));
		if(empty($this->mail)){
			$this->errors[] = 'メールアドレスが未入力です';
		}
		if(empty($this->pass)){
			$this->errors[] = 'パスワードが未入力です';
		}
		if(count($this->errors) > 0){
			return FALSE;
		}
		return TRUE;
	}
	
	public function handleError()
	{
		$this->setDefault();
		
		return View::INPUT;
	}
	
	private function setDefault()
	{
		$request = $this->getContext()->getRequest();
		$request->setAttribute('member_login_mail', $this->mail);
		$request->setAttribute('member_login_pass', $this->pass);
		$request->setAttribute('member_errors', $this->errors);
	}
}

?>
