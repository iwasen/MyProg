<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ɥ��ޥ���Ͽ/������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class SubscriptionListAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$submit = $this->getSubmitButton();
		if (preg_match('/^mailmag_cancel_([0-9]+)_btn$/', $submit, $matches)) {
			$this->db->deleteMelmagaMember2($matches[1], $this->member_id);
			$request->setAttribute('mailmag_msg', '���ޥ��ι��ɤ������ޤ�����');
		}

		return $this->getDefaultView();
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		$mailmag_subscription_list_ary = $this->db->getSubscriptionList($this->member_id);
		$request->setAttribute('mailmag_subscription_list_ary', $mailmag_subscription_list_ary);
		$request->setAttribute('user_page_url', $this->db->getConst('user_page_url'));

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��ǧ�ڤ��׵�
	public function isSecure() {
		return true;
	}
}
?>