<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 データベース処理基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_LIB_DIR . '/HNbDatabase.class.php');

class MailMagDatabase extends HNbDatabase {
	// ■メルマガID取得
	public function getMelmagaId($navi_page_id) {
		$sql = "SELECT nvp_melmaga_id AS melmaga_id"
				. " FROM t_navi_page"
				. " WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// ■メルマガ情報取得
	public function getMailMagList($melmaga_id) {
		$sql = "SELECT"
				. " mmc_melmaga_contents_id AS contents_id"
				. ",mmc_subject AS subject"
				. ",mmc_send_date AS send_date"
				. ",mmc_status AS status"
			. " FROM t_melmaga_contents"
			. " WHERE mmc_melmaga_id=$melmaga_id"
			. " ORDER BY mmc_melmaga_contents_id DESC";
		return $this->db->GetAll($sql);
	}

	// ■配信メルマガ情報取得
	public function getMailMagContents($contents_id) {
		$sql = "SELECT"
				. " mmc_melmaga_id AS melmaga_id"
				. ",mmc_melmaga_contents_id AS contents_id"
				. ",mmc_subject AS subject"
				. ",mmc_body AS body"
				. ",mmc_footer AS footer"
				. ",mmc_send_date AS send_date"
				. ",mmc_comment_flag AS comment_flag"
				. ",mmc_trackback_flag AS trackback_flag"
				. ",mmc_status AS status"
				. ",mmc_open_flag AS open_flag"
				. ",mmc_send_num AS send_num"
			. ",DATE_PART('year', mmc_send_date) AS send_year"
			. ",DATE_PART('month', mmc_send_date) AS send_month"
			. ",DATE_PART('day', mmc_send_date) AS send_day"
			. ",DATE_PART('hour', mmc_send_date) AS send_hour"
			. ",DATE_PART('minute', mmc_send_date) AS send_minute"
			. " FROM t_melmaga_contents"
			. " WHERE mmc_melmaga_contents_id=$contents_id";
		return $this->db->GetRow($sql);
	}

	// ■メルマガ基本情報取得
	public function getMailMagBasic($melmaga_id) {
		$sql = "SELECT"
				. " mmg_melmaga_id AS melmaga_id"
				. ",mmg_melmaga_name AS melmaga_name"
				. ",mmg_melmaga_intro AS melmaga_intro"
				. ",mmg_melmaga_footer AS melmaga_footer"
				. ",mmg_backnumber AS backnumber"
				. ",mmg_notify_mail AS notify_mail"
				. ",mmg_status AS status"
			. " FROM t_melmaga"
			. " WHERE mmg_melmaga_id=$melmaga_id";
		return $this->db->GetRow($sql);
	}

	// ■デフォルトフッタ取得
	public function getMelmagaFooter($melmaga_id) {
		$sql = "SELECT mmg_melmaga_footer FROM t_melmaga WHERE mmg_melmaga_id=$melmaga_id";
		return $this->db->GetOne($sql);
	}

	// ■配信メルマガ情報登録/更新
	public function updateContentsInfo($melmaga_id, $data_ary, $mode) {
		$rec['mmc_subject'] = $data_ary['subject'];
		$rec['mmc_body'] = $data_ary['body'];
		$rec['mmc_footer'] = $data_ary['footer'];
		$rec['mmc_send_date'] = $data_ary['send_year'] . '-' . $data_ary['send_month'] . '-' . $data_ary['send_day'] . ' ' . $data_ary['send_hour'] . ':' . $data_ary['send_minute'] . ':0';
		$rec['mmc_open_flag'] = $data_ary['open_flag'];
		$rec['mmc_comment_flag'] = $data_ary['comment_flag'];
		$rec['mmc_trackback_flag'] = $data_ary['trackback_flag'];

		if ($mode == 'insert') {
			$rec['mmc_melmaga_id'] = $melmaga_id;
			$rec['mmc_status'] = 1;
			$rec['mmc_pv_id'] = $this->getNewPageViewId();
			$this->db->AutoExecute('t_melmaga_contents', $rec, 'INSERT');
			$contents_id = $this->getCurrentSequence('t_melmaga_contents', 'mmc_melmaga_contents_id');
		} else if ($mode == 'update') {
			$contents_id = $data_ary['contents_id'];
			$this->db->AutoExecute('t_melmaga_contents', $rec, 'UPDATE', "mmc_melmaga_id=$melmaga_id AND mmc_melmaga_contents_id=" . $contents_id);
		}

		return $contents_id;
	}

	// ■メルマガ基本情報更新
	public function updateBasicInfo($melmaga_id, $data_ary) {
		$rec['mmg_melmaga_name'] = $data_ary['melmaga_name'];
		$rec['mmg_melmaga_intro'] = $data_ary['melmaga_intro'];
		$rec['mmg_melmaga_footer'] = $data_ary['melmaga_footer'];
		$rec['mmg_status'] = $data_ary['status'];
		$rec['mmg_backnumber'] = $data_ary['backnumber'];
		$rec['mmg_notify_mail'] = $data_ary['notify_mail'];
		$rec['mmg_update_date'] = 'now()';
		$this->db->AutoExecute('t_melmaga', $rec, 'UPDATE', "mmg_melmaga_id=$melmaga_id");
	}

	// ■メルマガメンバー削除（メールアドレス指定）
	public function deleteMelmagaMember($melmaga_id, $mail_addr) {
		$sub = sprintf("SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr_low='%s'", $this->db->escape(strtolower($mail_addr)));
		$sql = "DELETE FROM t_melmaga_member WHERE mmm_melmaga_id=$melmaga_id AND mmm_member_id=($sub)";
		$this->db->Execute($sql);
	}

	// ■メールアドレスの有無確認
	public function checkMailAddr($melmaga_id, $mail_addr) {
		$sql = sprintf("SELECT mmm_melmaga_id FROM t_melmaga_member JOIN t_member ON mmm_member_id=mbr_member_id WHERE mmm_melmaga_id=%s AND mbr_mail_addr_low='%s'", $melmaga_id, $this->db->escape(strtolower($mail_addr)));
		return $this->db->GetOne($sql) ? true : false;
	}

	// ■バックナンバー情報取得
	public function getBacknumberInfo($melmaga_id) {
		$sql = "SELECT"
				. " mmg_status AS status"
				. ",mmg_melmaga_name AS melmaga_name"
				. ",mmg_melmaga_intro AS melmaga_intro"
				. ",mmg_backnumber AS backnumber"
				. ",(SELECT COUNT(*) FROM t_melmaga_contents WHERE mmc_melmaga_id=mmg_melmaga_id AND mmc_status=2) AS publish_count"
				. ",(SELECT COUNT(*) FROM t_melmaga_member WHERE mmm_melmaga_id=mmg_melmaga_id) AS reader_num"
				. ",(SELECT MAX(mmc_send_date) FROM t_melmaga_contents WHERE mmc_melmaga_id=mmg_melmaga_id AND mmc_status=2) AS last_date"
			. " FROM t_melmaga"
			. " WHERE mmg_melmaga_id=$melmaga_id";
		return $this->db->GetRow($sql);
	}

	// ■バックナンバーリスト取得
	public function getBacknumberList($melmaga_id, $limit = 0) {
		$sql = "SELECT"
				. " mmc_melmaga_contents_id AS contents_id"
				. ",mmc_send_date AS send_date"
				. ",mmc_subject AS subject"
			. " FROM t_melmaga_contents"
			. " WHERE mmc_melmaga_id=$melmaga_id AND mmc_status=2 AND mmc_open_flag=1"
			. " ORDER BY mmc_send_date DESC"
			. ($limit ? " LIMIT $limit" : "");
		return $this->db->GetAll($sql);
	}

	// ■バックナンバーコンテンツ取得
	public function getBacknumberContents($contents_id) {
		$sql = "SELECT"
				. " mmc_melmaga_contents_id AS contents_id"
				. ",mmc_send_date AS send_date"
				. ",mmc_subject AS subject"
				. ",mmc_body AS body"
				. ",mmc_send_num AS send_num"
				. ",mmc_pv_id AS pv_id"
				. ",mmc_comment_flag AS comment_flag"
				. ",mmc_trackback_flag AS trackback_flag"
				. ",(SELECT COUNT(*) FROM l_melmaga_comment WHERE mmo_melmaga_contents_id=mmc_melmaga_contents_id AND mmo_open_flag=1) AS comment_num"
				. ",(SELECT COUNT(*) FROM l_melmaga_trackback WHERE mmt_melmaga_contents_id=mmc_melmaga_contents_id AND mmt_open_flag=1) AS trackback_num"
			. " FROM t_melmaga_contents"
			. " WHERE mmc_melmaga_contents_id=$contents_id AND mmc_status=2 AND mmc_open_flag=1";
		return $this->db->GetRow($sql);
	}

	// ■バックナンバーコメント取得
	public function getBacknumberComment($contents_id) {
		$sql = "SELECT"
				. " mmo_date AS date"
				. ",mmo_user_name AS user_name"
				. ",mmo_comment AS comment"
			. " FROM l_melmaga_comment"
			. " WHERE mmo_melmaga_contents_id=$contents_id AND mmo_open_flag=1"
			. " ORDER BY mmo_comment_no";
		return $this->db->GetAll($sql);
	}

	// ■コメント追加
	public function insertComment($contents_id, $user_name, $comment) {
		$ngword = $this->checkNGWord($user_name . $comment);
		if ($ngword != '')
			return false;

		$rec = array();
		$rec['mmo_melmaga_contents_id'] = $contents_id;
		$rec['mmo_comment_no'] = "(SELECT COALESCE(max(mmo_comment_no),0)+1 FROM l_melmaga_comment WHERE mmo_melmaga_contents_id=$contents_id)";
		$rec['mmo_user_name'] = $user_name;
		$rec['mmo_comment'] = $comment;
		$rec['mmo_ng_word'] = $ngword;
		$rec['mmo_pv_id'] = $this->getNewPageViewId();
		$this->db->AutoExecute('l_melmaga_comment', $rec, 'INSERT');

		return true;
	}

	// ■トラックバック情報取得
	public function getBacknumberTrackback($contents_id) {
		$sql = "SELECT"
				. " mmt_date AS date"
				. ",mmt_title AS title"
				. ",mmt_excerpt AS excerpt"
				. ",mmt_url AS url"
				. ",mmt_blog_name AS blog_name"
			. " FROM l_melmaga_trackback"
			. " WHERE mmt_melmaga_contents_id=$contents_id AND mmt_open_flag=1"
			. " ORDER BY mmt_trackback_no";
		return $this->db->GetAll($sql);
	}

	// ■トラックバック書き込み
	public function insertTrackback($trackback) {
		$rec = array();
		$rec['mmt_melmaga_contents_id'] = $trackback['contents_id'];
		$rec['mmt_trackback_no'] = "(SELECT COALESCE(max(mmt_trackback_no),0)+1 FROM l_melmaga_trackback WHERE mmt_melmaga_contents_id={$trackback['contents_id']})";
		$rec['mmt_title'] = $trackback['title'];
		$rec['mmt_url'] = $trackback['url'];
		$rec['mmt_blog_name'] = $trackback['blog_name'];
		$rec['mmt_excerpt'] = $trackback['excerpt'];
		$rec['mmt_ng_word'] = $this->checkNGWord($trackback['title'] . $trackback['blog_name'] . $trackback['excerpt']);
		$rec['mmt_pv_id'] = $this->getNewPageViewId();
		$this->db->AutoExecute('l_melmaga_trackback', $rec, 'INSERT');
	}

	// ■メルマガステータス取得
	public function getMailMagStatus($melmaga_id) {
		$sql = "SELECT"
				. " mmg_status AS status"
				. ",mmg_backnumber AS backnumber"
			. " FROM t_melmaga"
			. " WHERE mmg_melmaga_id=$melmaga_id";
		return $this->db->GetRow($sql);
	}

	// ■購読メルマガ一覧取得
	public function getSubscriptionList($member_id) {
		$sql = "SELECT"
				. " mmg_melmaga_id AS melmaga_id"
				. ",mmg_melmaga_name AS melmaga_name"
				. ",mmn_reader_cnt AS reader_cnt"
				. ",mmn_last_publish_date AS last_publish_date"
				. ",nvp_navi_page_id AS navi_page_id"
				. ",trim(COALESCE(nav_name1,'')||' '||COALESCE(nav_name2,'')) AS navi_name"
				. ",nvp_image_id AS image_id"
			. " FROM t_melmaga_member"
			. " JOIN t_melmaga ON mmg_melmaga_id=mmm_melmaga_id AND mmg_status=1"
			. " JOIN t_navi_page ON nvp_melmaga_id=mmm_melmaga_id AND nvp_open_flag=1"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " LEFT JOIN c_melmaga_count ON mmn_melmaga_id=mmm_melmaga_id"
			. " WHERE mmm_member_id=$member_id"
			. " ORDER BY mmm_regist_date DESC";
		return $this->db->GetAll($sql);
	}

	// ■メルマガ購読
	public function insertMelmagaMember($melmaga_id, $member_id) {
		$rec = array();
		$rec['mmm_melmaga_id'] = $melmaga_id;
		$rec['mmm_member_id'] = $member_id;
		$this->db->AutoExecute('t_melmaga_member', $rec, 'INSERT');
	}

	// ■メルマガ購読解除（メンバーID指定）
	public function deleteMelmagaMember2($melmaga_id, $member_id) {
		$sql = "DELETE FROM t_melmaga_member WHERE mmm_melmaga_id=$melmaga_id AND mmm_member_id=$member_id";
		$this->db->Execute($sql);
	}

	// ■メールアドレスからメンバーID取得
	public function getMemberIdFromMailaddr($mail_addr) {
		$sql = sprintf("SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr_low='%s' AND mbr_status IN (1,2,3)", $this->db->escape(strtolower($mail_addr)));
		return $this->db->GetOne($sql);
	}

	// ■メルマガ購読済みチェック
	public function checkMelmagaMember($melmaga_id, $member_id) {
		$sql = "SELECT COUNT(*) FROM t_melmaga_member WHERE mmm_melmaga_id=$melmaga_id AND mmm_member_id=$member_id";
		return $this->db->GetOne($sql);
	}

	// ■メルマガ名、ナビページ名、ナビ名取得
	public function getMailMagNavi($melmaga_id) {
		$sql = "SELECT"
				. " mmg_melmaga_name AS melmaga_name"
				. ",nvp_title AS navi_page_title"
				. ",trim(COALESCE(nav_name1,'')||' '||COALESCE(nav_name2,'')) AS navi_name"
				. ",nvp_channel_id AS channel_id"
			. " FROM t_melmaga"
			. " JOIN t_navi_page ON nvp_melmaga_id=mmg_melmaga_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE mmg_melmaga_id=$melmaga_id";
		return $this->db->GetRow($sql);
	}

	// ■同一チャネルのメルマガリスト取得
	public function getMailMagChannel($channel_id, $melmaga_id, $member_id) {
		$sql = "SELECT"
				. " mmg_melmaga_name AS melmaga_name"
				. ",nvp_navi_page_id AS navi_page_id"
				. ",nvp_title AS navi_page_title"
				. ",trim(COALESCE(nav_name1,'')||' '||COALESCE(nav_name2,'')) AS navi_name"
				. ",mmm_member_id AS member_id"
			. " FROM t_navi_page"
			. " JOIN t_melmaga ON mmg_melmaga_id=nvp_melmaga_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " LEFT JOIN t_melmaga_member ON mmm_melmaga_id=mmg_melmaga_id AND mmm_member_id=$member_id"
			. " WHERE nvp_channel_id=$channel_id AND nvp_status=1 AND mmg_status=1 AND mmg_melmaga_id<>$melmaga_id"
			. " ORDER BY mmg_melmaga_id";
		return $this->db->GetAll($sql);
	}

	// ■クリックカウンタ登録
	public function insertClickCounter($contents_id, $click_counter_ary) {
		if (is_array($click_counter_ary)) {
			$this->db->BeginTrans();

			foreach ($click_counter_ary as $ctid => $click_counter) {
				$rec = array();
				$rec['mmi_ct_id'] = $ctid;
				$rec['mmi_melmaga_contents_id'] = $contents_id;
				$rec['mmi_url_name'] = $click_counter['url_name'];
				$rec['mmi_jump_url'] = $click_counter['jump_url'];
				$this->db->AutoExecute('t_melmaga_click', $rec, 'INSERT');
			}

			$this->db->CommitTrans();
		}
	}

	// ■クリック数表示用コンテンツ取得
	public function getClickContents($contents_id) {
		$sql = "SELECT"
				. " mmc_send_date AS send_date"
				. ",mmc_subject AS subject"
				. ",mmc_body AS body"
				. ",mmc_send_num AS send_num"
			. " FROM t_melmaga_contents"
			. " WHERE mmc_melmaga_contents_id=$contents_id";
		return $this->db->GetRow($sql);
	}

	// ■クリック数取得
	public function getClickCounter($ct_id) {
		$sql = "SELECT mmi_click_count FROM t_melmaga_click WHERE mmi_ct_id=$ct_id";
		return $this->db->GetOne($sql);
	}
}
?>