<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 問い合わせ管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

class inquiry_data_class extends data_class {
	public $edit_kind;
	public $inquiry_id;
	public $navi_page_id;
	public $nvp_title;
	public $navi_id;
	public $admin_check;
	public $admin_date;
	public $admin_id;
	public $contact;

	public function __construct() {
		parent::__construct();

	}

	// ■DBからデータ読み込み
	public function read_db_contents($inquiry_id) {
		$sql = sprintf("SELECT * FROM t_navi_inquiry LEFT JOIN t_navi_page ON nvi_navi_page_id=nvp_navi_page_id"
			. " WHERE nvi_inquiry_id=%s", sql_number($inquiry_id));
		$fetch = $this->fetch_db($sql);

		$this->inquiry_id = $fetch->nvi_inquiry_id;
		$this->navi_page_id = $fetch->nvi_navi_page_id;
		$this->navi_id = $fetch->nvp_navi_id;
		$this->admin_check = $fetch->nvi_admin_check;
		$this->admin_date = $fetch->nvi_admin_date;
		$this->admin_id = $fetch->nvi_admin_id;
		$this->communication_msg = $fetch->nvi_communication_msg;

		$this->company_name = $fetch->nvi_company_name;
		$this->section_name = $fetch->nvi_section_name;
		$this->name = $fetch->nvi_name;
		$this->mail_addr = $fetch->nvi_mail_addr;
		$this->tel_no = $fetch->nvi_tel_no;
		$this->fax_no = $fetch->nvi_fax_no;
		$this->inquiry_type = $fetch->nvi_inquiry_type;
		$this->content = $fetch->nvi_content;
		$this->inquiry_date = $fetch->nvi_date;

		// ナビ回答情報
		$sql = sprintf("SELECT * FROM t_navi_answer WHERE nva_inquiry_id=%s ORDER BY nva_date", sql_number($inquiry_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++)
			$this->fetch[] = pg_fetch_object($result, $i);
	}

	// ■DBからデータ読み込み
	public function read_form() {
		$this->inquiry_id = $_POST['inquiry_id'];
		$this->admin_check = $_POST['admin_check'];
		$this->communication_msg = $_POST['communication_msg'];
	}

	// ■DBを更新
	public function update_db() {
		if ($this->admin_check != 1) {
			$rec['nvi_admin_check'] = sql_number($this->admin_check);
			$rec['nvi_communication_msg'] = sql_char($this->communication_msg);
			$rec['nvi_admin_id'] = sql_number($_SESSION['admin_login_id']);
			$rec['nvi_admin_date'] = sql_char('now');
			db_update('t_navi_inquiry',$rec , "nvi_inquiry_id=$this->inquiry_id");
		}
	}
}
?>
