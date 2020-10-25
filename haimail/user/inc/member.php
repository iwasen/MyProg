<?
//=== 会員情報クラス ===
Class CMember {
	//属性項目
	var $member_id;
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
	var $update_cnt;

	//入力エラーフラグ
	var $err_mail_adr;
	var $err_mail_adr2;
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

	//=== 初期化処理 ===
	function CMember() {
		if (isset($_SESSION['CMember']))
			$this = $_SESSION['CMember'];
		else {
			$this->kyoumi_genre = str_repeat('0', 100);
			$this->yokuiku_mise = str_repeat('0', 100);
			$this->docchiha = str_repeat('0', 20);
			$this->sei_getsu = '1';
			$this->sei_hi = '1';
		}
	}

	//=== データをセッションに保存 ===
	function SaveData() {
		$_SESSION['CMember'] = $this;
	}

	//=== 入力エラーフラグクリア
	function ClearErrFlag() {
		$this->err_mail_adr = false;
		$this->err_mail_adr2 = false;
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
	}

	//=== ログインチェック ===
	function Login($p_mail_adr, $p_password) {
		$sql = "SELECT * FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($p_mail_adr) . " AND MM_password=" . SqlStr($p_password) . " AND MM_status<>'9'";
		$ds = CreateDynaset($sql, ORADYN_READONLY);
		if ($ds->EOF())
			$login = false;
		else {
			$this->member_id = $ds->GetValue('MM_member_id');
			$this->mail_adr = $ds->GetValue('MM_mail_adr');
			$this->mail_adr2 = $ds->GetValue('MM_mail_adr');
			$this->password = $ds->GetValue('MM_password');
			$this->password2 = $ds->GetValue('MM_password');
			$this->seibetsu = $ds->GetValue('MM_seibetsu');
			$this->mikikon = $ds->GetValue('MM_mikikon');
			$this->sei_nen = get_datepart('Y', $ds->GetValue('MM_seinengappi'));
			$this->sei_getsu = get_datepart('M', $ds->GetValue('MM_seinengappi'));
			$this->sei_hi = get_datepart('D', $ds->GetValue('MM_seinengappi'));
			$this->zip_kyojuu1 = Zip1($ds->GetValue('MM_zip_kyojuu'));
			$this->zip_kyojuu2 = Zip2($ds->GetValue('MM_zip_kyojuu'));
			$this->zip_kinmu1 = Zip1($ds->GetValue('MM_zip_kinmu'));
			$this->zip_kinmu2 = Zip2($ds->GetValue('MM_zip_kinmu'));
			$this->shokugyou = $ds->GetValue('MM_shokugyou');
			$this->html_mail = $ds->GetValue('MM_html_mail');
			$this->kyoumi_genre = $ds->GetValue('MM_kyoumi_genre');
			$this->yokuiku_mise = $ds->GetValue('MM_yokuiku_mise');
			$this->docchiha = $ds->GetValue('MM_docchiha');
			$this->oshirase_mail = $ds->GetValue('MM_oshirase_mail');

			$login = true;
		}

		return $login;
	}

	//=== DBに保存 ===
	function StoreDB() {
		if ($this->member_id == '')
			return;

		$sql = "SELECT * FROM T_MEMBER WHERE MM_member_id='$this->member_id'";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if (!$ds->EOF()) {
			$sql = sprintf("UPDATE T_MEMBER SET MM_mail_adr=%s,MM_password=%s,MM_seibetsu=%s,MM_mikikon=%s,MM_seinengappi=%s,MM_zip_kyojuu=%s,MM_zip_kinmu=%s,MM_shokugyou=%s,MM_html_mail=%s,MM_kyoumi_genre=%s,MM_yokuiku_mise=%s,MM_docchiha=%s,MM_oshirase_mail=%s,MM_update_date=SYSDATE,MM_update_cnt=MM_update_cnt+1 WHERE MM_member_id='$this->member_id'",
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
					SqlStr($this->oshirase_mail));
			ExecuteSQL($sql);
		}

		$sql = "BEGIN S_CREATE_SEARCH_DATA('$this->member_id'); END;";
		ExecuteSQL($sql);
	}
}
?>