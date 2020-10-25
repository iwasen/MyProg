<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ȯ��Ͽ��������ɥ��������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class DownloadRecordAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		$csv_ary = array();

		$log_ary = $this->db->getRemarkLog($this->room_id);

		// ����å�
		$thread_ary = array();
		foreach ($log_ary as $log)
			$thread_ary[$log['parent_remark_id']][] = $log;

		// �ȥԥå����Ф�
		$topic_ary = array();
		$member_remark_ary = array();
		foreach ($log_ary as $log) {
			if ($log['parent_remark_id'] == 0) {
				$content_ary = array();
				$count_ary = array();
				$this->getRecordAry($log, $thread_ary, $content_ary, $count_ary);
				$topic_ary[] = array('subject' => '��' . $log['subject'], 'content' => $content_ary);
			}

			$member_remark_ary[$log['member_id']] = true;
		}

		// �˥å��͡������
		$csv_row = array();
		$csv_row[] = '�ȥԥå�';
		$nickname_ary = $this->db->getNicknameList($this->room_id, $this->getNaviMemberId());
		$nickname_ary[] = array('member_id' => 0, 'nickname' => '������Ƽ�');
		$member_id_ary = array();
		foreach ($nickname_ary as $nickname) {
			if (isset($member_remark_ary[$nickname['member_id']])) {
				$csv_row[] = $nickname['nickname'];
				$member_id_ary[] = $nickname['member_id'];
			}
		}
		$csv_ary[] = $csv_row;

		// ȯ��Ͽ�����
		foreach ($topic_ary as $topic) {
			$subject = $topic['subject'];

			foreach ($topic['content'] as $content) {
				$csv_row = array();

				// ��̾�����
				$csv_row[] = $subject;
				$subject = '';

				foreach ($member_id_ary as $member_id)
					$csv_row[] = isset($content[$member_id]) ? str_replace("\r", '', $content[$member_id]) : '';

				$csv_ary[] = $csv_row;
			}
		}

		// �ǡ�����ӥ塼���Ϥ�
		$request->setAttribute('waiwai_download_data', $csv_ary);

		return View::SUCCESS;
	}

	private function getRecordAry($log, $thread_ary, &$content_ary, &$count_ary) {
		$member_id = $log['member_id'];
		$count = isset($count_ary[$member_id]) ? $count_ary[$member_id] : 0;
		$count_ary[$member_id] = $count + 1;

		$content_ary[$count][$log['member_id']] = $log['content'];

		if (isset($thread_ary[$log['remark_id']])) {
			foreach ($thread_ary[$log['remark_id']] as $log2)
				$this->getRecordAry($log2, $thread_ary, $content_ary, &$count_ary);
		}
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		return View::NONE;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::GET | Request::POST;
	}

	// ��ǧ�ڤ��׵�
    public function isSecure() {
        return true;
    }

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return true;
	}
}
?>