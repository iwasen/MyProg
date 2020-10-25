<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ��Хå��ʥ�С�ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class _blockMailMagBacknumberAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		return View::NONE;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		if ($this->melmaga_id) {
			$mailmag = $this->db->getMailMagStatus($this->melmaga_id);
			if ($mailmag['status'] == 1) {
				if ($mailmag['backnumber'] == 1 || $mailmag['backnumber'] == 3) {
					$backnumber_ary = $this->db->getBacknumberList($this->melmaga_id, $mailmag['backnumber'] == 3 ? 1 : 5);
					if ($backnumber_ary) {
						$mailmag['backnumber_ary'] = $backnumber_ary;
						$request->setAttribute('mailmag', $mailmag);
						return View::SUCCESS;
					}
				}
			}
		}

		return View::NONE;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}
}
?>