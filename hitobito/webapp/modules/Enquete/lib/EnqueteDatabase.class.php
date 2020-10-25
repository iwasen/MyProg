<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �ǡ����١����������饹
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_LIB_DIR . '/HNbDatabase.class.php');

class EnqueteDatabase extends HNbDatabase {
	// ���ʥӥڡ���ID���餽�ΥʥӤΥ��С�ID����
	public function getMemberIdFromNaviPageId($navi_page_id) {
		$sql = "SELECT nav_member_id AS member_id"
			. " FROM t_navi_page"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// �����󥱡���ID�����������å�
	public function checkEnqueteId($enquete_id, $member_id, $status) {
		$open_cond = $this->getOpenCond($member_id);

		if (is_numeric($enquete_id)) {
			$sql = "SELECT nen_enquete_id FROM t_navi_enquete WHERE nen_enquete_id=$enquete_id AND nen_status IN ($status) AND $open_cond";
			if ($this->db->GetOne($sql))
				return true;	// OK
		}
		return false;	// NG
	}

	// �����󥱡��Ȱ�������
	public function getEnqueteList($navi_page_id) {
		$sql = "SELECT"
				. " nen_enquete_id AS enquete_id"
				. ",nen_status AS status"
				. ",nen_title AS title"
				. ",nen_start_date AS start_date"
				. ",nen_order AS order"
			. " FROM t_navi_enquete"
			. " WHERE nen_navi_page_id=$navi_page_id AND nen_status<>9"
			. " ORDER BY nen_order";
		return $this->db->GetAll($sql);
	}

	// �����󥱡�����Ͽ�������
	public function getEnqueteData($enquete_id) {
		$sql = "SELECT"
				. " nen_enquete_id AS enquete_id"
				. ",nen_status AS status"
				. ",nen_title AS title"
				. ",nen_question_text AS question_text"
				. ",nen_question_type AS question_type"
				. ",nen_comment_flag AS comment_flag"
				. ",nen_trackback_flag AS trackback_flag"
				. ",nen_start_date AS start_date"
				. ",nen_end_date AS end_date"
				. ",nen_open_flag AS open_flag"
			. " FROM t_navi_enquete"
			. " WHERE nen_enquete_id=$enquete_id";
		return $this->db->GetRow($sql);
	}

	// �����󥱡����������ܼ���
	public function getOptionData($enquete_id) {
		$sql = "SELECT"
				. " neo_option_no AS option_no"
				. ",neo_option_text AS option_text"
			. " FROM t_navi_enq_option"
			. " WHERE neo_enquete_id=$enquete_id"
			. " ORDER BY neo_option_no";
		return $this->db->GetAll($sql);
	}

	// �����󥱡�����Ͽ�����ɲ�
	public function insertEnqueteData($navi_page_id, $enquete_data, $option_data) {
		$this->db->BeginTrans();

		// ���󥱡�����Ͽ����
		$rec = array();
		$rec['nen_status'] = $this->getEnqueteStatus($enquete_data);
		$rec['nen_navi_page_id'] = $navi_page_id;
		$rec['nen_title'] = $enquete_data['title'];
		$rec['nen_question_text'] = $enquete_data['question_text'];
		$rec['nen_question_type'] = $enquete_data['question_type'];
		$rec['nen_comment_flag'] = $enquete_data['comment_flag'];
		$rec['nen_trackback_flag'] = $enquete_data['trackback_flag'];
		$rec['nen_start_date'] = $enquete_data['start_date'];
		$rec['nen_end_date'] = $enquete_data['end_date'];
		$rec['nen_order'] = "(SELECT COALESCE(max(nen_order),0)+1 FROM t_navi_enquete WHERE nen_navi_page_id=$navi_page_id)";
		$rec['nen_ng_word'] = $this->checkNGWord($enquete_data['title'] . $enquete_data['question_text']);
		$rec['nen_open_flag'] = $enquete_data['open_flag'];
		$rec['nen_pv_id'] = $this->getNewPageViewId();
		$this->db->AutoExecute('t_navi_enquete', $rec, 'INSERT');
		$enquete_id = $this->getCurrentSequence('t_navi_enquete', 'nen_enquete_id');

		// ���󥱡�����������
		$this->updateOptionData($enquete_id, $option_data);

		// ���󥱡��Ȳ����Կ�
		$rec = array();
		$rec['xxx_enquete_id'] = $enquete_id;
		$this->db->AutoExecute('c_navi_enquete', $rec, 'INSERT');

		$this->db->CommitTrans($option_data);

		return $enquete_id;
	}

	// �����󥱡�����Ͽ���󹹿�
	public function updateEnqueteData($enquete_data, $option_data) {
		$enquete_id = $enquete_data['enquete_id'];

		if ($enquete_id) {
			$this->db->BeginTrans();

			$rec = array();
			$rec['nen_status'] = $this->getEnqueteStatus($enquete_data);
			$rec['nen_title'] = $enquete_data['title'];
			$rec['nen_question_text'] = $enquete_data['question_text'];
			$rec['nen_question_type'] = $enquete_data['question_type'];
			$rec['nen_comment_flag'] = $enquete_data['comment_flag'];
			$rec['nen_trackback_flag'] = $enquete_data['trackback_flag'];
			$rec['nen_start_date'] = $enquete_data['start_date'];
			$rec['nen_end_date'] = $enquete_data['end_date'];
			$rec['nen_open_flag'] = $enquete_data['open_flag'];
			$this->db->AutoExecute('t_navi_enquete', $rec, 'UPDATE', "nen_enquete_id={$enquete_data['enquete_id']}");

			$this->updateOptionData($enquete_data['enquete_id'], $option_data);

			$this->db->CommitTrans();
		}

		return $enquete_id;
	}

	// �����󥱡��ȥ��ơ�����
	private function getEnqueteStatus($enquete_data) {
		$start_date = strtotime($enquete_data['start_date']);
		$end_date = strtotime($enquete_data['end_date']) + 24 * 60 * 60;
		$now = time();

		if ($now < $start_date)
			$status = 1;
		elseif ($now < $end_date)
			$status = 2;
		else
			$status = 3;

		return $status;
	}

	// �����󥱡����������ܹ���
	private function updateOptionData($enquete_id,  $option_data) {
		// ��¸���������
		$sql = "DELETE FROM t_navi_enq_option WHERE neo_enquete_id=$enquete_id";
		$this->db->Execute($sql);

		// ���������ɲ�
		if (is_array($option_data)) {
			$option_no = 1;
			foreach ($option_data as $option) {
				if ($option['option_text'] != '') {
					$rec = array();
					$rec['neo_enquete_id'] = $enquete_id;
					$rec['neo_option_no'] = $option_no++;
					$rec['neo_option_text'] = $option['option_text'];
					$this->db->AutoExecute('t_navi_enq_option', $rec, 'INSERT');
				}
			}
		}

		// ���󥱡��Ȳ����������
		$sql = "INSERT INTO t_navi_enq_answer (nea_enquete_id,nea_option_no)"
				. " SELECT neo_enquete_id,neo_option_no"
				. " FROM t_navi_enq_option"
				. " WHERE neo_enquete_id=$enquete_id AND NOT EXISTS (SELECT * FROM t_navi_enq_answer WHERE nea_enquete_id=neo_enquete_id AND nea_option_no=neo_option_no)";
		$this->db->Execute($sql);
	}

	// �����󥱡��Ȥ�岼�˰�ư
	public function moveEnquete($navi_page_id, $enquete_id, $dir_flag) {
		// ���ꤷ�����󥱡��Ȥ����
		$sql = "SELECT nen_enquete_id,nen_order FROM t_navi_enquete WHERE nen_enquete_id=$enquete_id";
		$row1 = $this->db->GetRow($sql);

		if ($dir_flag) {
			// �ҤȤľ�Υ��󥱡��Ȥ����
			$sql = "SELECT nen_enquete_id,nen_order"
				. " FROM t_navi_enquete"
				. " WHERE nen_navi_page_id=$navi_page_id AND nen_order<{$row1['nen_order']} AND nen_status<>9"
				. " ORDER BY nen_order DESC"
				. " LIMIT 1";
		} else {
			// �ҤȤĲ��Υ��󥱡��Ȥ����
			$sql = "SELECT nen_enquete_id,nen_order"
				. " FROM t_navi_enquete"
				. " WHERE nen_navi_page_id=$navi_page_id AND nen_order>{$row1['nen_order']} AND nen_status<>9"
				. " ORDER BY nen_order"
				. " LIMIT 1";
		}
		$row2 = $this->db->GetRow($sql);

		if ($row1 && $row2) {
			// �ҤȤľ�ޤ��ϲ��Υ��󥱡��Ȥ��¤ӽ���
			$this->db->BeginTrans();

			$rec = array();
			$rec['nen_order'] = $row2['nen_order'];
			$this->db->AutoExecute('t_navi_enquete', $rec, 'UPDATE', "nen_enquete_id={$row1['nen_enquete_id']}");

			$rec = array();
			$rec['nen_order'] = $row1['nen_order'];
			$this->db->AutoExecute('t_navi_enquete', $rec, 'UPDATE', "nen_enquete_id={$row2['nen_enquete_id']}");

			$this->db->CommitTrans();
		}
	}

	// �����󥱡��Ⱥ��
	public function deleteEnquete($enquete_id) {
		// ���ơ���������
		$sql = "SELECT nen_status FROM t_navi_enquete WHERE nen_enquete_id=$enquete_id";
		$status = $this->db->GetOne($sql);

		if ($status == 1) {
			// ̤�»ܤʤ�쥳���ɺ��
			$this->db->BeginTrans();

			$sql = "DELETE FROM t_navi_enquete WHERE nen_enquete_id=$enquete_id";
			$this->db->Execute($sql);

			$sql = "DELETE FROM t_navi_enq_option WHERE neo_enquete_id=$enquete_id";
			$this->db->Execute($sql);

			$sql = "DELETE FROM t_navi_enq_answer WHERE nea_enquete_id=$enquete_id";
			$this->db->Execute($sql);

			$sql = "DELETE FROM c_navi_enquete WHERE xxx_enquete_id=$enquete_id";
			$this->db->Execute($sql);

			$this->db->CommitTrans();
		} else {
			// �»ܺѤߤʤ饹�ơ����������Ѥߤ��ѹ�
			$rec = array();
			$rec['nen_status'] = 9;
			$this->db->AutoExecute('t_navi_enquete', $rec, 'UPDATE', "nen_enquete_id=$enquete_id");
		}
	}

	// �����󥱡��Ȳ����ɲ�
	public function insertAnswer($enquete_id, $member_id, $option_ary) {
		$seq_no = 0;

		if (is_array($option_ary)) {
			$this->db->BeginTrans();

			// �������󹹿�
			foreach ($option_ary as $option) {
				if ($option) {
					$rec = array();
					$rec['nea_answer_count'] = "nea_answer_count+1";
					$this->db->AutoExecute('t_navi_enq_answer', $rec, 'UPDATE', "nea_enquete_id=$enquete_id AND nea_option_no=$option");
				}
			}

			// �����Կ�����
			$rec = array();
			$rec['xxx_count'] = "xxx_count+1";
			$this->db->AutoExecute('c_navi_enquete', $rec, 'UPDATE', "xxx_enquete_id=$enquete_id");

			// ���������ɲ�
			$rec = array();
			$rec['nel_enquete_id'] = $enquete_id;
			$rec['nel_options'] = join(',', $option_ary);
			if ($member_id)
				$rec['nel_member_id'] = $member_id;
			$this->db->AutoExecute('l_navi_enq_answer', $rec, 'INSERT');
			$seq_no = $this->getCurrentSequence('l_navi_enq_answer', 'nel_seq_no');

			$this->db->CommitTrans();
		}

		return $seq_no;
	}

	// �����󥱡��Ȳ����Կ�����
	public function getAnswerCount($enquete_id) {
		$sql = "SELECT xxx_count FROM c_navi_enquete WHERE xxx_enquete_id=$enquete_id";
		return $this->db->GetOne($sql);
	}

	// �����󥱡���������̲���������
	public function getAnswerOption($enquete_id) {
		$sql = "SELECT"
				. " neo_option_no AS option_no"
				. ",neo_option_text AS option_text"
				. ",nea_answer_count AS answer_count"
			. " FROM t_navi_enq_option"
			. " JOIN t_navi_enq_answer ON nea_enquete_id=neo_enquete_id AND nea_option_no=neo_option_no"
			. " WHERE neo_enquete_id=$enquete_id"
			. " ORDER BY neo_option_no";
		return $this->db->GetAll($sql);
	}

	// �������Ⱦ������
	public function getCommentLog($enquete_id) {
		$sql = "SELECT"
				. " neo_comment_no AS comment_no"
				. ",neo_date AS date"
				. ",neo_user_name AS user_name"
				. ",neo_comment AS comment"
			. " FROM l_navi_enq_comment"
			. " WHERE neo_enquete_id=$enquete_id AND neo_open_flag=1"
			. " ORDER BY neo_comment_no";
		return $this->db->GetAll($sql);
	}

	// ���������ɲ�
	public function insertComment($enquete_id, $user_name, $comment, $seq_no = 0) {
		$ngword = $this->checkNGWord($user_name . $comment);
		if ($ngword != '')
			return false;

		if ($comment != '') {
			$this->db->BeginTrans();

			// �����Ƚ񤭹���
			$rec = array();
			$rec['neo_enquete_id'] = $enquete_id;
			$rec['neo_comment_no'] = "(SELECT COALESCE(max(neo_comment_no),0)+1 FROM l_navi_enq_comment WHERE neo_enquete_id=$enquete_id)";
			$rec['neo_user_name'] = $user_name;
			$rec['neo_comment'] = $comment;
			$rec['neo_ng_word'] = $ngword;
			$rec['neo_pv_id'] = $this->getNewPageViewId();
			$this->db->AutoExecute('l_navi_enq_comment', $rec, 'INSERT');
			$oid = $this->db->Insert_ID();

			if ($seq_no) {
				// ���󥱡��Ȳ����ȥ����Ȥ��б��դ�
				$rec = array();
				$rec['nel_comment_no'] = $this->db->GetOne("SELECT neo_comment_no FROM l_navi_enq_comment WHERE oid=$oid");
				$this->db->AutoExecute('l_navi_enq_answer', $rec, 'UPDATE', "nel_seq_no=$seq_no AND nel_comment_no IS NULL");
			}

			$this->db->CommitTrans();
		}

		return true;
	}

	// ���ȥ�å��Хå��������
	public function getTrackbackLog($enquete_id) {
		$sql = "SELECT"
				. " net_date AS date"
				. ",net_title AS title"
				. ",net_excerpt AS excerpt"
				. ",net_url AS url"
				. ",net_blog_name AS blog_name"
			. " FROM l_navi_enq_trackback"
			. " WHERE net_enquete_id=$enquete_id AND net_open_flag=1"
			. " ORDER BY net_trackback_no";
		return $this->db->GetAll($sql);
	}

	// ���ȥ�å��Хå��񤭹���
	public function insertTrackback($trackback) {
		$rec = array();
		$rec['net_enquete_id'] = $trackback['enquete_id'];
		$rec['net_trackback_no'] = "(SELECT COALESCE(max(net_trackback_no),0)+1 FROM l_navi_enq_trackback WHERE net_enquete_id={$trackback['enquete_id']})";
		$rec['net_title'] = $trackback['title'];
		$rec['net_url'] = $trackback['url'];
		$rec['net_blog_name'] = $trackback['blog_name'];
		$rec['net_excerpt'] = $trackback['excerpt'];
		$rec['net_ng_word'] = $this->checkNGWord($trackback['title'] . $trackback['blog_name'] . $trackback['excerpt']);
		$rec['net_pv_id'] = $this->getNewPageViewId();
		$this->db->AutoExecute('l_navi_enq_trackback', $rec, 'INSERT');
	}

	// �������楢�󥱡��ȿ�����
	public function getOpenEnqueteCount($channel_id, $navi_page_id, $member_id) {
		$open_cond = $this->getOpenCond($member_id);
		$channel_cond = ($channel_id && is_numeric($channel_id)) ? " AND nvp_channel_id=$channel_id" : '';
		$navi_page_cond = ($navi_page_id && is_numeric($navi_page_id)) ? " AND nen_navi_page_id=$navi_page_id" : '';

		$sql = "SELECT COUNT(*)"
			. " FROM t_navi_enquete"
			. " JOIN t_navi_page ON nvp_navi_page_id=nen_navi_page_id AND nvp_open_flag=1"
			. " WHERE nen_status IN (2,3) AND $open_cond $channel_cond $navi_page_cond";
		return $this->db->GetOne($sql);
	}

	// �������楢�󥱡��Ȱ�������
	public function getOpenEnqueteList($channel_id, $navi_page_id, $member_id, $limit, $offset, $status) {
		$open_cond = $this->getOpenCond($member_id);
		$channel_cond = ($channel_id && is_numeric($channel_id)) ? " AND nvp_channel_id=$channel_id" : '';
		$navi_page_cond = ($navi_page_id && is_numeric($navi_page_id)) ? " AND nen_navi_page_id=$navi_page_id" : '';

		$sql = "SELECT"
				. " nen_enquete_id AS enquete_id"
				. ",nen_navi_page_id AS navi_page_id"
				. ",nen_status AS status"
				. ",nen_title AS title"
				. ",nen_question_text AS question_text"
				. ",nen_start_date AS start_date"
				. ",nen_end_date AS end_date"
				. ",nen_order AS order"
			. " FROM t_navi_enquete"
			. " JOIN t_navi_page ON nvp_navi_page_id=nen_navi_page_id AND nvp_open_flag=1"
			. " WHERE nen_status IN ($status) AND $open_cond $channel_cond $navi_page_cond"
			. " ORDER BY nen_status,nen_start_date DESC"
			. " LIMIT $limit OFFSET $offset";
		return $this->db->GetAll($sql);
	}

	// ���ǿ��»��楢�󥱡���ID����
	public function getNewestEnquete($navi_page_id, $member_id) {
		$open_cond = $this->getOpenCond($member_id);

		$sql = "SELECT nen_enquete_id AS enquete_id"
			. " FROM t_navi_enquete"
			. " WHERE nen_status=2 AND $open_cond AND nen_navi_page_id=$navi_page_id"
			. " ORDER BY nen_order"
			. " LIMIT 1";
		return $this->db->GetOne($sql);
	}

	// ������������
	private function getOpenCond($member_id) {
		if ($member_id) {
			$sql = "SELECT * FROM t_navi_page JOIN t_room_member ON rmm_room_id=nvp_room_id AND rmm_member_id=$member_id WHERE nvp_navi_page_id=nen_navi_page_id AND rmm_status=3";
			$cond = "(nen_open_flag=1 OR EXISTS ($sql))";
		} else
			$cond = "nen_open_flag=1";

		return $cond;
	}

	// �����󥱡��Ȳ����ꥹ�ȼ���
	public function getAnswerList($enquete_id) {
		// �磻�磻��ļ�ID����
		$sql = "SELECT nvp_room_id"
			. " FROM t_navi_enquete"
			. " JOIN t_navi_page ON nvp_navi_page_id=nen_navi_page_id"
			. " WHERE nen_enquete_id=$enquete_id";
		$room_id = $this->db->GetOne($sql);
		if (!$room_id)
			$room_id = 0;

		$sql = "SELECT"
				. " nel_seq_no AS seq_no"
				. ",nel_enquete_id AS enquete_id"
				. ",nel_date AS date"
				. ",nel_options AS options"
				. ",nel_member_id AS member_id"
				. ",rmm_member_code AS member_code"
				. ",neo_user_name AS user_name"
				. ",neo_comment AS comment"
			. " FROM l_navi_enq_answer"
			. " LEFT JOIN t_room_member ON rmm_room_id=$room_id AND rmm_member_id=nel_member_id"
			. " LEFT JOIN l_navi_enq_comment ON neo_enquete_id=$enquete_id AND neo_comment_no=nel_comment_no"
			. " WHERE nel_enquete_id=$enquete_id"
			. " ORDER BY seq_no";
		return $this->db->GetAll($sql);
	}

	// �����󥱡���ID����磻�磻��ļ������ɼ���
	public function getRoomCode($enquete_id) {
		$sql = "SELECT rom_room_code"
			. " FROM t_navi_enquete"
			. " JOIN t_navi_page ON nvp_navi_page_id=nen_navi_page_id"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " WHERE nen_enquete_id=$enquete_id";
		return $this->db->GetOne($sql);
	}
}
?>