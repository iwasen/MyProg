<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ品質管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class melmaga_contents_data_class extends data_class {
	public $edit_name;												// 基本情報/ナビ記事/トラックバック/コメント
	public $navi_page_id;
	public $melmaga_contents_id;
	public $mmc_send_num;
	public $melmaga_name;
	public $blog_id;
	public $blg_title;
	public $nav_navi_id;
	public $pickup;
	public $communication_msg;

	public $mmc_subject;
	public $mmc_body;
	public $mmc_footer;
	public $mmc_ng_word;
	public $mmc_patrol_check;
	public $mmc_patrol_date;
	public $mmc_patrol_id;
	public $mmc_admin_check;
	public $mmc_admin_date;
	public $mmc_admin_id;
	public $mmc_open_flag;
	public $mmc_date;
	public $mmc_class;

	public $mmo_user_name;
	public $mmo_comment;
	public $mmo_ng_word;
	public $mmo_patrol_check;
	public $mmo_patrol_date;
	public $mmo_patrol_id;
	public $mmo_admin_check;
	public $mmo_admin_date;
	public $mmo_admin_id;
	public $mmo_open_flag;
	public $mmo_date;
	public $mmo_class;
	public $fetch_cm = array();
	public $fetch_tb = array();

	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み（メルマガ情報）
	public function read_db_contents($melmaga_contents_id) {
		$sql = sprintf("SELECT mmc_melmaga_contents_id,mmc_send_num,mmg_melmaga_id,mmg_melmaga_name,blg_blog_id,blg_title,nav_navi_id,mmc_pickup,mmc_communication_msg"
			. ",mmc_subject,mmc_body,mmc_footer,mmc_ng_word,mmc_patrol_check,mmc_patrol_date,mmc_patrol_id,mmc_patrol_id,mmc_admin_check,mmc_admin_date,mmc_admin_id"
			. ",mmc_open_flag,mmc_date,pvc_pv_count,nvp_navi_page_id"
			. " FROM t_melmaga_contents LEFT JOIN t_melmaga ON mmc_melmaga_id=mmg_melmaga_id"
			. " LEFT JOIN c_page_view ON mmc_pv_id=pvc_pv_id"
			. " LEFT JOIN t_navi_page ON mmg_melmaga_id=nvp_melmaga_id"
			. " LEFT JOIN t_blog ON nvp_blog_id=blg_blog_id"
			. " LEFT JOIN t_navi ON nvp_navi_id=nav_navi_id"
			. " WHERE mmc_melmaga_contents_id=%s", sql_number($melmaga_contents_id));

		$fetch = $this->fetch_db($sql);

		$this->melmaga_contents_id = $fetch->mmc_melmaga_contents_id;
		$this->send_num = $fetch->mmc_send_num;
		$this->melmaga_id = $fetch->mmg_melmaga_id;
		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->melmaga_name = $fetch->mmg_melmaga_name;
		$this->blog_id = $fetch->blg_blog_id;
		$this->blog_title = $fetch->blg_title;
		$this->navi_id = $fetch->nav_navi_id;
		$this->pickup = $fetch->mmc_pickup;
		$this->communication_msg = $fetch->mmc_communication_msg;

		// 投稿記事情報
		$this->mmc_subject = $fetch->mmc_subject;
		$this->mmc_body = $fetch->mmc_body;
		$this->mmc_footer = $fetch->mmc_footer;
		$this->mmc_ng_word = $fetch->mmc_ng_word;
		$this->mmc_patrol_check = $fetch->mmc_patrol_check;
		$this->mmc_patrol_date = $fetch->mmc_patrol_date;
		$this->mmc_patrol_id = $fetch->mmc_patrol_id;
		$this->mmc_admin_check = $fetch->mmc_admin_check;
		$this->mmc_admin_date = $fetch->mmc_admin_date;
		$this->mmc_admin_id = $fetch->mmc_admin_id;
		$this->mmc_open_flag = $fetch->mmc_open_flag;
		$this->mmc_date = $fetch->mmc_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;

		$this->mmc_class = $this->get_bar_class($this->mmc_patrol_check, $this->mmc_admin_check, $this->mmc_ng_word);
	}

	// ■DBからデータ読み込み（コメント全件）
	public function read_db_comment_all($melmaga_contents_id) {
		$sql = sprintf("SELECT mmo_comment,mmo_user_name,mmo_comment_no,mmo_ng_word"
			. ",mmo_patrol_check,mmo_patrol_date,mmo_patrol_id,mmo_admin_check,mmo_admin_date,mmo_admin_id,mmo_open_flag,mmo_date,pvc_pv_count"
			. " FROM l_melmaga_comment LEFT JOIN c_page_view ON mmo_pv_id=pvc_pv_id"
			. " WHERE mmo_melmaga_contents_id=%s ORDER BY mmo_date DESC", sql_number($melmaga_contents_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_cm[] = pg_fetch_object($result, $i);
	}

	// ■DBからデータ読み込み（コメント１件）
	public function read_db_comment($melmaga_contents_id, $comment_no) {
		$sql = sprintf("SELECT mmo_melmaga_contents_id,mmo_comment,mmo_user_name,mmo_comment_no"
			. ",mmo_ng_word,mmo_patrol_check,mmo_patrol_date,mmo_patrol_id,mmo_admin_check,mmo_admin_date,mmo_admin_id,mmo_open_flag,mmo_date,pvc_pv_count"
			. " FROM l_melmaga_comment LEFT JOIN c_page_view ON mmo_pv_id=pvc_pv_id"
			. " WHERE mmo_melmaga_contents_id=%s AND mmo_comment_no=%s", sql_number($melmaga_contents_id), sql_number($comment_no));
		$fetch = $this->fetch_db($sql);
		$this->melmaga_contents_id = $fetch->mmo_melmaga_contents_id;
		$this->comment_no = $fetch->mmo_comment_no;
		$this->mmo_user_name = $fetch->mmo_user_name;
		$this->mmo_comment = $fetch->mmo_comment;
		$this->mmo_ng_word = $fetch->mmo_ng_word;
		$this->mmo_patrol_check = $fetch->mmo_patrol_check;
		$this->mmo_patrol_date = $fetch->mmo_patrol_date;
		$this->mmo_patrol_id = $fetch->mmo_patrol_id;
		$this->mmo_admin_check = $fetch->mmo_admin_check;
		$this->mmo_admin_date = $fetch->mmo_admin_date;
		$this->mmo_admin_id = $fetch->mmo_admin_id;
		$this->mmo_open_flag = $fetch->mmo_open_flag;
		$this->mmo_date = $fetch->mmo_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
	}

	// ■DBからデータ読み込み（トラックバック全件）
	public function read_db_trackback_all($melmaga_contents_id) {
		$sql = sprintf("SELECT mmt_trackback_no,mmt_title,mmt_url,mmt_excerpt,mmt_trackback_no,mmt_ng_word"
		 . ",mmt_patrol_check,mmt_patrol_date,mmt_patrol_id,mmt_admin_check,mmt_admin_date,mmt_admin_id,mmt_open_flag,mmt_date,pvc_pv_count"
			. " FROM l_melmaga_trackback LEFT JOIN c_page_view ON mmt_pv_id=pvc_pv_id"
			. " WHERE mmt_melmaga_contents_id=%s ORDER BY mmt_date DESC", sql_number($melmaga_contents_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_tb[] = pg_fetch_object($result, $i);
	}

	// ■DBからデータ読み込み（トラックバック１件）
	public function read_db_trackback($melmaga_contents_id, $trackback_no) {
		$sql = sprintf("SELECT mmt_melmaga_contents_id,mmt_trackback_no,mmt_trackback_no,mmt_title,mmt_url,mmt_excerpt"
			. ",mmt_ng_word,mmt_patrol_check,mmt_patrol_date,mmt_patrol_id,mmt_admin_check,mmt_admin_date,mmt_admin_id,mmt_open_flag,mmt_date,pvc_pv_count"
			. " FROM l_melmaga_trackback LEFT JOIN c_page_view ON mmt_pv_id=pvc_pv_id"
			. " WHERE mmt_melmaga_contents_id=%s AND mmt_trackback_no=%s", sql_number($melmaga_contents_id), sql_number($trackback_no));
		$fetch = $this->fetch_db($sql);
		$this->melmaga_contents_id = $fetch->mmt_melmaga_contents_id;
		$this->trackback_no = $fetch->mmt_trackback_no;
		$this->mmt_title = $fetch->mmt_title;
		$this->mmt_url = $fetch->mmt_url;
		$this->mmt_excerpt = $fetch->mmt_excerpt;
		$this->mmt_ng_word = $fetch->mmt_ng_word;
		$this->mmt_patrol_check = $fetch->mmt_patrol_check;
		$this->mmt_patrol_date = $fetch->mmt_patrol_date;
		$this->mmt_patrol_id = $fetch->mmt_patrol_id;
		$this->mmt_admin_check = $fetch->mmt_admin_check;
		$this->mmt_admin_date = $fetch->mmt_admin_date;
		$this->mmt_admin_id = $fetch->mmt_admin_id;
		$this->mmt_open_flag = $fetch->mmt_open_flag;
		$this->mmt_date = $fetch->mmt_date;
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

	// ■フォームからデータ読み込み
	public function read_form() {
		switch($_POST['edit_kind']) {
		case 'bs':
			$this->edit_name = 'メールマガジン基本';
			$this->pickup = $_POST['pickup'];
			$this->communication_msg = $_POST['communication_msg'];
			break;
		case 'ct':
			$this->edit_name = 'メールマガジン詳細';
			$this->mmc_subject = $_POST['mmc_subject'];
			$this->mmc_body = $_POST['mmc_body'];
			$this->mmc_patrol_check = $_POST['mmc_patrol_check'];
			$this->mmc_admin_check = $_POST['mmc_admin_check'];
			$this->mmc_open_flag = $_POST['mmc_open_flag'];
			break;
		case 'tb':
			$this->edit_name = 'トラックバック';
			$this->trackback_no = $_POST['trackback_no'];
			$this->mmt_title = $_POST['mmt_title'];
			$this->mmt_url = $_POST['mmt_url'];
			$this->mmt_excerpt = $_POST['mmt_excerpt'];
			$this->mmt_patrol_check = $_POST['mmt_patrol_check'];
			$this->mmt_admin_check = $_POST['mmt_admin_check'];
			$this->mmt_open_flag = $_POST['mmt_open_flag'];
			break;
		case 'cm':
			$this->edit_name = 'コメント';
			$this->comment_no = $_POST['comment_no'];
			$this->mmo_user_name = $_POST['mmo_user_name'];
			$this->mmo_comment = $_POST['mmo_comment'];
			$this->mmo_patrol_check = $_POST['mmo_patrol_check'];
			$this->mmo_admin_check = $_POST['mmo_admin_check'];
			$this->mmo_open_flag = $_POST['mmo_open_flag'];
			break;
		};
		$this->melmaga_contents_id = $_POST['melmaga_contents_id'];
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		switch($_POST['edit_kind']) {
		case 'bs':
			$rec['mmc_pickup'] = sql_number($this->pickup);
			$rec['mmc_communication_msg'] = sql_char($this->communication_msg);
			db_update('t_melmaga_contents',$rec , "mmc_melmaga_contents_id=$this->melmaga_contents_id");
			break;
		case 'ct':
			$ngword = check_ngword($this->mmc_subject . $this->mmc_body);
			$rec['mmc_subject'] = sql_char($this->mmc_subject);
			$rec['mmc_body'] = sql_char($this->mmc_body);
			$rec['mmc_ng_word'] = sql_char($ngword);
			$rec['mmc_open_flag'] = sql_number($this->mmc_open_flag);

			if ($this->mmc_admin_check) {
				if ($this->mmc_admin_check != 1) {
					$rec['mmc_admin_check'] = sql_number($this->mmc_admin_check);
					$rec['mmc_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['mmc_admin_date'] = sql_char('now');
				} else {
					$rec['mmc_admin_check'] = 1;
					$rec['mmc_admin_id'] = sql_number('');
					$rec['mmc_admin_date'] = sql_char('');
				}
			}

			if ($this->mmc_patrol_check) {
				if ($this->mmc_patrol_check != 1) {
					$rec['mmc_patrol_check'] = sql_number($this->mmc_patrol_check);
					$rec['mmc_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['mmc_patrol_date'] = sql_char('now');
				} else {
					$rec['mmc_patrol_check'] = 1;
					$rec['mmc_patrol_id'] = sql_number('');
					$rec['mmc_patrol_date'] = sql_char('');
				}
			}
			db_update('t_melmaga_contents',$rec , "mmc_melmaga_contents_id=$this->melmaga_contents_id");
			break;
		case 'tb':
			$ngword = check_ngword($this->mmt_title . $this->mmt_excerpt);
			$rec['mmt_title'] = sql_char($this->mmt_title);
			$rec['mmt_url'] = sql_char($this->mmt_url);
			$rec['mmt_excerpt'] = sql_char($this->mmt_excerpt);
			$rec['mmt_ng_word'] = sql_char($ngword);
			$rec['mmt_open_flag'] = sql_number($this->mmt_open_flag);

			if ($this->mmt_admin_check) {
				if ($this->mmt_admin_check != 1) {
					$rec['mmt_admin_check'] = sql_char($this->mmt_admin_check);
					$rec['mmt_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['mmt_admin_date'] = sql_char('now');
				} else {
					$rec['mmt_admin_check'] = 1;
					$rec['mmt_admin_id'] = sql_number('');
					$rec['mmt_admin_date'] = sql_char('');
				}
			}

			if ($this->mmt_patrol_check) {
				if ($this->mmt_patrol_check != 1) {
					$rec['mmt_patrol_check'] = sql_char($this->mmt_patrol_check);
					$rec['mmt_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['mmt_patrol_date'] = sql_char('now');
				} else {
					$rec['mmt_patrol_check'] = 1;
					$rec['mmt_patrol_id'] = sql_number('');
					$rec['mmt_patrol_date'] = sql_char('');
				}
			}
			db_update('l_melmaga_trackback',$rec , "mmt_melmaga_contents_id=$this->melmaga_contents_id AND mmt_trackback_no=$this->trackback_no");
			break;
		case 'cm':
			$ngword = check_ngword($this->mmo_user_name . $this->mmo_comment);
			$rec['mmo_user_name'] = sql_char($this->mmo_user_name);
			$rec['mmo_comment'] = sql_char($this->mmo_comment);
			$rec['mmo_ng_word'] = sql_char($ngword);
			$rec['mmo_open_flag'] = sql_number($this->mmo_open_flag);

			if ($this->mmo_admin_check) {
				if ($this->mmo_admin_check != 1) {
					$rec['mmo_admin_check'] = sql_number($this->mmo_admin_check);
					$rec['mmo_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['mmo_admin_date'] = sql_char('now');
				} else {
					$rec['mmo_admin_check'] = 1;
					$rec['mmo_admin_id'] = sql_number('');
					$rec['mmo_admin_date'] = sql_char('');
				}
			}

			if ($this->mmo_patrol_check) {
				if ($this->mmo_patrol_check != 1) {
					$rec['mmo_patrol_check'] = sql_number($this->mmo_patrol_check);
					$rec['mmo_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['mmo_patrol_date'] = sql_char('now');
				} else {
					$rec['mmo_patrol_check'] = 1;
					$rec['mmo_patrol_id'] = sql_number('');
					$rec['mmo_patrol_date'] = sql_char('');
				}
			}
			db_update('l_melmaga_comment',$rec , "mmo_melmaga_contents_id=$this->melmaga_contents_id AND mmo_comment_no=$this->comment_no");
			break;
		}
		db_commit_trans();
	}
}
?>
