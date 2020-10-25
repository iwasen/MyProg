<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �ӥ塼���ܥ��饹
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteDatabase.class.php');

abstract class EnqueteBaseAction extends Action {
	protected $db;
	protected $channel_id;
	protected $navi_page_id;
	protected $member_id;

	// �������
	public function initialize($context) {
		parent::initialize($context);

		$user = $context->getUser();

		// �ʥӥڡ���ID�����������å�
		if ($this->isNaviEditPage())
			Navi::GuardNaviEdit($user);

		// �ǡ����١�����³
		$this->db = new EnqueteDatabase();

		// �ʥӥڡ���ID����
		$request = $context->getRequest();
		$this->navi_page_id = $request->getParameter('navipage_id');
		$request->setAttribute('navi_page_id', $this->navi_page_id);

		// ����ͥ�ID����
		$this->channel_id = $request->getParameter('category_id');
		$request->setAttribute('channel_id', $this->channel_id);

		// ���С�ID����
		$this->member_id = $user->isAuthenticated() ? $user->getAttribute('mbr_member_id') : null;

		return true;
	}

	// ���ʥӤΥ��С�ID����
	protected function getNaviMemberId() {
		return $this->db->getMemberIdFromNaviPageId($this->navi_page_id);
	}

	// ��submit�ܥ�������å�
	protected function getSubmitButton() {
		$request = $this->getContext()->getRequest();

		// ̾����"_btn_x"�ǽ���äƤ������submit�ܥ���Ȥߤʤ�
		$buttons = $request->getParameterNames();
		if (is_array($buttons)) {
			foreach ($buttons as $button) {
				if (preg_match('/^(.+_btn)_x$/', $button, $matches))
					return $matches[1];
			}
		}

		return null;
	}

	// �����դΥѥ�᡼�������
	protected function getDateParameter($name) {
		$request = $this->getContext()->getRequest();

		return $request->getParameter($name . '_year') . '-' . $request->getParameter($name . '_month') . '-' . $request->getParameter($name . '_day');
	}

	// �����դ������������å�
	protected function checkDate($date) {
		$time = strtotime($date);

		sscanf($date, "%d-%d-%d", &$y, &$m, &$d);

		if ($y != date('Y', $time) || $m != date('n', $time) || $d != date('j', $time))
			return 0;

		return $time;
	}

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return false;
	}
}
?>