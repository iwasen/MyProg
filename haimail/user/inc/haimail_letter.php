<?
//=== はいめーるレター情報クラス ===
class CHaimailLetter {
	//属性項目
	var $mail_adr;
	var $name;
	var $company;
	var $section;
	var $message;
	var $mail_adr_del;

	//入力エラーフラグ
	var $err_mail_adr;
	var $err_name;
	var $err_company;
	var $err_section;
	var $err_message;
	var $err_mail_adr_del;
	var $err_unregist;

	//=== 初期化処理 ===
	function CHaimailLetter() {
		if (isset($_SESSION['CHaimailLetter']))
			$this = $_SESSION['CHaimailLetter'];
	}

	//=== データをセッションに保存 ===
	function SaveData() {
		$_SESSION['CHaimailLetter'] = $this;
	}

	//=== 入力エラーフラグクリア ===
	function ClearErrFlag() {
		$this->err_mail_adr = false;
		$this->err_name = false;
		$this->err_company = false;
		$this->err_section = false;
		$this->err_message = false;
		$this->err_mail_adr_del = false;
		$this->err_unregist = false;
	}

	//=== DBに保存 ===
	function StoreDB() {
		if ($this->mail_adr == '')
			return false;

		$sql = "SELECT * FROM T_HAIMAIL_LETTER WHERE HL_mail_adr='$this->mail_adr'";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if ($ds->EOF()) {
			$hml_id = MakeID(GetNextVal('HL_hml_id'), 10);
			$sql = "INSERT INTO T_HAIMAIL_LETTER (HL_hml_id,HL_mail_adr,HL_date) VALUES ('$hml_id',%s,%s)";
		} else
			$sql = "UPDATE T_HAIMAIL_LETTER SET HL_mail_adr=%s,HL_date=%s WHERE HL_mail_adr='$this->mail_adr'";

		$sql = sprintf($sql,
				SqlStr($this->mail_adr),
				"SYSDATE");
		ExecuteSQL($sql);

		return true;
	}

	//=== DBから削除 ===
	function DeleteMailAddr() {
		if ($this->mail_adr_del == '')
			return false;

		$sql = "DELETE FROM T_HAIMAIL_LETTER WHERE HL_mail_adr='$this->mail_adr_del'";
		ExecuteSQL($sql);

		return true;
	}

	//=== データ削除 ===
	function Delete() {
		unset($_SESSION['CHaimailLetter']);
		session_unregister('CHaimailLetter');
	}
}
?>