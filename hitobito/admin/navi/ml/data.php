<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビML情報処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class ml_data_class extends data_class {
	public $room_id;
	public $name;
	public $open_date;
	public $status;
	public $banner_id;
	public $banner_image_url;
	public $banner_link_url;
	public $room_code;
	public $required_item = array();

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($room_id) {
		$sql = sprintf("SELECT * FROM t_room LEFT JOIN t_banner ON rom_banner_id=bnr_banner_id WHERE rom_room_id = %s", sql_number($room_id));
		$fetch = $this->fetch_db($sql);

		$this->room_id = $fetch->rom_room_id;
		$this->status = $fetch->rom_status;
		$this->regist_date = $fetch->rom_regist_date;
		$this->update_date = $fetch->rom_update_date;
		$this->open_date = get_datepart($fetch->rom_open_date);
		$this->name = $fetch->rom_name;
		$this->banner_id = $fetch->rom_banner_id;
		$this->banner_image_url = $fetch->bnr_image_url;
		$this->banner_link_url = $fetch->bnr_link_url;
		$this->room_code = $fetch->rom_room_code;
		$this->required_item = explode(',', $fetch->rom_required_item);

		// 会議UU/会議PV
		$sql = sprintf("SELECT COUNT(*) AS pv_num, COUNT(DISTINCT pvl_ip_addr) AS uu_num, MAX(pvl_date) AS last_date"
			. " FROM l_page_view JOIN l_room_remark ON pvl_pv_id=rmk_pv_id"
			. " WHERE rmk_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->pv_num = $fetch->pv_num;
		$this->uu_num = $fetch->uu_num;
		$this->last_date = $fetch->last_date;

		// バナークリック
		$sql = sprintf("SELECT COUNT(*) AS click_num, MAX(bnl_date) AS last_date"
			. " FROM l_banner_click LEFT JOIN t_room ON  bnl_banner_id=rom_banner_id"
			. " WHERE rom_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->click_num = $fetch->click_num;
		$this->click_last_date = $fetch->last_date;

		// メンバー数
		$sql = sprintf("SELECT COUNT(*) AS member_num, MAX(rmm_regist_date) AS last_date"
			. " FROM t_room_member WHERE rmm_status=3 AND rmm_resign_date IS NULL AND rmm_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->member_num = $fetch->member_num;
		$this->member_last_date = $fetch->last_date;

		// 入会者数
		$sql = sprintf("SELECT COUNT(*) AS regist_num, MAX(rmm_regist_date) AS last_date"
			. " FROM t_room_member WHERE rmm_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->regist_num = $fetch->regist_num;
		$this->regist_last_date = $fetch->last_date;

		// 退会者数
		$sql = sprintf("SELECT COUNT(*) AS resign_num_num, MAX(rmm_resign_date) AS last_date"
			. " FROM t_room_member WHERE rmm_resign_date IS NOT NULL AND rmm_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->resign_num = $fetch->resign_num_num;
		$this->resign_last_date = $fetch->last_date;

		// 発言者数
		$sql = sprintf("SELECT COUNT(*) AS remark_member_num, MAX(rmk_date) AS last_date"
			. " FROM l_room_remark"
			. " WHERE rmk_room_id=%s"
			. " GROUP BY rmk_member_id"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->remark_member_num = $fetch->remark_member_num;
		$this->remark_member_last_date = $fetch->last_date;

		// 発言数
		$sql = sprintf("SELECT COUNT(*) AS remark_num, MAX(rmk_date) AS last_date"
			. " FROM l_room_remark"
			. " WHERE rmk_room_id=%s"
			, sql_number($room_id));
		$fetch = $this->fetch_db($sql);
		$this->remark_num = $fetch->remark_num;
		$this->remark_last_date = $fetch->last_date;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->room_id = $_POST['room_id'];
		$this->banner_id = $_POST['banner_id'];
		$this->name = $_POST['name'];
		$this->open_date = $this->get_date('open_date');
		$this->status = $_POST['status'];
		$this->banner_image_url = $_POST['banner_image_url'];
		$this->banner_link_url = $_POST['banner_link_url'];
		$this->room_code = $_POST['room_code'];
		$this->required_item = $_POST['required_item'];
	}

	// ■データチェック
	public function check() {
		return true;
	}

/*
	// ■DBに追加
	public function insert_db() {
		db_insert('t_member', $rec);
	}
*/

	// ■DBを更新
	public function update_db() {

		db_begin_trans();

		$sql = sprintf("SELECT bnr_banner_id FROM t_banner LEFT JOIN t_room ON bnr_banner_id=rom_banner_id WHERE rom_room_id=%s", sql_number($this->room_id));
		$this->banner_id = db_fetch1($sql);

		if ($this->banner_id) {
			// バナー情報更新
			$rec['bnr_image_url'] = sql_char($this->banner_image_url);
			$rec['bnr_link_url'] = sql_char($this->banner_link_url);
			db_update('t_banner',$rec , "bnr_banner_id=$this->banner_id");
		} else {
			// バナー情報追加
			$rec['bnr_image_url'] = sql_char($this->banner_image_url);
			$rec['bnr_link_url'] = sql_char($this->banner_link_url);
			db_insert('t_banner',$rec);

			$this->banner_id = get_current_seq('t_banner', 'bnr_banner_id');
		}

		// 会議室情報更新
		$rec['rom_name'] = sql_char($this->name);
		$rec['rom_open_date'] = sql_date2($this->open_date);
		$rec['rom_status'] = sql_number($this->status);
		$rec['rom_update_date'] = sql_date('now');
		$rec['rom_banner_id'] = sql_number($this->banner_id);
		$rec['rom_room_code'] = sql_char($this->room_code);
		$rec['rom_required_item'] = is_array($this->required_item) ? sql_char(implode(',', $this->required_item)) : sql_char($this->required_item);
		db_update('t_room',$rec , "rom_room_id=$this->room_id");

		db_commit_trans();
	}
/*
	// ■DBから削除
	public function delete_db() {
		db_delete('t_member', "mbr_member_id=$this->member_id");
	}
*/
}
?>