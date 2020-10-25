<?
//=== お友達紹介情報クラス ===
Class CShoukai {
	//属性項目
	var $nickname;
	var $tomodachi_adr;
	var $message;

	//入力エラーフラグ
	var $err_nickname;
	var $err_tomodachi_adr;
	var $err_message;
	var $err_message_len;

	//=== 初期化処理 ===
	function CShoukai() {
		if (isset($_SESSION['CShoukai']))
			$this = $_SESSION['CShoukai'];
	}

	//=== データをセッションに保存 ===
	function SaveData() {
		$_SESSION['CShoukai'] = $this;
	}

	//=== 入力エラーフラグクリア ===
	function ClearErrFlag() {
		$this->err_nickname = false;
		$this->err_tomodachi_adr = false;
		$this->err_message = false;
		$this->err_message_len = false;
	}

	//=== DBに保存 ===
	function StoreDB($member_id) {
		if ($member_id == '')
			return false;

		$shoukai_no = GetNextVal('SK_shoukai_no');
		$sql = "SELECT * FROM T_SHOUKAI WHERE SK_shoukai_no=$shoukai_no";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if (!$ds->EOF())
			return false;

		$sql = sprintf("INSERT INTO T_SHOUKAI (SK_shoukai_no,SK_member_id,SK_nickname,SK_message,SK_date) VALUES (%s,%s,%s,%s,%s)",
				SqlNum($shoukai_no),
				SqlStr($member_id),
				SqlStr($this->nickname),
				SqlStr($this->message),
				"SYSDATE");
		ExecuteSQL($sql);

		$sql = "SELECT * FROM T_HISHOUKAI WHERE HS_shoukai_no=$shoukai_no";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if (!$ds->EOF())
			return false;

		$arry = explode("\n", $this->tomodachi_adr);
		foreach ($arry as $i => $mail_adr) {
			$mail_adr = trim($mail_adr);
			if ($mail_adr != '') {
				$sql = sprintf("INSERT INTO T_HISHOUKAI (HS_shoukai_no,HS_seq_no,HS_mail_adr,HS_regist_flag) VALUES (%s,%s,%s,%s)",
						SqlNum($shoukai_no),
						SqlNum($i + 1),
						SqlStr($mail_adr),
						SqlStr('0'));
				ExecuteSQL($sql);
			}
		}

		return true;
	}

	//=== データ削除 ===
	function Delete() {
		unset($_SESSION['CShoukai']);
		session_unregister('CShoukai');
	}
}
?>