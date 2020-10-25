<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 データベース処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_LIB_DIR . '/HNbDatabase.class.php');

class WaiwaiDatabase extends HNbDatabase {
	// ■ナビページIDから会議室ID取得
	public function getRoomIdFromNaviPageId($navi_page_id) {
		$sql = "SELECT nvp_room_id AS room_id"
				. " FROM t_navi_page"
				. " WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// ■ナビページIDからそのナビのメンバーID取得
	public function getMemberIdFromNaviPageId($navi_page_id) {
		$sql = "SELECT nav_member_id AS member_id"
				. " FROM t_navi_page"
				. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
				. " WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// ■投稿件数取得
	public function getRemarkNum($room_id) {
		$sql = "SELECT COUNT(*) FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_status=1 AND rmk_disp_type=1 AND rmk_open_flag=1";
		return $this->db->GetOne($sql);
	}

	// ■子レスリスト取得
	public function getChildrenList($room_id, $parent_remark_id) {
		$sql = "SELECT rmk_remark_id AS remark_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_date AS date"
					. ",rmk_subject AS subject"
					. ",rmk_child_num AS child_num"
					. ",rmk_nickname AS nickname"
					. ",rmk_open_flag AS open_flag"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_parent_remark_id=$parent_remark_id AND rmk_status=1 AND rmk_disp_type=1"
				. " ORDER BY rmk_remark_id";
		return $this->db->GetAll($sql);
	}

	// ■投稿順リスト取得
	public function getTimeList($room_id, $limit, $offset) {
		$limit = $limit == 0 ? '' : "LIMIT $limit";
		$offset = "OFFSET $offset";

		$sql = "SELECT rmk_remark_id AS remark_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_subject AS subject"
					. ",rmk_date AS date"
					. ",rmk_room_id AS room_id"
					. ",rmk_member_id AS member_id"
					. ",rmk_nickname AS nickname"
					. ",rmk_open_flag AS open_flag"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_status=1 AND rmk_disp_type=1 AND rmk_open_flag=1"
				. " ORDER BY rmk_seq_no"
				. " $limit $offset";
		return $this->db->GetAll($sql);
	}

	// ■投稿順全ID取得
	public function getTimeRemarkID ($room_id) {
		$sql = "SELECT rmk_remark_id AS remark_id"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_status=1 AND rmk_disp_type=1 AND rmk_open_flag=1"
				. " ORDER BY rmk_seq_no";
		return $this->db->GetAll($sql);
	}

	// ■発言内容取得
	public function getContents($remark_id) {
		$sql = "SELECT rmk_remark_id AS remark_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_subject AS subject"
					. ",rmk_nickname AS nickname"
					. ",rmk_date AS date"
					. ",rmk_content AS content"
					. ",rmk_pv_id AS pv_id"
				. " FROM l_room_remark"
				. " WHERE rmk_remark_id=$remark_id";
		return $this->db->GetRow($sql);
	}

	// ■ワイワイ会議最新投稿取得
	public function getNewRemarks($channel_id, $room_id, $limit, $member_id = 0, $offset = 0) {
		if ($channel_id && is_numeric($channel_id))
			$channel_cond = " AND nvp_channel_id=$channel_id";
		else
			$channel_cond = '';

		if ($room_id && is_numeric($room_id))
			$room_cond = " AND rmk_room_id=$room_id";
		else
			$room_cond = '';

		if ($member_id && is_numeric($member_id))
			$status_cond = " AND (rom_status=1 OR rom_status=2 AND EXISTS (SELECT * FROM t_room_member WHERE rmm_room_id=rmk_room_id AND rmm_member_id=$member_id AND rmm_status=3))";
		else
			$status_cond = " AND rom_status=1";

		$sql = "SELECT rmk_remark_id AS remark_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_subject AS subject"
					. ",rmk_date AS date"
					. ",rmk_room_id AS room_id"
					. ",rmk_nickname AS nickname"
					. ",rmk_content AS content"
					. ",rom_name AS room_name"
					. ",nvp_navi_page_id AS navi_page_id"
					. ",trim(coalesce(nav_name1,'')||coalesce(nav_name2,'')) AS navi_name"
				. " FROM l_room_remark"
				. " JOIN t_room ON rom_room_id=rmk_room_id"
				. " JOIN t_navi_page ON nvp_room_id=rmk_room_id AND nvp_status=1 AND nvp_open_flag=1"
				. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
				. " WHERE rmk_status=1 AND rmk_disp_type=1 AND rmk_open_flag=1 AND rmk_keisai_flag=1 $channel_cond $room_cond $status_cond"
				. " ORDER BY rmk_remark_id DESC"
				. " LIMIT $limit OFFSET $offset";
		return $this->db->GetAll($sql);
	}

	// ■ニックネーム取得
	public function getNickname($room_id, $member_id) {
		$sql = "SELECT rmm_nickname AS nickname"
				. " FROM t_room_member"
				. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id";
		return $this->db->GetOne($sql);
	}

	// ■会議室概要取得
	public function getRoomOutline($room_id) {
		$sql = "SELECT rom_outline AS outline"
				. ", rom_name AS name"
				. ",(SELECT COUNT(*) FROM t_room_member WHERE rmm_room_id=$room_id) AS member_num"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■基本情報取得
	public function getBasicInfo($room_id) {
		$sql = "SELECT rom_name AS name"
					. ",rom_outline as outline"
					. ",rom_regist_mail_flag AS regist_mail_flag"
					. ",rom_resign_mail_flag AS resign_mail_flag"
					. ",rom_header_flag AS header_flag"
					. ",rom_footer_flag AS footer_flag"
					. ",rom_guest_regist_notify_flag AS guest_regist_notify_flag"
					. ",rom_public_user_notify_flag AS public_user_notify_flag"
					. ",rom_room_code AS room_code"
					. ",rom_status AS status"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■基本情報保存
	public function updateBasicInfo($room_id, $member_id, $data_ary) {
		$this->db->BeginTrans();

		// 会議室登録情報テーブル更新
		$rec = array();
		$rec['rom_name'] = $data_ary['name'];
		$rec['rom_outline'] = $data_ary['outline'];
		$rec['rom_regist_mail_flag'] = $data_ary['regist_mail_flag'];
		$rec['rom_resign_mail_flag'] = $data_ary['resign_mail_flag'];
		$rec['rom_header_flag'] = $data_ary['header_flag'];
		$rec['rom_footer_flag'] = $data_ary['footer_flag'];
		$rec['rom_guest_regist_notify_flag'] = $data_ary['guest_regist_notify_flag'];
		$rec['rom_public_user_notify_flag'] = $data_ary['public_user_notify_flag'];
//		$rec['rom_room_code'] = $data_ary['room_code'];
		$rec['rom_status'] = $data_ary['status'];
		$this->db->AutoExecute('t_room', $rec, 'UPDATE', "rom_room_id=$room_id");

		// 会議室参加メンバー（ニックネーム）更新
		$rec = array();
		$rec['rmm_nickname'] = $data_ary['nickname'];
		$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id=$member_id");

		$this->db->CommitTrans();
	}

	// ■ヘッダ・フッタ情報取得
	public function getHeaderInfo($room_id) {
		$sql = "SELECT rom_header AS header"
					. ",rom_footer AS footer"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■デフォルトヘッダ・フッタ取得
	public function getDefaultHeader() {
		$sql = "SELECT rmh_header AS header"
					. ",rmh_footer AS footer"
				. " FROM m_room_header"
				. " WHERE rmh_header_id=1";
		return $this->db->GetRow($sql);
	}

	// ■ヘッダ・フッタ情報更新
	public function updateHeaderInfo($room_id, $data_ary) {
		$rec = array();
		$rec['rom_header'] = $data_ary['header'];
		$rec['rom_footer'] = $data_ary['footer'];
		$this->db->AutoExecute('t_room', $rec, 'UPDATE', "rom_room_id=$room_id");
	}

	// ■会議室情報取得
	public function getRoomInfo($room_id) {
		$sql = "SELECT rom_room_code AS room_code"
					. ",rom_header_flag AS header_flag"
					. ",rom_footer_flag AS footer_flag"
					. ",rom_header AS header"
					. ",rom_footer AS footer"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■メンバー情報取得
	public function getMemberInfo($room_id, $member_id) {
		$sql = "SELECT rmm_member_code AS member_code"
					. ",rmm_nickname AS nickname"
					. ",mbr_mail_addr AS mail_addr"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id AND rmm_status=3";
		return $this->db->GetRow($sql);
	}

	// ■発言挿入処理
	public function insertRemark($room_id, $data_ary) {
		$this->db->BeginTrans();

		$rec = array();
		$rec['rmk_room_id'] = $data_ary['room_id'];
		$rec['rmk_member_id'] = $data_ary['member_id'];
		$rec['rmk_date'] = $data_ary['date'];
		$rec['rmk_parent_remark_id'] = $data_ary['parent_remark_id'];
		$rec['rmk_disp_type'] = $data_ary['disp_type'];
		$rec['rmk_disp_member_id'] = $data_ary['disp_member_id'];
		$rec['rmk_remark_type'] = $data_ary['remark_type'];
		$rec['rmk_seq_no'] = $data_ary['seq_no'];
		$rec['rmk_from'] = $data_ary['from'];
		$rec['rmk_message_id'] = $data_ary['message_id'];
		$rec['rmk_mail_header'] = $data_ary['mail_header'];
		$rec['rmk_subject'] = $data_ary['subject'];
		$rec['rmk_content'] = $data_ary['content'];
		$rec['rmk_room_header'] = $data_ary['room_header'];
		$rec['rmk_room_footer'] = $data_ary['room_footer'];
		$rec['rmk_web_mail'] = $data_ary['web_mail'];
		$rec['rmk_ng_word'] = $data_ary['ng_word'];
		$rec['rmk_pv_id'] = $data_ary['pv_id'];
		$rec['rmk_nickname'] = $data_ary['nickname'];
		$this->db->AutoExecute('l_room_remark', $rec, 'INSERT');

		$remark_id = $this->getCurrentSequence('l_room_remark', 'rmk_remark_id');

		// 子発言数更新
		$this->updateChildNum($data_ary['parent_remark_id'], 1);

		$this->db->CommitTrans();

		return $remark_id;
	}

	// ■子発言数更新処理
	private function updateChildNum($remark_id, $num = 0) {
		if ($remark_id) {
			// 子発言数を更新
			$rec = array();
			if ($num)
				$rec['rmk_child_num'] = "rmk_child_num + $num";
			else
				$rec['rmk_child_num'] = "(SELECT coalesce(sum(rmk_child_num)+count(nullif(rmk_open_flag,0)),0) FROM l_room_remark WHERE rmk_parent_remark_id=$remark_id AND rmk_status=1)";
			$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_remark_id=$remark_id");

			// 親も更新
			$sql = "SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$remark_id";
			$this->updateChildNum($this->db->GetOne($sql), $num);
		}
	}

	// ■差込変数情報取得
	public function getRoomVars($room_id) {
		$sql = "SELECT nvp_title AS title"
					. ",rom_name AS name"
					. ",rom_room_code AS room_code"
					. ",nav_name1 AS name1"
					. ",nav_name2 AS name2"
					. ",rom_room_code AS room_code"
				. " FROM t_room"
				. " JOIN t_navi_page ON nvp_room_id=rom_room_id"
				. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■ゲスト登録メール情報取得
	public function getGuestMail($room_id) {
		$sql = "SELECT rom_guest_regist_mail AS guest_mail"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■ゲスト登録メール情報更新
	public function updateGuestMail($room_id, $data_ary) {
		$rec = array();
		$rec['rom_guest_regist_mail'] = $data_ary['guest_mail'];
		$this->db->AutoExecute('t_room', $rec, 'UPDATE', "rom_room_id=$room_id");
	}

	// ■入会者メール情報取得
	public function getRegistMail($room_id) {
		$sql = "SELECT rom_regist_mail AS regist_mail"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■入会者メール情報更新
	public function updateRegistMail($room_id, $data_ary) {
		$rec = array();
		$rec['rom_regist_mail'] = $data_ary['regist_mail'];
		$this->db->AutoExecute('t_room', $rec, 'UPDATE', "rom_room_id=$room_id");
	}

	// ■退会者メール情報取得
	public function getResignMail($room_id) {
		$sql = "SELECT rom_resign_mail AS resign_mail"
				. " FROM t_room"
				. " WHERE rom_room_id=$room_id";
		return $this->db->GetRow($sql);
	}

	// ■退会者メール情報更新
	public function updateResignMail($room_id, $data_ary) {
		$rec = array();
		$rec['rom_resign_mail'] = $data_ary['resign_mail'];
		$this->db->AutoExecute('t_room', $rec, 'UPDATE', "rom_room_id=$room_id");
	}

	// ■メンバーリスト取得
	public function getMemberList($room_id, $admin_flag, $sort_field) {
		$sb1 = "SELECT rmk_member_id, COUNT(*) AS remark_num, MAX(rmk_date) AS last_date"
					. " FROM l_room_remark"
					. " WHERE rmk_room_id=$room_id AND rmk_status=1"
					. " GROUP BY rmk_member_id";

		$sql = "SELECT rmm_member_id AS member_id"
					. ",mbr_mail_addr AS mail_addr"
					. ",rmm_nickname AS nickname"
					. ",COALESCE(remark_num,0) AS remark_num"
					. ",last_date"
					. ",rmm_mail_send_flag AS mail_send_flag"
					. ",rmm_regist_date AS regist_date"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " LEFT JOIN ($sb1) sb1 ON rmk_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_status=3 AND rmm_admin_flag=$admin_flag"
				. " ORDER BY $sort_field";
		return $this->db->GetAll($sql);
	}

	// ■メンバー属性情報取得
	public function getMemberAttribute($member_id, $room_id) {
		if ($member_id) {
			$sql = "SELECT mbr_name AS name"
				. ",rom_room_code AS room_code"
				. ",rmm_member_code AS member_code"
				. ",mbr_name_kana AS name_kana"
				. ",mbr_sex_cd AS sex_cd"
				. ",mbr_birthday AS birthday"
				. ",mbr_zip_cd AS zip_cd"
				. ",mbr_address1 AS address1"
				. ",mbr_address2 AS address2"
				. ",mbr_tel AS tel"
				. ",mbr_self_introduction AS self_introduction"
				. " FROM t_member LEFT JOIN t_room_member ON mbr_member_id=rmm_member_id LEFT JOIN t_room ON rmm_room_id=rom_room_id"
				. " WHERE mbr_member_id=$member_id AND rmm_room_id=$room_id" ;
			return $this->db->GetRow($sql);
		}
	}

	// ■管理者フラグ更新
	public function updateAdminFlag($room_id, $member_id_ary, $admin_flag) {
		$member_id = join(',', $member_id_ary);
		if ($member_id != '') {
			$rec = array();
			$rec['rmm_admin_flag'] = $admin_flag;
			$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id IN ($member_id) AND rmm_admin_flag<>$admin_flag");
		}
	}

	// ■メール配信フラグ更新
	public function updateMailSendFlag($room_id, $member_id_ary, $mail_send_flag) {
		$member_id = join(',', $member_id_ary);
		if ($member_id != '') {
			$rec = array();
			$rec['rmm_mail_send_flag'] = $mail_send_flag;
			$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id IN ($member_id) AND rmm_mail_send_flag<>$mail_send_flag");
		}
	}

	// ■メンバー登録
	public function registMember($room_id, $member_id_ary) {
		$member_id = join(',', $member_id_ary);
		if ($member_id != '') {
			$rec = array();
			$rec['rmm_status'] = 3;
			$rec['rmm_regist_date'] = 'now';
			$rec['rmm_resign_date'] = null;
			$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id IN ($member_id) AND rmm_status<>3");
		}
	}

	// ■メンバー解除
	public function resignMember($room_id, $member_id_ary) {
		$member_id = join(',', $member_id_ary);
		if ($member_id != '') {
			$rec = array();
			$rec['rmm_status'] = 9;
			$rec['rmm_resign_date'] = 'now';
			$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id IN ($member_id) AND rmm_status<>9");
		}
	}

	// ■ゲストメンバーリスト取得
	public function getGuestList($room_id, $sort_field) {
		$sql = "SELECT rmm_member_id AS member_id"
					. ",mbr_mail_addr AS mail_addr"
					. ",rmm_nickname AS nickname"
					. ",rmm_mail_send_flag AS mail_send_flag"
					. ",rmm_regist_date AS regist_date"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_status=2"
				. " ORDER BY $sort_field";
		return $this->db->GetAll($sql);
	}

	// ■メンバー削除
	public function deleteMember($room_id, $member_id_ary) {
		$member_id = join(',', $member_id_ary);
		if ($member_id != '') {
			$sql = "DELETE FROM t_room_member WHERE rmm_room_id=$room_id AND rmm_member_id IN ($member_id)";
			$this->db->Execute($sql);
		}
	}

	// ■発言更新
	public function updateRemark($remark_id, $subject, $content) {
		$rec = array();
		$rec['rmk_subject'] = $subject;
		$rec['rmk_content'] = $content;
		$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_remark_id=$remark_id");
	}

	// ■発言削除
	public function deleteRemark($remark_id) {
		$this->db->BeginTrans();

		// 親発言ID取得
		$sql = "SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$remark_id";
		$parent_remark_id = $this->db->GetOne($sql);

		// 発言更新
		$rec = array();
		$rec['rmk_status'] = 9;
		$rec['rmk_parent_remark_id'] = null;
		$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_remark_id=$remark_id");

		// スレッドの子を親に接続
		$rec = array();
		$rec['rmk_parent_remark_id'] = $parent_remark_id;
		$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_parent_remark_id=$remark_id");

		// 子発言数更新
		$this->updateChildNum($parent_remark_id);

		$this->db->CommitTrans();
	}

	// ■発言ID取得
	public function getRemarkId($room_id, $seq_no) {
		$sql = "SELECT rmk_remark_id FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_disp_type=1 AND rmk_seq_no=$seq_no AND rmk_status=1";
		return $this->db->GetOne($sql);
	}

	// ■親子関係チェック
	public function checkParentChild($room_id, $from_id, $to_id) {
		$sql = "SELECT rmk_parent_remark_id"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_disp_type=1 AND rmk_remark_id=$to_id AND rmk_status=1";
		$parent_remark_id = $this->db->GetOne($sql);

		if ($parent_remark_id == 0)
			return true;
		elseif ($parent_remark_id == $from_id)
			return false;
		else
			return $this->checkParentChild($room_id, $from_id, $parent_remark_id);
	}

	// ■スレッド移動
	public function moveThread($from_id, $to_id, $option) {
		$this->db->BeginTrans();

		// 移動元の親IDを取得
		$sql = "SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$from_id";
		$parent_from_id = $this->db->GetOne($sql);

		// 指定した発言のみの場合はその子を自分の親に接続
		if ($option == 1) {
			// 対象の親発言IDを取得
			$rec = array();
			$rec['rmk_parent_remark_id'] = "(SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$from_id)";
			$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_parent_remark_id=$from_id");
		}

		// スレッドの移動
		$rec = array();
		$rec['rmk_parent_remark_id'] = $to_id;
		$this->db->AutoExecute('l_room_remark', $rec, 'UPDATE', "rmk_remark_id=$from_id");

		// 子発言数更新
		$this->updateChildNum($parent_from_id);
		$this->updateChildNum($from_id);

		$this->db->CommitTrans();
	}

	// ■一般からの投稿一覧取得
	public function getPublicList($room_id) {
		$sql = "SELECT rmp_seq_no AS seq_no"
					. ",rmp_status AS status"
					. ",rmp_date AS date"
					. ",rmp_mail_addr AS mail_addr"
					. ",rmp_nickname AS nickname"
					. ",rmp_title AS title"
					. ",rmp_content AS content"
				. " FROM t_room_public"
				. " WHERE rmp_room_id=$room_id"
				. " ORDER BY rmp_seq_no DESC";
		return $this->db->GetAll($sql);
	}

	// ■一般ユーザ投稿取得
	public function getPublicContents($room_id, $seq_no) {
		$sql = "SELECT rmp_status AS status"
					. ",rmp_date AS date"
					. ",rmp_mail_addr AS mail_addr"
					. ",rmp_nickname AS nickname"
					. ",rmp_title AS title"
					. ",rmp_content AS content"
				. " FROM t_room_public"
				. " WHERE rmp_room_id=$room_id AND rmp_seq_no=$seq_no";
		return $this->db->GetRow($sql);
	}

	// ■一般ユーザ投稿ステータス更新
	public function updatePublicStatus($room_id, $seq_no, $status) {
		$rec = array();
		$rec['rmp_status'] = $status;
		$this->db->AutoExecute('t_room_public', $rec, 'UPDATE', "rmp_room_id=$room_id AND rmp_seq_no=$seq_no");
	}

	// ■一般からの投稿を削除
	public function deletePublic($room_id, $seq_no) {
		$sql = "DELETE FROM t_room_public WHERE rmp_room_id=$room_id AND rmp_seq_no=$seq_no";
		$this->db->Execute($sql);
	}

	// ■発言ログ取得
	public function getRemarkLog($room_id) {
		$sql = "SELECT rmk_remark_id AS remark_id"
					. ",rmk_parent_remark_id AS parent_remark_id"
					. ",rmk_member_id AS member_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_date AS date"
					. ",rmk_subject AS subject"
					. ",rmk_content AS content"
					. ",rmk_nickname AS nickname"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_disp_type=1 AND rmk_status=1 AND rmk_open_flag=1"
				. " ORDER BY rmk_seq_no";
		return $this->db->GetAll($sql);
	}

	// ■発言集計取得
	public function getRemarkCount($room_id) {
		$sql = "SELECT rmk_nickname AS nickname"
					. ",COUNT(*) AS count1"
					. ",COUNT(DISTINCT(date_trunc('day', rmk_date))) AS count2"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$room_id AND rmk_disp_type=1 AND rmk_status=1 AND rmk_open_flag=1"
				. " GROUP BY rmk_nickname"
				. " ORDER BY rmk_nickname";
		return $this->db->GetAll($sql);
	}

	// ■ニックネーム一覧取得
	public function getNicknameList($room_id, $member_id) {
		$sql = "SELECT rmm_member_id AS member_id"
					. ",rmm_nickname AS nickname"
				. " FROM t_room_member"
				. " WHERE rmm_room_id=$room_id"
				. " ORDER BY CASE WHEN rmm_member_id=$member_id THEN 0 ELSE 1 END, rmm_regist_date";
		return $this->db->GetAll($sql);
	}

	// ■ゲストメンバー有無チェック
	public function checkGuestMember($room_id) {
		$sql = "SELECT EXISTS (SELECT * FROM t_room_member WHERE rmm_room_id=$room_id AND rmm_status=2)";
		return $this->db->GetOne($sql);
	}

	// ■一般ユーザからの投稿待ち有無チェック
	public function checkPublicPost($room_id) {
		$sql = "SELECT EXISTS (SELECT * FROM t_room_public WHERE rmp_room_id=$room_id AND rmp_status=1)";
		return $this->db->GetOne($sql);
	}

	// ■送信メールテンプレート取得
	public function getMailTemplate($template_id) {
		$sql = "SELECT mtm_subject AS subject"
					. ",mtm_from AS from"
					. ",mtm_cc AS cc"
					. ",mtm_bcc AS bcc"
					. ",mtm_reply_to AS reply_to"
					. ",mtm_body AS body"
				. " FROM m_mail_template"
				. " WHERE mtm_template_id='$template_id'";
		return $this->db->GetRow($sql);
	}

	// ■発言送信メール取得
	public function getSendRemark($remark_id) {
		$sql = "SELECT rmk_room_id AS room_id"
					. ",rmk_seq_no AS seq_no"
					. ",rmk_parent_remark_id AS parent_remark_id"
					. ",rmk_subject AS subject"
					. ",rmk_from AS from"
					. ",rmk_message_id AS message_id"
					. ",rmk_content AS content"
					. ",rmk_room_header AS room_header"
					. ",rmk_room_footer AS room_footer"
					. ",rom_room_code AS room_code"
				. " FROM l_room_remark"
				. " JOIN t_room ON rom_room_id=rmk_room_id"
				. " WHERE rmk_remark_id=$remark_id";
		return $this->db->GetRow($sql);
	}

	// ■メッセージID取得
	public function getMessageId($remark_id) {
		$sql = "SELECT rmk_message_id AS message_id FROM l_room_remark WHERE rmk_remark_id=$remark_id";
		return $this->db->GetOne($sql);
	}

	// ■t_send_mail_dataにセット
	public function setSendMailData($subject, $from, $reply_to, $body, $mail_header='', $send_date='', $mail_type='0') {
		if (ereg('(.+)\<(.+)\>', $from, $regs)) {
			$from_addr = trim($regs[2]);
			$from_name = trim($regs[1]);
		} else {
			$from_addr = $from;
			$from_name = '';
		}

		$this->db->BeginTrans();

		// メール送信テーブルにセット
		$rec = array();
		$rec['sd_subject'] = $subject;
		$rec['sd_from'] = $from_addr;
		$rec['sd_reply_to'] = $reply_to;
		$rec['sd_body'] = $body;
		$rec['sd_status'] = 0;
		$rec['sd_mail_header'] = $mail_header;
		$rec['sd_send_date'] = $send_date;
		$rec['sd_personal'] = $from_name;
		$rec['sd_mail_type'] = $mail_type;
		$this->db->AutoExecute('t_send_mail_data', $rec, 'INSERT');

		// 送信メールID取得
		$send_mail_id = $this->getCurrentSequence('t_send_mail_data', 'sd_send_mail_id');

		return $send_mail_id;
	}

	// ■t_send_mail_listにセット
	public function setSendMailList($send_mail_id, $to, $sub_seq = '', $mail_header = '', $reply_to = '') {
		if ($to == '')
			return;

		$sql = sprintf("SELECT sl_send_mail_id FROM t_send_mail_list WHERE sl_send_mail_id=%s AND sl_to='%s'",
				$send_mail_id,
				$this->db->escape($to));
		if ($this->db->GetOne($sql))
			return;

		if ($reply_to != '')
			$mail_header = "Reply-To: $reply_to\n$mail_header";

		$rec = array();
		$rec['sl_send_mail_id'] = $send_mail_id;
		$rec['sl_to'] = $to;
		$rec['sl_sub_seq'] = $sub_seq;
		$rec['sl_mail_header'] = $mail_header;
		$this->db->AutoExecute('t_send_mail_list', $rec, 'INSERT');
	}

	// ■t_send_mail_embedにセット
	public function setSendMailEmbed($send_mail_id, $sub_seq, $variable_name, $embed_text) {
		$rec = array();
		$rec['se_send_mail_id'] = $send_mail_id;
		$rec['se_sub_seq'] = $sub_seq;
		$rec['se_variable_name'] = $variable_name;
		$rec['se_embed_text'] = $embed_text;
		$this->db->AutoExecute('t_send_mail_embed', $rec, 'INSERT');
	}

	// ■t_send_mail_dataステータスを送信可にセット
	public function setSendMailOk($send_mail_id) {
		$rec = array();
		$rec['sd_status'] = 1;
		$this->db->AutoExecute('t_send_mail_data', $rec, 'UPDATE', "sd_send_mail_id=$send_mail_id");

		$this->db->CommitTrans();
	}

	// ■参加会議室一覧取得
	public function getEntryList($member_id) {
		$sql = "SELECT"
				. " rmm_nickname AS nickname"
				. ",rmm_mail_send_flag AS mail_send_flag"
				. ",rom_room_id AS room_id"
				. ",rom_name AS room_name"
				. ",(SELECT MAX(rmk_date) FROM l_room_remark WHERE rmk_member_id=$member_id) AS last_date"
				. ",nvp_navi_page_id AS navi_page_id"
				. ",nav_name1||' '||nav_name2 AS navi_name"
				. ",nvp_image_id AS image_id"
			. " FROM t_room_member"
			. " JOIN t_room ON rom_room_id=rmm_room_id AND rom_status IN (1,2)"
			. " JOIN t_navi_page ON nvp_room_id=rmm_room_id AND nvp_open_flag=1"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE rmm_member_id=$member_id AND rmm_status IN (2,3)"
			. " ORDER BY rmm_regist_date DESC";
		return $this->db->GetAll($sql);
	}

	// ■ニックネーム更新
	public function updateNickname($member_id, $nickname_ary) {
		if (is_array($nickname_ary)) {
			$this->db->BeginTrans();

			foreach ($nickname_ary as $room_id => $nickname) {
				$rec = array();
				$rec['rmm_nickname'] = $nickname;
				$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', sprintf("rmm_room_id=$room_id AND rmm_member_id=$member_id AND rmm_nickname<>'%s'", $this->db->escape($nickname)));
			}

			$this->db->CommitTrans();
		}
	}

	// ■ワイワイ会議解除
	public function deleteRoomMember($room_id, $member_id) {
		$rec = array();
		$rec['rmm_status'] = 9;
		$rec['rmm_resign_date'] = 'now';
		$this->db->AutoExecute('t_room_member', $rec, 'UPDATE', "rmm_room_id=$room_id AND rmm_member_id=$member_id");
	}

	// ■会議室コードチェック
	public function checkRoomCode($room_id, $room_code) {
		$sql = sprintf("SELECT COUNT(*) FROM t_room WHERE rom_room_id<>$room_id AND rom_room_code='%s'", $this->db->escape($room_code));
		return $this->db->GetOne($sql);
	}

	// ■一般からの投稿
	public function insertRoomPublic($room_id, $post) {
		$rec = array();
		$rec['rmp_room_id'] = $room_id;
		$rec['rmp_seq_no'] = "(SELECT COALESCE(max(rmp_seq_no),0)+1 FROM t_room_public WHERE rmp_room_id=$room_id)";
		$rec['rmp_mail_addr'] = $post['mail_addr'];
		$rec['rmp_nickname'] = $post['nickname'];
		$rec['rmp_title'] = $post['title'];
		$rec['rmp_content'] = $post['content'];
		$rec['rmp_web_mail'] = 2;
		$this->db->AutoExecute('t_room_public', $rec, 'INSERT');
	}

	// ■ワイワイ会議ステータス取得
	public function getRoomStatus($room_id) {
		$sql = "SELECT rom_status FROM t_room WHERE rom_room_id=$room_id";
		return $this->db->GetOne($sql);
	}

	// ■ワイワイ会議参加チェック
	public function checkRoomMember($room_id, $member_id) {
		$sql = "SELECT COUNT(*)"
				. " FROM t_room_member"
				. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id AND rmm_status=3";
		return $this->db->GetOne($sql);
	}

	// ■ニックネーム存在チェック
	public function checkNickname($room_id, $nickname, $member_id) {
		$sql = "SELECT COUNT(*)"
			. " FROM t_room_member"
			. " WHERE rmm_room_id=$room_id AND rmm_nickname='" . $this->db->escape($nickname) . "'";
		if ($member_id)
			$sql .= " AND rmm_member_id<>$member_id";
		return $this->db->GetOne($sql);
	}

	// ■ナビメールアドレス取得
	public function getNaviMailaddr($navi_page_id) {
		$sql = "SELECT mbr_mail_addr"
		. " FROM t_navi_page"
		. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
		. " JOIN t_member ON mbr_member_id=nav_member_id"
		. " WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// ■都道府県名取得
	public function getArea1Name($code) {
		if ($code) {
			$sql = "SELECT tdf_name FROM m_todofuken WHERE tdf_area_id=$code";
			return $this->db->GetOne($sql);
		}
	}

	// ■メンバーアンケートID取得
	public function isEnqQuestion($room_id, $member_id, $add_where = ''){

		if ($room_id && $member_id) {

			$sql = "SELECT ren_enquete_id FROM t_room_enquete WHERE ren_room_id=$room_id AND ren_status=2 $add_where";
			$enquete_id = $this->db->GetOne($sql);
			if (!$enquete_id)
				return;

			$sql = "SELECT rel_member_id FROM l_room_enq_answer WHERE rel_enquete_id=$enquete_id AND rel_member_id=$member_id";
			$member_id = $this->db->GetOne($sql);
			if ($member_id)
				return;

			return $enquete_id;
		}
	}

	// ■メンバーアンケート設問取得
	public function getEnqQuestion($enquete_id) {

		$sql = "SELECT ren_enquete_id AS enquete_id"
			. ",ren_title AS title"
			. ",ren_enquete_text AS enquete_text"
			. ",req_question_no AS question_no"
			. ",req_type AS type"
			. ",req_free_flag AS free_flag"
			. ",req_question_text AS question_text"
			. " FROM t_room_enquete LEFT JOIN t_room_enq_question ON ren_enquete_id=req_enquete_id"
			. " WHERE ren_enquete_id=$enquete_id"
			. " ORDER BY req_question_no";
		return $this->db->GetAll($sql);
	}

	// ■メンバーアンケート選択肢取得
	public function getOptionText($enquete_id, $question_no) {

		if ($enquete_id && $question_no) {
			$sql = "SELECT reo_option_no AS option_no, reo_option_text AS option_text"
			. " FROM t_room_enq_option WHERE reo_enquete_id=$enquete_id AND reo_question_no=$question_no ORDER BY reo_option_no";
			return $this->db->GetAll($sql);
		}
	}

	// ■選択肢テキスト取得
	public function getAnswerText($enquete_id, $question_no, $ans) {
		if ($enquete_id && $question_no && $ans) {

			if (is_array($ans))
				$ans = implode(',', $ans);

			$sql = "SELECT reo_option_no AS option_no, reo_option_text AS option_text FROM t_room_enq_option"
				. " WHERE reo_enquete_id=$enquete_id AND reo_question_no=$question_no AND reo_option_no IN ($ans)";
			return $this->db->GetAll($sql);
		}
	}

	// ■アンケート回答保存
	public function saveEnqueteAnswer($post, $enquete_id, $member_id) {

		$sql = "SELECT rel_member_id FROM l_room_enq_answer WHERE rel_enquete_id=$enquete_id AND rel_member_id=$member_id";
		$l_member_id = $this->db->GetOne($sql);

		if (!$l_member_id) {

			$this->db->BeginTrans();

			// 回答履歴保存
			foreach ($post as $data) {

				$rec['rel_enquete_id'] = $enquete_id;
				$rec['rel_question_no'] = $data['quest_no'];
				$rec['rel_member_id'] = $member_id;
				if ($data['type'] != 3) {
					if ($data['free_text'])
						array_pop($data['ans']);
					$rec['rel_options'] = implode(',', $data['ans']);
					$rec['rel_free_text'] = $data['free_text'];
				} else {
					$rec['rel_options'] = null;
					$rec['rel_free_text'] = $data['ans_FA'];
				}

				$this->db->AutoExecute('l_room_enq_answer', $rec, 'INSERT');

			}

			// 回答者数更新
			$sql = "SELECT rec_enquete_id FROM c_room_enquete WHERE rec_enquete_id=$enquete_id";
			$c_enquete_id = $this->db->GetOne($sql);

			if ($c_enquete_id) {
				$rec['rec_count'] = "rec_count + 1";
				$this->db->AutoExecute('c_room_enquete', $rec, 'UPDATE', "rec_enquete_id=$enquete_id");
			} else {
				$rec['rec_enquete_id'] = $enquete_id;
				$rec['rec_count'] = 1;
				$this->db->AutoExecute('c_room_enquete', $rec, 'INSERT');
			}

			$this->db->CommitTrans();
		}
	}

	// ■都道府県情報取得
	public function getTodofuken() {
		$sql = "SELECT tdf_area_id AS area_id, tdf_name AS name FROM m_todofuken ORDER BY tdf_area_id";
		return $this->db->GetAll($sql);
	}

	// ■必須属性取得
	public function getRequiredItem($room_id) {
		$sql = "SELECT rom_required_item FROM t_room WHERE rom_room_id=$room_id";
		return $this->db->GetOne($sql);
	}

	// ■会議室未登録メンバー属性情報取得
	public function getNewMemberAttribute($member_id, $room_id) {
		if ($member_id) {
			$sql = "SELECT mbr_name AS name"
				. ",mbr_name_kana AS name_kana"
				. ",mbr_sex_cd AS sex_cd"
				. ",EXTRACT(YEAR FROM mbr_birthday) AS birth_y"
				. ",date_part('month', mbr_birthday) AS birth_m"
				. ",date_part('day', mbr_birthday) AS birth_d"
				. ",mbr_zip_cd AS zip_cd"
				. ",mbr_address1 AS address1"
				. ",mbr_address2 AS address2"
				. ",mbr_tel AS tel"
				. ",mbr_self_introduction AS self_introduction"
				. " FROM t_member"
				. " WHERE mbr_member_id=$member_id" ;
			return $this->db->GetRow($sql);
		}
	}

	// ■会議室メンバーステータス取得
	public function getRoomMemberStatus($room_id, $member_id) {
		if ($room_id && $member_id) {
			$sql = "SELECT rmm_status FROM t_room_member WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id";
			return $this->db->GetOne($sql);
		}
	}
}
?>