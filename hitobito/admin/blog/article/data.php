<?php
/**
 * ひとびと･net 事務局ページ
 *
 * Blog（ナビ記事）管理情報処理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class blog_data_class extends data_class {
	public $edit_name;
	public $nvp_navi_page_id;
	public $article_id;
	public $blog_id;
	public $navi_page_title;
	public $navi_id;
	public $pickup;
	public $navi_seelct;
	public $communication_msg;


	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み
	public function read_db($article_id) {
		$this->new = false;

		$sql = sprintf("SELECT bla_article_id,nvp_navi_page_id,nvp_blog_id,nvp_title,nvp_navi_id,bla_pickup,bla_navi_select_flag,bla_communication_msg"
			. ",bla_title,bla_article_text,bla_ng_word,bla_patrol_check,bla_patrol_id,bla_patrol_date,bla_admin_check,bla_admin_id,bla_admin_date,bla_open_flag,bla_date,pvc_pv_count"
			. " FROM t_blog_article LEFT JOIN t_navi_page ON bla_blog_id=nvp_blog_id"
			. " LEFT JOIN c_page_view ON bla_pv_id=pvc_pv_id"
			. " WHERE bla_article_id=%s", sql_number($article_id));
		$fetch = $this->fetch_db($sql);

		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->article_id = $fetch->bla_article_id;
		$this->blog_id = $fetch->nvp_blog_id;
		$this->navi_page_title = $fetch->nvp_title;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->pickup = $fetch->bla_pickup;
		$this->navi_select = $fetch->bla_navi_select_flag;
		$this->communication_msg = $fetch->bla_communication_msg;

		// 投稿記事情報(全件)
		$this->bla_title = $fetch->bla_title;
		$this->bla_article_text = $fetch->bla_article_text;
		$this->bla_ng_word = $fetch->bla_ng_word;
		$this->bla_patrol_check = $fetch->bla_patrol_check;
		$this->bla_patrol_date = $fetch->bla_patrol_date;
		$this->bla_patrol_id = $fetch->bla_patrol_id;
		$this->bla_admin_check = $fetch->bla_admin_check;
		$this->bla_admin_date = $fetch->bla_admin_date;
		$this->bla_admin_id = $fetch->bla_admin_id;
		$this->bla_open_flag = $fetch->bla_open_flag;
		$this->bla_date = $fetch->bla_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
		$this->bla_class = $this->get_bar_class($this->bla_patrol_check, $this->bla_admin_check, $this->bla_ng_word);

		// トラックバック情報
		$sql = sprintf("SELECT * FROM t_blog_trackback LEFT JOIN c_page_view ON blt_pv_id=pvc_pv_id WHERE blt_article_id=%s", sql_number($article_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_tb[] = pg_fetch_object($result, $i);

		// コメント情報情報
		$sql = sprintf("SELECT * FROM t_blog_comment LEFT JOIN c_page_view ON blo_pv_id=pvc_pv_id WHERE blo_article_id=%s", sql_number($article_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch_cm[] = pg_fetch_object($result, $i);
	}

	// ■トラックバックデータ読み込み
	function read_db_tb($article_id, $trackback_no) {
		$sql = sprintf("SELECT * FROM t_blog_trackback LEFT JOIN c_page_view ON blt_pv_id=pvc_pv_id WHERE blt_article_id=%s AND blt_trackback_no=%s", sql_number($article_id), sql_number($trackback_no));
		$fetch = $this->fetch_db($sql);

		$this->article_id = $fetch->blt_article_id;
		$this->trackback_no = $fetch->blt_trackback_no;
		$this->blt_title = $fetch->blt_title;
		$this->blt_url = $fetch->blt_url;
		$this->blt_excerpt = $fetch->blt_excerpt;
		$this->blt_ng_word = $fetch->blt_ng_word;
		$this->blt_patrol_check = $fetch->blt_patrol_check;
		$this->blt_patrol_date = $fetch->blt_patrol_date;
		$this->blt_patrol_id = $fetch->blt_patrol_id;
		$this->blt_admin_check = $fetch->blt_admin_check;
		$this->blt_admin_date = $fetch->blt_admin_date;
		$this->blt_admin_id = $fetch->blt_admin_id;
		$this->blt_open_flag = $fetch->blt_open_flag;
		$this->blt_date = $fetch->blt_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
	}

	// ■コメントデータ読み込み
	function read_db_cm($article_id, $comment_no) {
		$sql = sprintf("SELECT * FROM t_blog_comment LEFT JOIN c_page_view ON blo_pv_id=pvc_pv_id WHERE blo_article_id=%s AND blo_comment_no=%s", sql_number($article_id), sql_number($comment_no));
		$fetch = $this->fetch_db($sql);

		$this->article_id = $fetch->blo_article_id;
		$this->comment_no = $fetch->blo_comment_no;
		$this->blo_user_name = $fetch->blo_user_name;
		$this->blo_comment = $fetch->blo_comment;
		$this->blo_ng_word = $fetch->blo_ng_word;
		$this->blo_patrol_check = $fetch->blo_patrol_check;
		$this->blo_patrol_date = $fetch->blo_patrol_date;
		$this->blo_patrol_id = $fetch->blo_patrol_id;
		$this->blo_admin_check = $fetch->blo_admin_check;
		$this->blo_admin_date = $fetch->blo_admin_date;
		$this->blo_admin_id = $fetch->blo_admin_id;
		$this->blo_open_flag = $fetch->blo_open_flag;
		$this->blo_date = $fetch->blo_date;
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
			$this->edit_name = 'ナビ記事基本';
			$this->pickup = $_POST['pickup'];
			$this->navi_select = $_POST['navi_select'] ? 1 : 0;
			$this->communication_msg = $_POST['communication_msg'];
			break;
		case 'ct':
			$this->edit_name = 'ナビ記事詳細';
			$this->bla_title = $_POST['bla_title'];
			$this->bla_article_text = $_POST['bla_article_text'];
			$this->bla_patrol_check = $_POST['bla_patrol_check'];
			$this->bla_admin_check = $_POST['bla_admin_check'];
			$this->bla_open_flag = $_POST['bla_open_flag'];
			break;
		case 'tb':
			$this->edit_name = 'ナビ記事トラックバック';
			$this->trackback_no = $_POST['trackback_no'];
			$this->blt_title = $_POST['blt_title'];
			$this->blt_url = $_POST['blt_url'];
			$this->blt_excerpt = $_POST['blt_excerpt'];
			$this->blt_patrol_check = $_POST['blt_patrol_check'];
			$this->blt_admin_check = $_POST['blt_admin_check'];
			$this->blt_open_flag = $_POST['blt_open_flag'];
			break;
		case 'cm':
			$this->edit_name = 'ナビ記事コメント';
			$this->comment_no = $_POST['comment_no'];
			$this->blo_user_name = $_POST['blo_user_name'];
			$this->blo_comment = $_POST['blo_comment'];
			$this->blo_patrol_check = $_POST['blo_patrol_check'];
			$this->blo_admin_check = $_POST['blo_admin_check'];
			$this->blo_open_flag = $_POST['blo_open_flag'];
			break;
		}
		$this->article_id = $_POST['article_id'];
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		switch($_POST['edit_kind']) {
		case 'bs':
			$rec['bla_pickup'] = sql_number($this->pickup);
			$rec['bla_navi_select_flag'] = sql_number($this->navi_select);
			$rec['bla_communication_msg'] = sql_char($this->communication_msg);
			db_update('t_blog_article',$rec , "bla_article_id=$this->article_id");
			break;
		case 'ct':
			$ngword = check_ngword($this->bla_title . $this->bla_article_text);
			$rec['bla_title'] = sql_char($this->bla_title);
			$rec['bla_article_text'] = sql_char($this->bla_article_text);
			$rec['bla_ng_word'] = sql_char($ngword);
			$rec['bla_open_flag'] = sql_number($this->bla_open_flag);

			if ($this->bla_admin_check) {
				if ($this->bla_admin_check != 1) {
					$rec['bla_admin_check'] = sql_number($this->bla_admin_check);
					$rec['bla_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['bla_admin_date'] = sql_char('now');
				} else {
					$rec['bla_admin_check'] = 1;
					$rec['bla_admin_id'] = sql_number('');
					$rec['bla_admin_date'] = sql_char('');
				}
			}

			if ($this->bla_patrol_check) {
				if ($this->bla_patrol_check != 1) {
					$rec['bla_patrol_check'] = sql_number($this->bla_patrol_check);
					$rec['bla_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['bla_patrol_date'] = sql_char('now');
				} else {
					$rec['bla_patrol_check'] = 1;
					$rec['bla_patrol_id'] = sql_number('');
					$rec['bla_patrol_date'] = sql_char('');
				}
			}
			db_update('t_blog_article',$rec , "bla_article_id=$this->article_id");
			break;
		case 'tb':
			$ngword = check_ngword($this->blt_title . $this->blt_excerpt);
			$rec['blt_title'] = sql_char($this->blt_title);
			$rec['blt_url'] = sql_char($this->blt_url);
			$rec['blt_excerpt'] = sql_char($this->blt_excerpt);
			$rec['blt_ng_word'] = sql_char($ngword);
			$rec['blt_open_flag'] = sql_number($this->blt_open_flag);

			if ($this->blt_admin_check) {
				if ($this->blt_admin_check != 1) {
					$rec['blt_admin_check'] = sql_number($this->blt_admin_check);
					$rec['blt_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['blt_admin_date'] = sql_char('now');
				} else {
					$rec['blt_admin_check'] = 1;
					$rec['blt_admin_id'] = sql_number('');
					$rec['blt_admin_date'] = sql_char('');
				}
			}

			if ($this->blt_patrol_check) {
				if ($this->blt_patrol_check != 1) {
					$rec['blt_patrol_check'] = sql_number($this->blt_patrol_check);
					$rec['blt_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['blt_patrol_date'] = sql_char('now');
				} else {
					$rec['blt_patrol_check'] = 1;
					$rec['blt_patrol_id'] = sql_number('');
					$rec['blt_patrol_date'] = sql_char('');
				}
			}
			db_update('t_blog_trackback',$rec , "blt_article_id=$this->article_id AND blt_trackback_no=$this->trackback_no");
			break;
		case 'cm':
			$ngword = check_ngword($this->blo_user_name . $this->blo_comment);
			$rec['blo_user_name'] = sql_char($this->blo_user_name);
			$rec['blo_comment'] = sql_char($this->blo_comment);
			$rec['blo_ng_word'] = sql_char($ngword);
			$rec['blo_open_flag'] = sql_number($this->blo_open_flag);

			if ($this->blo_admin_check) {
				if ($this->blo_admin_check != 1) {
					$rec['blo_admin_check'] = sql_number($this->blo_admin_check);
					$rec['blo_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['blo_admin_date'] = sql_char('now');
				} else {
					$rec['blo_admin_check'] = 1;
					$rec['blo_admin_id'] = sql_number('');
					$rec['blo_admin_date'] = sql_char('');
				}
			}

			if ($this->blo_patrol_check) {
				if ($this->blo_patrol_check != 1) {
					$rec['blo_patrol_check'] = sql_number($this->blo_patrol_check);
					$rec['blo_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['blo_patrol_date'] = sql_char('now');
				} else {
					$rec['blo_patrol_check'] = 1;
					$rec['blo_patrol_id'] = sql_number('');
					$rec['blo_patrol_date'] = sql_char('');
				}
			}
			db_update('t_blog_comment',$rec , "blo_article_id=$this->article_id AND blo_comment_no=$this->comment_no");
			break;
		}
		db_commit_trans();
	}
}

?>
