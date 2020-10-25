<?php
/**
 * ひとびと･net 事務局ページ
 *
 * リンク品質管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class link_data_class extends data_class {
	public $edit_kind;												// 基本情報/ナビ記事/トラックバック/コメント
	public $link_id;
	public $navi_page_id;
	public $nvp_title;
	public $navi_id;
	public $pickup;
	public $communication_msg;

	public $link_name;
	public $link_url;
	public $link_outline;
	public $ng_word;
	public $patrol_check;
	public $patrol_date;
	public $patrol_id;
	public $admin_check;
	public $admin_date;
	public $admin_id;
	public $open_flag;
	public $date;
	public $view_count;
	public $click_count;
	public $class;

	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み
	public function read_db_contents($link_id) {
		$sql = sprintf("SELECT nol_osusume_link_id,nvp_navi_page_id,nvp_navi_id,nvp_title,nvp_navi_id,nol_pickup,nol_communication_msg,nol_link_name,nol_link_url,nol_link_outline"
			.",nol_ng_word,nol_patrol_check,nol_patrol_date,nol_patrol_id,nol_admin_check,nol_admin_date,nol_admin_id,nol_open_flag,nol_date,pvc_pv_count,noc_click_count"
			. " FROM t_navi_osusume_link LEFT JOIN t_navi_page ON nol_navi_page_id=nvp_navi_page_id"
			. " LEFT JOIN c_navi_osusume_link ON nol_osusume_link_id=noc_osusume_link_id"
			. " LEFT JOIN c_page_view ON nol_pv_id=pvc_pv_id"
			. " WHERE nol_osusume_link_id=%s", sql_number($link_id));
		$fetch = $this->fetch_db($sql);

		$this->link_id = $fetch->nol_osusume_link_id;
		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->nvp_title = $fetch->nvp_title;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->pickup = $fetch->nol_pickup;
		$this->communication_msg = $fetch->nol_communication_msg;
		$this->link_name = $fetch->nol_link_name;
		$this->link_url = $fetch->nol_link_url;
		$this->link_outline = $fetch->nol_link_outline;
		$this->ng_word = $fetch->nol_ng_word;
		$this->patrol_check = $fetch->nol_patrol_check;
		$this->patrol_date = $fetch->nol_patrol_date;
		$this->patrol_id = $fetch->nol_patrol_id;
		$this->admin_check = $fetch->nol_admin_check;
		$this->admin_date = $fetch->nol_admin_date;
		$this->admin_id = $fetch->nol_admin_id;
		$this->open_flag = $fetch->nol_open_flag;
		$this->date = $fetch->nol_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;
		$this->click_count = $fetch->noc_click_count;

		$this->class = $this->get_bar_class($this->patrol_check, $this->admin_check, $this->ng_word);
	}

	// ■表示バー色取得
	protected function get_bar_class($patrol_check, $admin_check, $ng_word) {
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
			$this->edit_name = 'リンク基本';
			$this->pickup = $_POST['pickup'];
			$this->communication_msg = $_POST['communication_msg'];
			break;
		case 'ct':
			$this->edit_name = 'リンク詳細';
			$this->link_name = $_POST['link_name'];
			$this->link_url = $_POST['link_url'];
			$this->link_outline = $_POST['link_outline'];
			$this->patrol_check = $_POST['patrol_check'];
			$this->admin_check = $_POST['admin_check'];
			$this->open_flag = $_POST['open_flag'];
			break;
		}
		$this->link_id = $_POST['link_id'];
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		switch($_POST['edit_kind']) {
		case 'bs':
			$rec['nol_pickup'] = sql_number($this->pickup);
			$rec['nol_communication_msg'] = sql_char($this->communication_msg);
			db_update('t_navi_osusume_link',$rec , "nol_osusume_link_id=$this->link_id");
			break;
		case 'ct':
			$ngword = check_ngword($this->link_name . $this->link_outline);
			$rec['nol_link_name'] = sql_char($this->link_name);
			$rec['nol_link_url'] = sql_char($this->link_url);
			$rec['nol_link_outline'] = sql_char($this->link_outline);
			$rec['nol_ng_word'] = sql_char($ngword);
			$rec['nol_open_flag'] = sql_number($this->open_flag);

			if ($this->admin_check) {
				if ($this->admin_check != 1) {
					$rec['nol_admin_check'] = sql_number($this->admin_check);
					$rec['nol_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['nol_admin_date'] = sql_char('now');
				} else {
					$rec['nol_admin_check'] = 1;
					$rec['nol_admin_id'] = sql_number('');
					$rec['nol_admin_date'] = sql_char('');
				}
			}

			if ($this->patrol_check) {
				if ($this->patrol_check != 1) {
					$rec['nol_patrol_check'] = sql_number($this->patrol_check);
					$rec['nol_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['nol_patrol_date'] = sql_char('now');
				} else {
					$rec['nol_patrol_check'] = 1;
					$rec['nol_patrol_id'] = sql_number('');
					$rec['nol_patrol_date'] = sql_char('');
				}
			}
			db_update('t_navi_osusume_link',$rec , "nol_osusume_link_id=$this->link_id");
			break;
		}

		db_commit_trans();
	}
}
?>
