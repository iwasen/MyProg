<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �Хå��ʥ�С�����ƥ��ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';

class BacknumberContentsAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$contents_id = $request->getParameter('contents_id');
		$user_name = $request->getParameter('user_name');
		$comment = $request->getParameter('comment');

		$comment_set = false;

		switch ($this->getSubmitButton()) {
		case 'post_comment_btn':
			if ($user->getAttribute('mailmag_comment_time') == $request->getParameter('mailmag_comment_time')) {
				// �����Ƚ񤭹���
				if ($this->db->insertComment($contents_id, $user_name, $comment)) {
					// �֥�������Ƥ��꤬�Ȥ��������ޤ����פ�ɽ��
					$request->setAttribute('mailmag_comment_ok', true);

		            // ����������
					$mailmag_contents = $this->db->getMailMagContents($contents_id);
		            $notifyValues = array(
		            'POSTER' => $user_name,
		            'BODY' => $comment,
					'ENTRY_TITLE' => $mailmag_contents['subject'],
					'ENTRY_URL' => hitobito::getUrl() . "index.php?module=MailMag&action=BacknumberContents&contents_id=$contents_id"
		            );
		            $notifer = new NaviNotifyMail();
		            $notifer->notify(hitobito::getNaviPageInfo(), 'comment', $notifyValues);
				} else {
					// ����Ƥ˺ݤ����Ϥ򤴱�θ���������������դ��Ϥ��äƤ��뤿�ᡢ����Ƥ�ȿ�ǤǤ��ޤ���Ǥ����פ�ɽ��
					$request->setAttribute('mailmag_comment_ng', true);
				}
			}
			break;
		case 'key_change_btn':
			$comment_set = true;
			break;
		}

		return $this->setBacknumberContents(false, $comment_set);
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		return $this->setBacknumberContents(true, false);
	}

	// ���Хå��ʥ�С�����ƥ������
	private function setBacknumberContents($page_view_count_flag, $comment_set) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		$contents_id = $request->getParameter('contents_id');

		if ($this->melmaga_id) {
			$mailmag = $this->db->getBacknumberInfo($this->melmaga_id);
			if ($mailmag['status'] == 1) {
				if ($mailmag['backnumber'] == 1 || $mailmag['backnumber'] == 3) {
					$mailmag['contents'] = $this->db->getBacknumberContents($contents_id);
					$mailmag['comment_ary'] = $this->db->getBacknumberComment($contents_id);
					$mailmag['trackback_ary'] = $this->db->getBacknumberTrackback($contents_id);
					$mailmag['trackback_url'] = $this->db->getConst('user_page_url') . "?module=MailMag&action=ReceiveTrackback&contents_id=$contents_id";

					// �ڡ����ӥ塼�������
					if ($page_view_count_flag)
						$this->db->countPageView($mailmag['contents']['pv_id']);
				}
			}

			// �����Ⱦ�������
			if ($comment_set) {
				$mailmag['user_name'] = $request->getParameter('user_name');
				$mailmag['comment'] = $request->getParameter('comment');
			}

			$request->setAttribute('mailmag', $mailmag);
		}

		// ¿����Ƥ��ɻߥե饰����
		$time = time();
		$user->setAttribute('mailmag_comment_time', $time);
		$request->setAttribute('mailmag_comment_time', $time);

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// �����ϥ��顼����
	public function handleError() {
		return $this->setBacknumberContents(false, true);
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		if ($this->getSubmitButton() == 'post_comment_btn') {
			$validatorManager->registerName('user_name', TRUE, '̾�������Ϥ��Ƥ���������');
			$validatorManager->registerName('comment', TRUE, '�����Ȥ����Ϥ��Ƥ���������');
			$validatorManager->registerName('key_string', TRUE, '�������ʸ��������Ϥ��Ƥ���������');
		}
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		if ($this->getSubmitButton() == 'post_comment_btn') {
			$key_string = $request->getParameter('key_string');
			if (strcasecmp(mb_convert_kana($key_string, 'a'), $_SESSION['captcha_keystring']) != 0) {
				$request->setError('key_string', '�������ʸ��������������ޤ���');
				return false;
			}
		}

		unset($_SESSION['captcha_keystring']);

		return true;
	}
}
?>