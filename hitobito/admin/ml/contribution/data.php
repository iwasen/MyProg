<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 発言品質管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class room_remark_data_class extends data_class {
	public $edit_name;												// 基本情報/ナビ記事/トラックバック/コメント
	public $navi_page_id;
	public $remark_id;
	public $room_id;
	public $room_name;
	public $navi_id;
	public $navi_name;
	public $nav_navi_id;
	public $pickup;
	public $communication_msg;
	public $rmk_subject;
	public $rmk_room_header;
	public $rmk_content;
	public $rmk_room_footer;
	public $rmk__ng_word;
	public $rmk__patrol_check;
	public $rmk__patrol_date;
	public $rmk__patrol_id;
	public $rmk__admin_check;
	public $rmk__admin_date;
	public $rmk__admin_id;
	public $rmk__open_flag;
	public $rmk__date;
	public $rmv_view_count;
	public $rmk__class;

	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み（発言情報）
	public function read_db($remark_id) {
		$sql = sprintf("SELECT rmk_remark_id,rmk_room_id,rom_name,nvp_navi_id,trim(nav_name1||' '||COALESCE(nav_name2,'')) AS navi_name,rmk_pickup,rmk_communication_msg"
			. ",rmk_subject,rmk_room_header,rmk_content,rmk_room_footer,rmk_ng_word,rmk_patrol_check,rmk_patrol_date,rmk_patrol_id,rmk_admin_check,rmk_admin_date,rmk_admin_id,rmk_open_flag"
			. ",rmk_member_id,rmm_nickname,rmk_date,pvc_pv_count,nvp_navi_page_id,rmk_seq_no"
			. " FROM l_room_remark LEFT JOIN t_room ON rmk_room_id=rom_room_id"
			. " LEFT JOIN t_room_member ON rmk_member_id=rmm_member_id"
			. " LEFT JOIN t_navi_page ON rmk_room_id=nvp_room_id"
			. " LEFT JOIN t_navi ON nvp_navi_id=nav_navi_id"
			. " LEFT JOIN c_page_view ON rmk_pv_id=pvc_pv_id"
			. " WHERE rmk_remark_id=%s",sql_number($remark_id));
		$fetch = $this->fetch_db($sql);

		$this->remark_id = $fetch->rmk_remark_id;
		$this->room_id = $fetch->rmk_room_id;
		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->seq_no = $fetch->rmk_seq_no;
		$this->room_name = $fetch->rom_name;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->navi_name = $fetch->navi_name;
		$this->pickup = $fetch->rmk_pickup;
		$this->communication_msg = $fetch->rmk_communication_msg;

		// 投稿記事情報
		$this->rmk_subject = $fetch->rmk_subject;
		$this->rmk_room_header = $fetch->rmk_room_header;
		$this->rmk_content = $fetch->rmk_content;
		$this->rmk_room_footer = $fetch->rmk_room_footer;
		$this->rmk_ng_word = $fetch->rmk_ng_word;
		$this->rmk_patrol_check = $fetch->rmk_patrol_check;
		$this->rmk_patrol_date = $fetch->rmk_patrol_date;
		$this->rmk_patrol_id = $fetch->rmk_patrol_id;
		$this->rmk_admin_check = $fetch->rmk_admin_check;
		$this->rmk_admin_date = $fetch->rmk_admin_date;
		$this->rmk_admin_id = $fetch->rmk_admin_id;
		$this->rmk_open_flag = $fetch->rmk_open_flag;
		$this->rmk_date = $fetch->rmk_date;
		$this->pvc_pv_count = $fetch->pvc_pv_count;

		$this->rmk_class = $this->get_bar_class($this->rmk_patrol_check, $this->rmk_admin_check, $this->rmk_ng_word);
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
			$this->edit_name = 'ナビ記事基本';
			$this->pickup = $_POST['pickup'];
			$this->communication_msg = $_POST['communication_msg'];
			break;
		case 'ct':
			$this->edit_name = 'ナビ記事詳細';
			$this->rmk_subject = $_POST['rmk_subject'];
			$this->rmk_room_header = $_POST['rmk_room_header'];
			$this->rmk_content = $_POST['rmk_content'];
			$this->rmk_room_footer = $_POST['rmk_room_footer'];
			$this->rmk_patrol_check = $_POST['rmk_patrol_check'];
			$this->rmk_admin_check = $_POST['rmk_admin_check'];
			$this->rmk_open_flag = $_POST['rmk_open_flag'];
			break;
		}
		$this->remark_id = $_POST['remark_id'];
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		switch($_POST['edit_kind']) {
		case 'bs':
			$rec['rmk_pickup'] = sql_number($this->pickup);
			$rec['rmk_communication_msg'] = sql_char($this->communication_msg);
			db_update('l_room_remark',$rec , "rmk_remark_id=$this->remark_id");
			break;
		case 'ct':
			$ngword = check_ngword($this->rmk_subject . $this->rmk_room_header . $this->rmk_content . $this->rmk_room_footer);
			$rec['rmk_subject'] = sql_char($this->rmk_subject);
			$rec['rmk_room_header'] = sql_char($this->rmk_room_header);
			$rec['rmk_content'] = sql_char($this->rmk_content);
			$rec['rmk_room_footer'] = sql_char($this->rmk_room_footer);
			$rec['rmk_ng_word'] = sql_char($ngword);
			$rec['rmk_open_flag'] = sql_number($this->rmk_open_flag);

			if ($this->rmk_admin_check) {
				if ($this->rmk_admin_check != 1) {
					$rec['rmk_admin_check'] = sql_number($this->rmk_admin_check);
					$rec['rmk_admin_id'] = sql_number($_SESSION['admin_login_id']);
					$rec['rmk_admin_date'] = sql_char('now');
				} else {
					$rec['rmk_admin_check'] = 1;
					$rec['rmk_admin_id'] = sql_number('');
					$rec['rmk_admin_date'] = sql_char('');
				}
			}

			if ($this->rmk_patrol_check) {
				if ($this->rmk_patrol_check != 1) {
					$rec['rmk_patrol_check'] = sql_number($this->rmk_patrol_check);
					$rec['rmk_patrol_id'] = sql_number($_SESSION['patrol_login_id']);
					$rec['rmk_patrol_date'] = sql_char('now');
				} else {
					$rec['rmk_patrol_check'] = 1;
					$rec['rmk_patrol_id'] = sql_number('');
					$rec['rmk_patrol_date'] = sql_char('');
				}
			}
			db_update('l_room_remark',$rec , "rmk_remark_id=$this->remark_id");

			set_child_num($this->remark_id);
			break;
		}

		db_commit_trans();
	}
}
?>
