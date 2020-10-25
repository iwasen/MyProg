<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ���̤������ƥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class PostQuestionAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$post = $this->getParameters();
		if ($user->getAttribute('waiwai_post_flag')) {
			// �������ɻߥե饰�ꥻ�å�
			$user->setAttribute('waiwai_post_flag', false);

			// ȯ������
			$this->db->insertRoomPublic($this->room_id, $post);

			// �ʥӤإ᡼�������
			$this->sendNotifyMail($this->room_id, $post);
		}

		// ����ȯ����ɽ��
		$request->setAttribute('waiwai_post', $post);

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$user = $this->getContext()->getUser();

		// �������ɻߥե饰���å�
		$user->setAttribute('waiwai_post_flag', true);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('waiwai_nickname', true, '�˥å��͡��ब̤���ϤǤ���');
		$validatorManager->registerName('waiwai_mail_addr', true, '�᡼�륢�ɥ쥹��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_title', true, '�����ȥ뤬̤���ϤǤ���');
		$validatorManager->registerName('waiwai_content', true, '��ʸ��̤���ϤǤ���');
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		$params = $this->getParameters();

		if (!$this->checkMailaddr($params['mail_addr'])) {
			$request->setError('room_code', "�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���");
			return false;
		}

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ȯ����������
		$post = $this->getParameters();
		$request->setAttribute('waiwai_post', $post);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['nickname'] = $request->getParameter('waiwai_nickname');
		$params['mail_addr'] = $request->getParameter('waiwai_mail_addr');
		$params['title'] = $request->getParameter('waiwai_title');
		$params['content'] = $request->getParameter('waiwai_content');

		return $params;
	}

	// ���ʥӤإ᡼�������
	private function sendNotifyMail($room_id, &$post) {
		$basic_info = $this->db->getBasicInfo($room_id);
		if ($basic_info['public_user_notify_flag']) {
			$mail_addr = $this->db->getNaviMailaddr($this->navi_page_id);
			if ($mail_addr != '') {
				$template = $this->db->getMailTemplate('room_public');
				$body = $template['body'];
				$user_page_url = $this->db->getConst('user_page_url');

				$body = str_replace('%ROOM_NAME%', $basic_info['name'], $body);
				$body = str_replace('%SUBJECT%', $post['title'], $body);
				$body = str_replace('%NICKNAME%', $post['nickname'], $body);
				$body = str_replace('%BODY%', $post['content'], $body);
				$body = str_replace('%NAVIEDIT_URL%', "${user_page_url}index.php?module=Waiwai&action=ListPublic&navipage_id=$this->navi_page_id", $body);

				$send_mail_id = $this->db->setSendMailData($template['subject'], $template['from'], $template['reply_to'], $body);
				$this->db->setSendMailList($send_mail_id, $mail_addr);
				$this->db->setSendMailOk($send_mail_id);
			}
		}
	}
}
?>