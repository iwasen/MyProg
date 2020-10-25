<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author aizawa
 * @version
 */

class LoginFromAdminAction extends Action
{
	public function execute ()
	{
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$member_id = $request->getParameter('member_login_id');
		$pass = $request->getParameter('member_login_pass');

		$memberManager = HNb::getHandler('MemberManager');

		if ($member = $memberManager->get($member_id)) {
			$mail = $member->getEmail();

			if ($memberManager->login($user, $mail, $pass, false)) {
				// login ok
				if ($request->hasParameter('redirect_url')) {
					$controller->redirect($request->getParameter('redirect_url'));
					exit();
				}
			}
		}

		$request->setParameter('member_login_mail', '');
		$request->setParameter('member_login_pass', '');

		$controller->forward('Member', 'Login');
		return View::NONE;
	}
}
?>
