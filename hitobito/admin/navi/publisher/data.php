<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ情報処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class ml_data_class extends data_class {
	public $melmaga_id;
	public $melmaga_name;
	public $navi_name;
	public $navi_page_id;
	public $edit_navi_page_id;
	public $status;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($melmaga_id) {
		$sql = sprintf("SELECT mmg_melmaga_id, mmg_melmaga_name, nav_name1, nav_name2, mmg_status, mmg_regist_date, mmg_update_date, nvp_navi_page_id, mmn_reader_cnt, mmn_publish_cnt, mmn_last_publish_date"
			. " FROM t_melmaga LEFT JOIN t_navi_page ON mmg_melmaga_id = nvp_melmaga_id"
			. " LEFT JOIN c_melmaga_count ON mmg_melmaga_id = mmn_melmaga_id"
			. " LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id"
			. " WHERE mmg_melmaga_id = %s", sql_number($melmaga_id));
		$fetch = $this->fetch_db($sql);

		$this->melmaga_id = $fetch->mmg_melmaga_id;
		$this->melmaga_name = $fetch->mmg_melmaga_name;
		$this->navi_page_id = $fetch->nvp_navi_page_id;
		$this->navi_name = $fetch->nav_name1 . ' ' . $fetch->nav_name2;
		$this->status = $fetch->mmg_status;
		$this->regist_date = $fetch->mmg_regist_date;
		$this->update_date = $fetch->mmg_update_date;
		$this->reader_cnt = $fetch->mmn_reader_cnt;
		$this->publish_cnt = $fetch->mmn_publish_cnt;
		$this->last_publish = $fetch->mmn_last_publish_date;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->melmaga_id = $_POST['melmaga_id'];
		$this->melmaga_name = $_POST['melmaga_name'];
		$this->navi_page_id = $_POST['navi_page_id'];
		$this->edit_navi_page_id = $_POST['edit_navi_page_id'];
		$this->status = $_POST['status'];
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

		// メルマガ情報更新
		$rec['mmg_melmaga_name'] = sql_char($this->melmaga_name);
		$rec['mmg_status'] = sql_number($this->status);
		$rec['mmg_update_date'] = sql_date('now');
		db_update('t_melmaga',$rec , "mmg_melmaga_id=$this->melmaga_id");

		// ナビページ情報更新
		if ($this->navi_page_id != $this->edit_navi_page_id) {
			$rec['nvp_melmaga_id'] = sql_number('');
			$rec['nvp_update_date'] = sql_date('now');
			db_update('t_navi_page',$rec , "nvp_navi_page_id=$this->navi_page_id");

			$rec['nvp_melmaga_id'] = sql_number($this->melmaga_id);
			$rec['nvp_update_date'] = sql_date('now');
			db_update('t_navi_page',$rec , "nvp_navi_page_id=$this->edit_navi_page_id");
		}
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