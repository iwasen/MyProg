<?
//=== 仮登録情報クラス ===
Class CRegInfo {
	//属性項目
	var $seq_no;
	var $mail_adr;
	var $mail_adr2;
	var $password;
	var $password2;
	var $seibetsu;
	var $mikikon;
	var $sei_nen;
	var $sei_getsu;
	var $sei_hi;
	var $zip_kyojuu1;
	var $zip_kyojuu2;
	var $zip_kinmu1;
	var $zip_kinmu2;
	var $shokugyou;
	var $html_mail;
	var $kyoumi_genre;
	var $yokuiku_mise;
	var $docchiha;
	var $oshirase_mail;
	var $shoukai_no;
	var $pr_id;
	var $regist_type;

	//入力エラーフラグ
	var $err_mail_adr;
	var $err_password;
	var $err_seibetsu;
	var $err_mikikon;
	var $err_seinengappi;
	var $err_zip_kyojuu;
	var $err_zip_kinmu;
	var $err_shokugyou;
	var $err_html_mail;
	var $err_kyoumi_genre;
	var $err_yokuiku_mise;
	var $err_docchiha;
	var $err_oshirase_mail;
	var $err_already_regist;

	//=== 初期化処理 ===
	function CRegInfo() {
		if (isset($_SESSION['CRegInfo'])) {
			$this = $_SESSION['CRegInfo'];
		}else {
			$this->kyoumi_genre = str_repeat('0', 100);
			$this->yokuiku_mise = str_repeat('0', 100);
			$this->docchiha = str_repeat('0', 20);
			$this->sei_getsu = '1';
			$this->sei_hi = '1';
		}
	}

	//=== データをセッションに保存 ===
	function SaveData() {
		$_SESSION['CRegInfo'] = $this;
	}

	//=== 入力エラーフラグクリア ===
	function ClearErrFlag() {
		$this->err_mail_adr = false;
		$this->err_password = false;
		$this->err_seibetsu = false;
		$this->err_mikikon = false;
		$this->err_seinengappi = false;
		$this->err_zip_kyojuu = false;
		$this->err_zip_kinmu = false;
		$this->err_shokugyou = false;
		$this->err_html_mail = false;
		$this->err_kyoumi_genre = false;
		$this->err_yokuiku_mise = false;
		$this->err_docchiha = false;
		$this->err_oshirase_mail = false;
		$this->err_already_regist = false;
	}

	//=== DBに保存 ===
	function StoreDB() {
		if ($this->mail_adr == '')
			return;

		$sql = "SELECT KT_seq_no FROM T_KARI_TOUROKU WHERE KT_mail_adr='$this->mail_adr'";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if ($ds->EOF()) {
			$this->seq_no = GetNextVal('KT_seq_no');
			$sql = "INSERT INTO T_KARI_TOUROKU (KT_seq_no,KT_mail_adr,KT_password,KT_seibetsu,KT_mikikon,KT_seinengappi,KT_zip_kyojuu,KT_zip_kinmu,KT_shokugyou,KT_html_mail,KT_kyoumi_genre,KT_yokuiku_mise,KT_docchiha,KT_oshirase_mail,KT_shoukai_no,KT_pr_id,KT_regist_date,KT_status,KT_regist_type,KT_reminder_flag) VALUES ($this->seq_no,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$this->seq_no = $ds->GetValue('KT_seq_no');
			$sql = "UPDATE T_KARI_TOUROKU SET KT_mail_adr=%s,KT_password=%s,KT_seibetsu=%s,KT_mikikon=%s,KT_seinengappi=%s,KT_zip_kyojuu=%s,KT_zip_kinmu=%s,KT_shokugyou=%s,KT_html_mail=%s,KT_kyoumi_genre=%s,KT_yokuiku_mise=%s,KT_docchiha=%s,KT_oshirase_mail=%s,KT_shoukai_no=%s,KT_pr_id=%s,KT_regist_date=%s,KT_status=%s,KT_regist_type=%s,KT_reminder_flag=%s WHERE KT_mail_adr='$this->mail_adr'";
		}

		$sql = sprintf($sql,
				SqlStr($this->mail_adr),
				SqlStr($this->password),
				SqlStr($this->seibetsu),
				SqlStr($this->mikikon),
				SqlDate("$this->sei_nen/$this->sei_getsu/$this->sei_hi"),
				SqlStr($this->zip_kyojuu1 . $this->zip_kyojuu2),
				SqlStr($this->zip_kinmu1 . $this->zip_kinmu2),
				SqlStr($this->shokugyou),
				SqlStr($this->html_mail),
				SqlStr($this->kyoumi_genre),
				SqlStr($this->yokuiku_mise),
				SqlStr($this->docchiha),
				SqlStr($this->oshirase_mail),
				SqlNum($this->shoukai_no),
				SqlStr($this->pr_id),
				"SYSDATE",
				SqlStr('0'),
				SqlStr($this->regist_type),
				SqlStr('0'));
		ExecuteSQL($sql);
	}
}
?>