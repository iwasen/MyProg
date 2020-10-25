<?php
/**
 * ひとびと･net 事務局ページ
 *
 * アンケート品質管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class enquete_data_class extends data_class {
	public $edit_name;												// 基本情報/設問/トラックバック/コメント
	public $enquete_id;
	public $ans_count;
	public $navi_page_id;
	public $navi_id;
	public $blog_id;
	public $blog_title;
	public $pickup;
	public $communication_msg;

	public $nen_title;
	public $nen_question_text;
	public $nen_ng_word;
	public $nen_patrol_check;
	public $nen_patrol_date;
	public $nen_patrol_id;
	public $nen_admin_check;
	public $nen_admin_date;
	public $nen_admin_id;
	public $nen_open_flag;
	public $nen_date;
	public $nen_class;

	public $net_title;
	public $net_excerpt;
	public $net_url;
	public $net_ng_word;
	public $net_patrol_check;
	public $net_patrol_date;
	public $net_patrol_id;
	public $net_admin_check;
	public $net_admin_date;
	public $net_admin_id;
	public $net_open_flag;
	public $net_date;
	public $net_class;

	public $neo_user_name;
	public $neo_comment;
	public $neo_ng_word;
	public $neo_patrol_check;
	public $neo_patrol_date;
	public $neo_patrol_id;
	public $neo_admin_check;
	public $neo_admin_date;
	public $neo_admin_id;
	public $neo_open_flag;
	public $neo_date;
	public $neo_class;
	public $fetch_cm = array();
	public $fetch_tb = array();

	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み（アンケート情報）
	public function read_db($enquete_id) {
		$sql = sprintf("SELECT nen_enquete_id,xxx_count,nen_title,nen_navi_page_id,nvp_navi_id,nvp_title,nvp_blog_id,nen_pickup,nen_communication_msg,nen_question_text"
			. ",nen_ng_word,nen_patrol_check,nen_patrol_date,nen_patrol_id,nen_admin_check,nen_admin_date,nen_admin_id,nen_open_flag,nen_date,pvc_pv_count"
			. " FROM t_navi_enquete LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
			. " LEFT JOIN c_navi_enquete ON nen_enquete_id=xxx_enquete_id"
			. " LEFT JOIN c_page_view ON nen_pv_id=pvc_pv_id"
			. " WHERE nen_enquete_id=%s", sql_number($enquete_id));
		$fetch = $this->fetch_db($sql);

		$this->enquete_id = $fetch->nen_enquete_id;
		$this->ans_count = $fetch->xxx_count;
		$this->nen_title = $fetch->nen_title;
		$this->navi_page_id = $fetch->nen_navi_page_id;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->nvp_title = $fetch->nvp_title;
		$this->blog_id = $fetch->nvp_blog_id;
		$this->pickup = $fetch->nen_pickup;
		$this->communication_msg = $fetch->nen_communication_msg;
		$this->nen_question_text = $fetch->nen_question_text;
		$this->nen_ng_word = $fetch->nen_ng_word;
		$this->nen_patrol_check = $fetch->nen_patrol_check;
		$this->nen_patrol_date = $fetch->nen_patrol_date;
		$this->nen_patrol_id = $fetch->nen_patrol_id;
		$this->nen_admin_check = $fetch->nen_admin_check;
		$this->nen_admin_date = $fetch->nen_admin_date;
		$this->nen_admin_id = $fetch->nen_admin_id;
		$this->nen_open_flag = $fetch->nen_open_flag;
		$this->nen_date = $fetch->nen_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;

		$this->nen_class = $this->get_bar_class($this->nen_patrol_check, $this->nen_admin_check, $this->nen_ng_word);

		$sql = sprintf("SELECT neo_option_no,neo_option_text FROM t_navi_enq_option WHERE neo_enquete_id = %s", sql_number($enquete_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->enq_option[$fetch->neo_option_no] = $fetch->neo_option_text;
		}
	}

	// ■DBからデータ読み込み（コメント全件）
	public function read_db_comment_all($enquete_id) {
		$sql = sprintf("SELECT neo_comment,neo_user_name,neo_comment_no,neo_ng_word"
			. ",neo_patrol_check,neo_patrol_date,neo_patrol_id,neo_admin_check,neo_admin_date,neo_admin_id,neo_open_flag,neo_date,pvc_pv_count"
			. " FROM l_navi_enq_comment LEFT JOIN c_page_view ON neo_pv_id=pvc_pv_id"
			. " WHERE neo_enquete_id=%s ORDER BY neo_date DESC", sql_number($enquete_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_cm[] = pg_fetch_object($result, $i);
	}

	// ■DBからデータ読み込み（コメント１件）
	public function read_db_comment($enquete_id, $comment_no) {
		$sql = sprintf("SELECT neo_enquete_id,neo_comment_no,neo_comment,neo_user_name,neo_comment_no,neo_ng_word"
			. ",neo_patrol_check,neo_patrol_date,neo_patrol_id,neo_admin_check,neo_admin_date,neo_admin_id,neo_open_flag,neo_date,pvc_pv_count"
			. " FROM l_navi_enq_comment LEFT JOIN c_page_view ON neo_pv_id=pvc_pv_id"
			. " WHERE neo_enquete_id=%s AND neo_comment_no=%s", sql_number($enquete_id), sql_number($comment_no));
		$fetch = $this->fetch_db($sql);
		$this->enquete_id = $fetch->neo_enquete_id;
		$this->comment_no = $fetch->neo_comment_no;
		$this->neo_user_name = $fetch->neo_user_name;
		$this->neo_comment = $fetch->neo_comment;
		$this->neo_ng_word = $fetch->neo_ng_word;
		$this->neo_patrol_check = $fetch->neo_patrol_check;
		$this->neo_patrol_date = $fetch->neo_patrol_date;
		$this->neo_patrol_id = $fetch->neo_patrol_id;
		$this->neo_admin_check = $fetch->neo_admin_check;
		$this->neo_admin_date = $fetch->neo_admin_date;
		$this->neo_admin_id = $fetch->neo_admin_id;
		$this->neo_open_flag = $fetch->neo_open_flag;
		$this->neo_date = $fetch->neo_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
	}

	// ■DBからデータ読み込み（トラックバック全件）
	public function read_db_trackback_all($enquete_id) {
		$sql = sprintf("SELECT net_trackback_no,net_title,net_url,net_excerpt,net_trackback_no,net_ng_word"
		 . ",net_patrol_check,net_patrol_date,net_patrol_id,net_admin_check,net_admin_date,net_admin_id,net_open_flag,net_date,pvc_pv_count"
			. " FROM l_navi_enq_trackback LEFT JOIN c_page_view ON net_pv_id=pvc_pv_id"
			. " WHERE net_enquete_id=%s ORDER BY net_date DESC", sql_number($enquete_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_tb[] = pg_fetch_object($result, $i);
	}

	// ■DBからデータ読み込み（トラックバック１件）
	public function read_db_trackback($enquete_id, $trackback_no) {
		$sql = sprintf("SELECT net_enquete_id,net_trackback_no,net_trackback_no,net_title,net_url,net_excerpt"
			. ",net_ng_word,net_patrol_check,net_patrol_date,net_patrol_id,net_admin_check,net_admin_date,net_admin_id,net_open_flag,net_date,pvc_pv_count"
			. " FROM l_navi_enq_trackback LEFT JOIN c_page_view ON net_pv_id=pvc_pv_id"
			. " WHERE net_enquete_id=%s AND net_trackback_no=%s", sql_number($enquete_id), sql_number($trackback_no));
		$fetch = $this->fetch_db($sql);
		$this->enquete_id = $fetch->net_enquete_id;
		$this->trackback_no = $fetch->net_trackback_no;
		$this->net_title = $fetch->net_title;
		$this->net_url = $fetch->net_url;
		$this->net_excerpt = $fetch->net_excerpt;
		$this->net_ng_word = $fetch->net_ng_word;
		$this->net_patrol_check = $fetch->net_patrol_check;
		$this->net_patrol_date = $fetch->net_patrol_date;
		$this->net_patrol_id = $fetch->net_patrol_id;
		$this->net_admin_check = $fetch->net_admin_check;
		$this->net_admin_date = $fetch->net_admin_date;
		$this->net_admin_id = $fetch->net_admin_id;
		$this->net_open_flag = $fetch->net_open_flag;
		$this->net_date = $fetch->net_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
	}

	// ■表示バー色取得
	public function get_bar_class($patrol_check, $admin_check, $ng_word) {
		if (($patrol_check == 3 || $patrol_check == 4) && $admin_check == 1)
			return 'head2';
		else if ($patrol_check == 1 && $admin_check == 1) {
			if ($ng_word)
				return 'head2';
			else
				return 'head3';
		} else
			return '';
	}

	// ■該当ナビのアンケート数カウント
	public function enq_count() {
		$sub = "SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_navi_id = ". sql_number($this->navi_id);
		$sql = "SELECT COUNT(*) AS enq_count, MAX(nen_date) AS new_date FROM t_navi_enquete WHERE nen_navi_page_id IN ($sub)";
		$fetch = $this->fetch_db($sql);
		return number_format($fetch->enq_count) . '回 (' . format_datetime($fetch->new_date) . ')';
	}

	// ■該当ナビのアンケート合計回答数
	public function ans_count() {
		$sub = "SELECT nvp_navi_page_id FROM t_navi_page WHERE nvp_navi_id = ". sql_number($this->navi_id);
		$sql = "SELECT SUM(xxx_count) AS ans_count, MAX(nen_date) AS new_date FROM c_navi_enquete LEFT JOIN t_navi_enquete ON xxx_enquete_id=nen_enquete_id WHERE nen_navi_page_id IN ($sub)";
		$fetch = $this->fetch_db($sql);
		return number_format($fetch->ans_count) . '件 (' . format_datetime($fetch->new_date) . ')';
	}
	// ■フォームからデータ読み込み
	public function read_form() {
		switch($_POST['edit_kind']) {
		case 'bs':
			$this->edit_name = 'アンケート基本';
			$this->pickup = $_POST['pickup'];
			$this->communication_msg = $_POST['communication_msg'];
			break;
		case 'ct':
			$this->edit_name = 'アンケート詳細';
			$this->nen_title = $_POST['nen_title'];
			$this->nen_question_text = $_POST['nen_question_text'];
			$this->enq_option = $_POST['enq_option'];
			$this->nen_patrol_check = $_POST['nen_patrol_check'];
			$this->nen_admin_check = $_POST['nen_admin_check'];
			$this->nen_open_flag = $_POST['nen_open_flag'];
			break;
		case 'tb':
			$this->edit_name = 'トラックバック';
			$this->trackback_no = $_POST['trackback_no'];
			$this->net_title = $_POST['net_title'];
			$this->net_url = $_POST['net_url'];
			$this->net_excerpt = $_POST['net_excerpt'];
			$this->net_patrol_check = $_POST['net_patrol_check'];
			$this->net_admin_check = $_POST['net_admin_check'];
			$this->net_open_flag = $_POST['net_open_flag'];
			break;
		case 'cm':
			$this->edit_name = 'コメント';
			$this->comment_no = $_POST['comment_no'];
			$this->neo_user_name = $_POST['neo_user_name'];
			$this->neo_comment = $_POST['neo_comment'];
			$this->neo_excerpt = $_POST['neo_excerpt'];
			$this->neo_patrol_check = $_POST['neo_patrol_check'];
			$this->neo_admin_check = $_POST['neo_admin_check'];
			$this->neo_open_flag = $_POST['neo_open_flag'];
			break;
		case 'en':
			$this->edit_name = 'アンケート';
			$this->navi_page_id = $_POST['navi_page_id'];
			break;
		}
		$this->enquete_id = $_POST['enquete_id'];
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		switch($_POST['edit_kind']) {
		case 'bs':
			$rec['nen_pickup'] = sql_number($this->pickup);
			$rec['nen_communication_msg'] = sql_char($this->communication_msg);
			db_update('t_navi_enquete',$rec , "nen_enquete_id=$this->enquete_id");
			break;
		case 'ct':
			$ngword = check_ngword($this->nen_title . $this->nen_question_text);
			$rec['nen_title'] = sql_char($this->nen_title);
			$rec['nen_question_text'] = sql_char($this->nen_question_text);
			$rec['nen_ng_word'] = sql_char($ngword);
			$rec['nen_open_flag'] = sql_number($this->nen_open_flag);

			if ($this->nen_admin_check) {
				if ($this->nen_admin_check != 1) {
					$rec['nen_admin_check'] = sql_char($this->nen_admin_check);
					$rec['nen_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['nen_admin_date'] = sql_char('now');
				} else {
					$rec['nen_admin_check'] = 1;
					$rec['nen_admin_id'] = sql_number('');
					$rec['nen_admin_date'] = sql_char('');
				}
			}

			if ($this->nen_patrol_check) {
				if ($this->nen_patrol_check != 1) {
					$rec['nen_patrol_check'] = sql_char($this->nen_patrol_check);
					$rec['nen_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['nen_patrol_date'] = sql_char('now');
				} else {
					$rec['nen_patrol_check'] = 1;
					$rec['nen_patrol_id'] = sql_number('');
					$rec['nen_patrol_date'] = sql_char('');
				}
			}
			db_update('t_navi_enquete', $rec, "nen_enquete_id=$this->enquete_id");

			// アンケート選択肢更新
			db_delete('t_navi_enq_option', "neo_enquete_id=$this->enquete_id");

			foreach ($this->enq_option as $option_no => $option_text) {
				if ($option_text != '') {
					$rec['neo_enquete_id'] = sql_number($this->enquete_id);
					$rec['neo_option_no'] = sql_number($option_no);
					$rec['neo_option_text'] = sql_char($option_text);
					db_insert('t_navi_enq_option', $rec);
				}
			}
			break;
		case 'tb':
			$ngword = check_ngword($this->net_title . $this->net_excerpt);
			$rec['net_title'] = sql_char($this->net_title);
			$rec['net_url'] = sql_char($this->net_url);
			$rec['net_excerpt'] = sql_char($this->net_excerpt);
			$rec['net_ng_word'] = sql_char($ngword);
			$rec['net_open_flag'] = sql_number($this->net_open_flag);

			if ($this->net_admin_check) {
				if ($this->net_admin_check != 1) {
					$rec['net_admin_check'] = sql_char($this->net_admin_check);
					$rec['net_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['net_admin_date'] = sql_char('now');
				} else {
					$rec['net_admin_check'] = 1;
					$rec['net_admin_id'] = sql_number('');
					$rec['net_admin_date'] = sql_char('');
				}
			}

			if ($this->net_patrol_check) {
				if ($this->net_patrol_check != 1) {
					$rec['net_patrol_check'] = sql_char($this->net_patrol_check);
					$rec['net_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['net_patrol_date'] = sql_char('now');
				} else {
					$rec['net_patrol_check'] = 1;
					$rec['net_patrol_id'] = sql_number('');
					$rec['net_patrol_date'] = sql_char('');
				}
			}
			db_update('l_navi_enq_trackback',$rec , "net_enquete_id=$this->enquete_id AND net_trackback_no=$this->trackback_no");
			break;
		case 'cm':
			$ngword = check_ngword($this->neo_user_name . $this->neo_comment);
			$rec['neo_user_name'] = sql_char($this->neo_user_name);
			$rec['neo_comment'] = sql_char($this->neo_comment);
			$rec['neo_ng_word'] = sql_char($ngword);
			$rec['neo_open_flag'] = sql_number($this->neo_open_flag);

			if ($this->neo_admin_check) {
				if ($this->neo_admin_check != 1) {
					$rec['neo_admin_check'] = sql_char($this->neo_admin_check);
					$rec['neo_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['neo_admin_date'] = sql_char('now');
				} else {
					$rec['neo_admin_check'] = 1;
					$rec['neo_admin_id'] = sql_number('');
					$rec['neo_admin_date'] = sql_char('');
				}
			}

			if ($this->neo_patrol_check) {
				if ($this->neo_patrol_check != 1) {
					$rec['neo_patrol_check'] = sql_char($this->neo_patrol_check);
					$rec['neo_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['neo_patrol_date'] = sql_char('now');
				} else {
					$rec['neo_patrol_check'] = 1;
					$rec['neo_patrol_id'] = sql_number('');
					$rec['neo_patrol_date'] = sql_char('');
				}
			}
			db_update('l_navi_enq_comment',$rec , "neo_enquete_id=$this->enquete_id AND neo_comment_no=$this->comment_no");
			break;
		case 'en':
			$rec['nen_navi_page_id'] = sql_number($this->navi_page_id);
			db_update('t_navi_enquete',$rec , "nen_enquete_id=$this->enquete_id");
			break;
		}

		db_commit_trans();
	}
}

?>
