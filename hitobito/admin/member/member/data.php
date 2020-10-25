<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メンバー登録情報処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class member_data_class extends data_class {
	public $member_id;
	public $nickname;
	public $status;
	public $regist_date;
	public $update_date;
	public $mail_addr;
	public $password;
	public $hitobito_letter;
	public $undeliver;
	public $ml_status = array();
	public $name;
	public $name_kana;
	public $sex_cd;
	public $birthday;
	public $zip_cd;
	public $address1;
	public $address2;
	public $tel;
	public $intro;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($member_id) {
		$sql = sprintf("SELECT * FROM t_member LEFT JOIN c_undeliver ON mbr_member_id = und_member_id WHERE mbr_member_id = %s", sql_number($member_id));
		$fetch = $this->fetch_db($sql);

		$this->member_id = $fetch->mbr_member_id;
		$this->status = $fetch->mbr_status;
		$this->regist_date = $fetch->mbr_regist_date;
		$this->update_date = $fetch->mbr_update_date;
		$this->mail_addr = $fetch->mbr_mail_addr;
		$this->name = $fetch->mbr_name;
		$this->name_kana = $fetch->mbr_name_kana;
		$this->sex_cd = $fetch->mbr_sex_cd;
		$this->birthday = get_datepart($fetch->mbr_birthday);
		$this->zip_cd = trim($fetch->mbr_zip_cd);
		$this->address1 = $fetch->mbr_address1;
		$this->address2 = $fetch->mbr_address2;
		$this->tel = explode('-', $fetch->mbr_tel);
		$this->intro = $fetch->mbr_self_introduction;
		$this->hitobito_letter = $fetch->mbr_hitobito_letter;
		$this->undeliver = $fetch->und_count;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->member_id = $_POST['member_id'];
		$this->nickname = $_POST['nickname'];
		$this->status = $_POST['status'];
		$this->mail_addr = $_POST['mail_addr'];
		$this->password = $_POST['password'];
		$this->name = $_POST['name'];
		$this->name_kana = $_POST['name_kana'];
		$this->sex_cd = $_POST['sex_cd'];
		$this->birthday = $this->get_date('birthday');
		$this->zip_cd = $_POST['zip1'] . $_POST['zip2'];
		$this->address1 = $_POST['address1'];
		$this->address2 = $_POST['address2'];
		$this->tel = $_POST['tel1'] . '-' . $_POST['tel2'] . '-' . $_POST['tel3'];
		$this->intro = $_POST['intro'];
		$this->hitobito_letter = $_POST['hitobito_letter'];
		$this->ml_status = $_POST['ml_status'];
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

		// メンバー情報更新
		$rec['mbr_mail_addr'] = sql_char($this->mail_addr);
		$rec['mbr_mail_addr_low'] = sql_char(strtolower($this->mail_addr));
		$rec['mbr_name'] = sql_char($this->name);
		$rec['mbr_name_kana'] = sql_char($this->name_kana);
		$rec['mbr_sex_cd'] = sql_char($this->sex_cd);
		$rec['mbr_zip_cd'] = sql_char($this->zip_cd);
		$rec['mbr_address1'] = sql_number($this->address1);
		$rec['mbr_address2'] = sql_char($this->address2);
		$rec['mbr_tel'] = sql_char($this->tel);
		$rec['mbr_self_introduction'] = sql_char($this->intro);
		$rec['mbr_hitobito_letter'] = sql_number($this->hitobito_letter);
		$rec['mbr_status'] = sql_number($this->status);
		$rec['mbr_update_date'] = 'current_timestamp';
		if ($this->birthday['y'] && $this->birthday['m'] && $this->birthday['d'])
			$rec['mbr_birthday'] = sql_date2($this->birthday);
		if ($this->password != '')
			$rec['mbr_password'] = sql_char(md5($this->password));
		db_update('t_member',$rec , "mbr_member_id=$this->member_id");

		// ML情報更新
		if (is_array($this->ml_status)) {
			foreach ($this->ml_status as $room_id => $ml_status) {
				$rec['rmm_nickname'] = sql_char($this->nickname[$room_id]);
				$rec['rmm_status'] = sql_number($ml_status);
				db_update('t_room_member', $rec, "rmm_room_id=$room_id AND rmm_member_id=$this->member_id");
			}
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