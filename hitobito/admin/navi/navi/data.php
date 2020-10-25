<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ登録情報処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class navi_data_class extends data_class {
	public $navi_id;
	public $mail_addr;
	public $password;
	public $forward_id;
	public $forward_flag;
	public $name1;
	public $name2;
	public $name1_kana;
	public $name2_kana;
	public $sex_cd;
	public $birthday;
	public $zip_cd;
	public $jitaku_tel;
	public $address1;
	public $address2;
	public $address3;
	public $shokugyo_id;
	public $status;
	public $regist_date;
	public $update_date;
	public $member_id;

	public $err;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($navi_id) {
		$sql = sprintf("SELECT * FROM t_navi LEFT JOIN t_member ON nav_member_id=mbr_member_id WHERE nav_navi_id = %s", sql_number($navi_id));
		$fetch = $this->fetch_db($sql);

		$this->navi_id = $fetch->nav_navi_id;
		$this->mail_addr = $fetch->mbr_mail_addr;
		$this->forward_id = $fetch->nav_forward_id;
		$this->forward_flag = $fetch->nav_forward_flag;
		$this->name1 = $fetch->nav_name1;
		$this->name2 = $fetch->nav_name2;
		$this->name1_kana = $fetch->nav_name1_kana;
		$this->name2_kana = $fetch->nav_name2_kana;
		$this->sex_cd = $fetch->nav_sex_cd;
		$this->birthday = get_datepart($fetch->nav_birthday);
		$this->zip_cd = $fetch->nav_zip_cd;
		$this->jitaku_tel = $fetch->nav_jitaku_tel;
		$this->address1 = $fetch->nav_address1;
		$this->address2 = $fetch->nav_address2;
		$this->address3 = $fetch->nav_address3;
		$this->shokugyo_id = $fetch->nav_shokugyo_id;
		$this->status = $fetch->nav_status;
		$this->regist_date = $fetch->nav_regist_date;
		$this->update_date = $fetch->nav_update_date;
		$this->member_id = $fetch->nav_member_id;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->navi_id = $_POST['navi_id'];
		$this->mail_addr = $_POST['mail_addr'];
		$this->password = $_POST['password'];
		$this->forward_id = $_POST['forward_id'];
		$this->forward_flag = $_POST['forward_flag'];
		$this->name1 = $_POST['name1'];
		$this->name2 = $_POST['name2'];
		$this->name1_kana = mb_convert_kana($_POST['name1_kana'], "KVC");
		$this->name2_kana = mb_convert_kana($_POST['name2_kana'], "KVC");
		$this->sex_cd = $_POST['sex_cd'];
		$this->birthday = $this->get_date('birthday');
		$this->zip_cd = $_POST['zip1'] . $_POST['zip2'];
		$this->jitaku_tel = $_POST['jitaku_tel'];
		$this->address1 = $_POST['address1'];
		$this->address2 = $_POST['address2'];
		$this->address3 = $_POST['address3'];
		$this->shokugyo_id = $_POST['shokugyo_id'];
		$this->status = $_POST['status'];
		$this->member_id = $_POST['member_id'];
	}

	// ■データチェック
	public function check() {
		return true;
	}


	// ■DBに追加
	public function insert_db() {

		db_begin_trans();

		$this->member_id = $this->check_member_id($this->mail_addr);
		if (!$this->member_id) {
			$rec['mbr_mail_addr'] = sql_char($this->mail_addr);
			$rec['mbr_mail_addr_low'] = sql_char(strtolower($this->mail_addr));
			$rec['mbr_password'] = sql_char(md5($this->password));
			db_insert('t_member', $rec);

			$this->member_id = get_current_seq('t_member', 'mbr_member_id');
		}

		if (!$this->check_navi_forward_id($this->member_id, $this->forward_id)) {
			$rec['nav_member_id'] = sql_char($this->member_id);
			$rec['nav_forward_id'] = sql_char($this->forward_id);
			$rec['nav_forward_flag'] = sql_number($this->forward_flag);
			$rec['nav_name1'] = sql_char($this->name1);
			$rec['nav_name2'] = sql_char($this->name2);
			$rec['nav_name1_kana'] = sql_char($this->name1_kana);
			$rec['nav_name2_kana'] = sql_char($this->name2_kana);
			$rec['nav_sex_cd'] = sql_number($this->sex_cd);
			$rec['nav_birthday'] = sql_date2($this->birthday);
			$rec['nav_zip_cd'] = sql_char($this->zip_cd);
			$rec['nav_jitaku_tel'] = sql_char($this->jitaku_tel);
			$rec['nav_address1'] = sql_char($this->address1);
			$rec['nav_address2'] = sql_char($this->address2);
			$rec['nav_address3'] = sql_char($this->address3);
			$rec['nav_shokugyo_id'] = sql_number($this->shokugyo_id);
			$rec['nav_status'] = sql_number($this->status);
			db_insert('t_navi', $rec);
		} else {
			$this->err = 1;
		}

		db_commit_trans();
	}


	// ■DBを更新
	public function update_db() {

		if (!$this->check_navi_forward_id($this->member_id, $this->forward_id)) {

			db_begin_trans();

			// メンバー情報更新
			$rec['mbr_mail_addr'] = sql_char($this->mail_addr);
			$rec['mbr_mail_addr_low'] = sql_char(strtolower($this->mail_addr));
			$rec['mbr_update_date'] = 'current_timestamp';
			if ($this->password != '')
				$rec['mbr_password'] = sql_char(md5($this->password));
			db_update('t_member',$rec , "mbr_member_id=$this->member_id");

			// ナビ情報更新
			$rec['nav_forward_id'] = sql_char($this->forward_id);
			$rec['nav_forward_flag'] = sql_number($this->forward_flag);
			$rec['nav_name1'] = sql_char($this->name1);
			$rec['nav_name2'] = sql_char($this->name2);
			$rec['nav_name1_kana'] = sql_char($this->name1_kana);
			$rec['nav_name2_kana'] = sql_char($this->name2_kana);
			$rec['nav_sex_cd'] = sql_number($this->sex_cd);
			$rec['nav_birthday'] = sql_date2($this->birthday);
			$rec['nav_zip_cd'] = sql_number($this->zip_cd);
			$rec['nav_jitaku_tel'] = sql_char($this->jitaku_tel);
			$rec['nav_address1'] = sql_number($this->address1);
			$rec['nav_address2'] = sql_char($this->address2);
			$rec['nav_address3'] = sql_char($this->address3);
			$rec['nav_shokugyo_id'] = sql_char($this->shokugyo_id);
	//		$rec['nav_status'] = sql_char($this->status);
			$rec['nav_update_date'] = sql_date(now);
			db_update('t_navi',$rec , "nav_navi_id=$this->navi_id");

			db_commit_trans();

		} else {
			$this->err = 1;
		}
	}

	// ■メンバー登録確認
	protected function check_member_id ($mail_addr) {
		$sql = sprintf("SELECT mbr_member_id FROM t_member WHERE mbr_mail_addr=%s OR mbr_mail_addr_low=%s", sql_char($mail_addr), sql_char($mail_addr));
		return db_fetch1($sql);
	}

	// ■ナビ メンバーID重複確認
	protected function check_navi_member_id ($member_id) {
		$sql = sprintf("SELECT nav_navi_id FROM t_navi WHERE nav_member_id=%s", sql_number($member_id));
		return db_fetch1($sql);
	}

	// ■ナビ 転送アドレスID重複確認
	protected function check_navi_forward_id ($member_id, $forward_id) {
		$sql = sprintf("SELECT nav_navi_id FROM t_navi WHERE nav_member_id<>%s AND nav_forward_id=%s", sql_number($member_id), sql_char($forward_id));
		return db_fetch1($sql);
	}

}
?>