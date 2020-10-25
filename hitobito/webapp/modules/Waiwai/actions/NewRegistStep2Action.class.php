<?php
/**
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class NewRegistStep2Action extends WaiwaiBaseAction {
	private $mail = '';
	private $pass = '';
	private $errors = array();

	public function initialize($context)
	{
		parent::initialize($context);
		if (!$this->checkNaviPageId())
			return FALSE;
		$this->setBrowseCommon();
		return TRUE;
	}	
	
    public function execute ()
    {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		


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
		$url = 'index.php?module=Waiwai&action=Browse&navipage_id='.hitobito::getNaviPageInfo()->getId();
		$controller->redirect($url);
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