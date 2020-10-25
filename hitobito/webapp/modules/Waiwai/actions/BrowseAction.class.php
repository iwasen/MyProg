<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ��������������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class BrowseAction extends WaiwaiBaseAction {
	// �������
	public function initialize($context) {
		if (!parent::initialize($context))
			return false;

		$this->checkRoomStatus();

		return true;
	}

	// �����������¹�
	public function execute() {
		return View::NONE;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		// �ʥ�ID�����å�
		if (!$this->checkNaviPageId() || !$this->room_id) {
			$controller->forward('Navi', 'ShowNaviPage');
			return View::NONE;
		}

		$seq_no = $request->getParameter('waiwai_id');
		$view = $request->getParameter('view');

		// ������ƤΤ�ɽ��
		if ($seq_no && !$view) {

			// ȯ����������
			$this->setContents(true);

			// ����ȯ���������
			$this->setRemarkLink($seq_no);

		// ��ƥꥹ�ȤΤ�ɽ��
		} else {

			// ������������
			$this->setBrowseCommon();
		}

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::NONE;
	}

	// ���������remark_id ����
	private function setRemarkLink($seq_no) {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		$display_type = $user->getAttribute('waiwai_display_type');

		$remark_id = $this->db->getRemarkId($this->room_id, $seq_no);

		$remark_ary = array();

		// ����å�ɽ��
		if ($display_type == 'thread')
			$this->getThreadRemarkID($remark_ary, $remark_id);

		// ��ƽ�ɽ��
		else
			$this->getTimeRemarkID($remark_ary, $remark_id);

		$current = array_search($remark_id, $remark_ary);

		if (in_array($remark_id, $remark_ary)) {
			$links['befor_remark_id'] = $remark_ary[$current - 1];
			$links['next_remark_id'] = $remark_ary[$current + 1];
		}

		$request->setAttribute('waiwai_links', $links);

		// ���ߤΥڡ�������
		$page_size = $user->getAttribute('waiwai_page_size');
		$page_size =  $page_size ? $page_size : 50;
		$remark_order = $current + 1;
		$current_page = ceil($remark_order / $page_size);
		$user->setAttribute('waiwai_current_page', $current_page);

	}

	// ������åɽ�remark_id����
	private function getThreadRemarkID(&$ary, $remark_id, $break_flag = false, $parent_remark_id = 0, $count = 0) {

		$rec_ary = $this->db->getChildrenList($this->room_id, $parent_remark_id);

		$nrow = count($rec_ary);
		for ($i = 0; $i < $nrow;$i++) {

			$rec = &$rec_ary[$i];

			if ($rec['open_flag'] == 1) {

				$ary[] = $rec['remark_id'];

				if ($break_flag)
					break;

				if ($remark_id == $rec['remark_id']) {
					$break_flag = true;
				}

				$count++;
			}

			if ($rec['child_num']) {
					$count = $this->getThreadRemarkID($ary, $remark_id, $break_flag, $rec['remark_id'], $count);
			}
		}
		return $count;
	}

	// ����ƽ�remark_id����
	protected function getTimeRemarkID (&$ary, $remark_id) {
		$rec_ary = $this->db->getTimeRemarkID($this->room_id);

		$break_flag = false;
		foreach ($rec_ary as $rec) {
				$ary[] = $rec['remark_id'];

			if ($break_flag)
				break;

			if ($remark_id == $rec['remark_id']) {
				$break_flag = true;
			}
		}
	}
	
}
?>