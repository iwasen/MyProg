<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��ȷ��ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';

class ShowResultAction extends EnqueteBaseAction {
	// �����������¹�
	public function execute() {
		$user = $this->getContext()->getUser();
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');

		$comment_set = false;

		switch ($this->getSubmitButton()) {
		case 'enquete_post_comment_btn':
			if ($user->getAttribute('enquete_comment_flag') == $request->getParameter('enquete_comment_flag')) {
				// �����Ƚ񤭹���
				$ok = $this->db->insertComment($enquete_id, $request->getParameter('enquete_user_name'), $request->getParameter('enquete_comment'), $user->getAttribute('enquete_seq_no'));
				$user->removeAttribute('enquete_seq_no');

				if ($ok) {
					// �֥�������Ƥ��꤬�Ȥ��������ޤ����פ�ɽ��
					$request->setAttribute('enquete_comment_ok', true);

	                // ����������
					$enquete_data = $this->db->getEnqueteData($enquete_id);
	                $notifyValues = array(
	                'POSTER' => $request->getParameter('enquete_user_name'),
	                'BODY' => $request->getParameter('enquete_comment'),
					'ENTRY_TITLE' => $enquete_data['title'],
					'ENTRY_URL' => hitobito::getUrl() . "index.php?module=Enquete&action=ShowResult&enquete_id=$enquete_id"
	                );
	                $notifer = new NaviNotifyMail();
	                $notifer->notify(hitobito::getNaviPageInfo(), 'comment', $notifyValues);
				} else {
					// ����Ƥ˺ݤ����Ϥ򤴱�θ���������������դ��Ϥ��äƤ��뤿�ᡢ����Ƥ�ȿ�ǤǤ��ޤ���Ǥ����פ�ɽ��
					$request->setAttribute('enquete_comment_ng', true);
				}
			}
			break;
		case 'key_change_btn':
			$comment_set = true;
			break;
		}

		// ���󥱡��ȷ������
		$this->setEnqueteResult($comment_set);

		return View::INPUT;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');

		if (!$this->db->checkEnqueteId($enquete_id, $this->member_id, '2,3')) {
			// ���󥱡���ID��̵����а���ɽ����
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// ���󥱡��ȷ������
		$this->setEnqueteResult(false);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'enquete_post_comment_btn') {
			$validatorManager->registerName('enquete_user_name', TRUE, '̾�������Ϥ��Ƥ���������');
			$validatorManager->registerName('enquete_comment', TRUE, '�����Ȥ����Ϥ��Ƥ���������');
			$validatorManager->registerName('key_string', TRUE, '�������ʸ��������Ϥ��Ƥ���������');
		}
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'enquete_post_comment_btn') {
			$key_string = $request->getParameter('key_string');
			if (strcasecmp(mb_convert_kana($key_string, 'a'), $_SESSION['captcha_keystring']) != 0) {
				$request->setError('key_string', '�������ʸ��������������ޤ���');
				return false;
			}
		}

		unset($_SESSION['captcha_keystring']);

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		// ���󥱡��ȷ������
		$this->setEnqueteResult(true);

		return View::INPUT;
	}

	// �����󥱡��ȷ������
	private function setEnqueteResult($comment_set) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$enquete_id = $request->getParameter('enquete_id');
		if (!$enquete_id) {
			// ���󥱡���ID��̵����а���ɽ����
			$controller = $this->getContext()->getController();
			$controller->forward('Enquete', 'ListEnquete');
			return View::NONE;
		}

		// ���󥱡��ȷ������
		$enquete_data = $this->db->getEnqueteData($enquete_id);
		$enquete_data['option'] = $this->db->getAnswerOption($enquete_id);
		$enquete_data['answer_count'] = $this->db->getAnswerCount($enquete_id);
		$request->setAttribute('enquete_data', $enquete_data);

		// �����Ⱦ�������
		if ($comment_set) {
			$enquete_comment['user_name'] = $request->getParameter('enquete_user_name');
			$enquete_comment['comment'] = $request->getParameter('enquete_comment');
		}
		$enquete_comment['log'] = $this->db->getCommentLog($enquete_id);
		$request->setAttribute('enquete_comment', $enquete_comment);

		// �ȥ�å��Хå���������
		$enquete_trackback['url'] = $this->db->getConst('user_page_url') . "?module=Enquete&action=ReceiveTrackback&enquete_id=$enquete_id";
		$enquete_trackback['log'] = $this->db->getTrackbackLog($enquete_id);
		$request->setAttribute('enquete_trackback', $enquete_trackback);

		// ¿������ɻߥե饰����
		$time = time();
		$user->setAttribute('enquete_comment_flag', $time);
		$request->setAttribute('enquete_comment_flag', $time);
	}
}
?>