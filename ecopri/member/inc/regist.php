<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報クラス（仮登録）
'******************************************************/

class mm_regist1 {
	var $active;
	var $mb_seq_no;
	var $mb_id;
	var $mb_mail_addr;
	var	$mb_password;
	var	$mb_password2;

	// コンストラクタ
	function mm_regist1() {
		$this->active = true;
	}


	// DBに保存
	function save_db_kari() {

		$sql = "SELECT mb_seq_no FROM t_member WHERE mb_step <> 9 and mb_id=" . sql_char(strtolower($this->mb_id));
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$seq_no = $fetch->mb_seq_no;
			$sql = "update t_member set mb_id=%s,mb_password=%s,mb_mail_addr=%s where mb_seq_no=$seq_no";
		} else {
			$sql = "INSERT INTO t_member (mb_id,mb_password,mb_mail_addr,mb_regist_date,mb_reg_kari_date) values (%s,%s,%s,'now','now')";
		}
		$sql = sprintf($sql,
				sql_char($this->mb_id),
				sql_char($this->mb_password),
				sql_char($this->mb_mail_addr));
		db_exec($sql);

		if (!$seq_no)
			$seq_no = get_currval('t_member_mb_seq_no_seq');
		return $seq_no;

	}
}

?>