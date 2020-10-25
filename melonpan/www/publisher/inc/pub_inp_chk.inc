<?php
$print_msg = "";
$Chk_flg = TRUE;

if ($pub_chg1) {
	$mail_add_sub = $mail_add_main;
}

// 基本メールアドレスのチェック
if (strlen($mail_add_main) == 0)
{
	$print_msg = "基本メールアドレスが記入されていないようです。<br>\n";
} else {
	// 基本メールアドレスの長さチェック
	if (strlen($mail_add_main) > 50 || strlen($mail_add_sub) > 50)
	{
		$print_msg = $print_msg .  "メールアドレスは、半角５０文字以内で入力してください。<br>\n";
	} else {
		// 基本メールアドレスの値チェック
		if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_add_main)) )
		{
			$print_msg = $print_msg .  "基本メールアドレスが正しく入力されていないようです。<br>\n";
		} else {
			// ２つのアドレスが同じかチェック
			if  (strcmp($mail_add_main, $mail_add_sub) != 0)
			{
				$print_msg = $print_msg .  "入力された２つのメールアドレスが一致していないようです。ご確認ください。<br>\n";
			}

		}
	}
}
if ($print_msg == "") {
	// メールアドレス重複チェック
	$wk_flg = 0;
//	$conn = db_conxxxt();
	$sql = "SELECT * FROM M_PUBLISHER WHERE mail_add_main = '" . $mail_add_main . "'";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
/* **
	$stmt = OCIParse($conn, $sql);
	OCIDefinebyname($stmt, "PUBLISHER_ID", &$wk_pub_id);
	$ncols = OCIExecute($stmt, OCI_DEFAULT);
	while (OCIFetch($stmt)) {
*/
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$wk_pub_id = $fetch->publisher_id;
		if ($wk_pub_id != $publisher_id) {
			$wk_flg = 1;
		}
	}
//	db_logoff();
	if ($wk_flg == 1) {
		$print_msg = $print_msg . "記入されたメールアドレスはすでに登録されているようです。"
			     . "めろんぱんでは複数のメルマガを発行者管理管理ページから"
			     . "一括管理できます。<br>";
	}
}

// 「発行者氏名（漢字）」のチェック
$owner_nm_kanj = i18n_ja_jp_hantozen($owner_nm_kanj,"K");
if (strlen($owner_nm_kanj) == 0)
{
	$print_msg = $print_msg .  "発行者氏名（漢字）が記入されていないようです。<br>\n";
} else {
	// 「発行者氏名（漢字）」の長さチェック
	if (strlen($owner_nm_kanj) > 50)
	{
		$print_msg = $print_msg .  "発行者氏名（漢字）は、全角２５文字以内で入力してください。<br>\n";
	}
}
// 「発行者氏名（カナ）」のチェック
$owner_nm_kana = i18n_ja_jp_hantozen($owner_nm_kana,"K");
if (strlen($owner_nm_kana) == 0)
{
	$print_msg = $print_msg .  "発行者氏名（カナ）が記入されていないようです。<br>\n";
} else {
	// 「発行者氏名（カナ）」の長さチェック
	if (strlen($owner_nm_kana) > 50)
	{
		$print_msg = $print_msg .  "発行者氏名（カナ）は、全角２５文字以内で入力してください。<br>\n";
	}
}
// 「連絡先郵便番号」のチェック
if (strlen($owner_zip1) == 0 || strlen($owner_zip2) == 0)
{
	$print_msg = $print_msg .  "連絡先郵便番号が記入されていないようです。<br>\n";
} else {
	// 「連絡先郵便番号」のチェック
	if (strlen($owner_zip1) <> 3 || strlen($owner_zip2) <> 4)
	{
		$print_msg = $print_msg .  "連絡先郵便番号は、半角３桁―半角４桁で入力してください。<br>\n";
	} else {
		if ( !(ereg ("^[0-9]{3}$", $owner_zip1)) || !(ereg ("^[0-9]{4}$", $owner_zip2)) )
		{
			$print_msg = $print_msg .  "連絡先郵便番号が正しく入力されていないようです。<br>\n";
		}
	}
}
// 「連絡先住所」のチェック
if (strlen($owner_add) == 0)
{
	$print_msg = $print_msg .  "連絡先住所が記入されていないようです。<br>\n";
} else {
	// 「連絡先住所」のチェック
	if (strlen($owner_add) > 100)
	{
		$print_msg = $print_msg .  "連絡先住所は、全角５０文字以内で入力してください。<br>\n";
	}
}
// 「連絡先電話番号」のチェック
if (strlen($owner_tel) == 0)
{
	$print_msg = $print_msg .  "連絡先電話番号が記入されていないようです。<br>\n";
} else {
	// 「連絡先電話番号」のチェック
	if (strlen($owner_tel) > 15)
	{
		$print_msg = $print_msg .  "連絡先電話番号は、半角１５文字以内で入力してください。<br>\n";
	} elseif(strlen($owner_tel) < 5) {
		$print_msg = $print_msg .  "連絡先電話番号は、半角５文字以上で入力してください。<br>\n";
	} else {
		if ( !(ereg ("^[0-9()\-]+$", $owner_tel)) )
		{
			$print_msg = $print_msg .  "連絡先電話番号が正しく入力されていないようです。<br>\n";
		}
	}
}
// 「パスワード」のチェック
if (strlen($owner_pswd) == 0 && strlen($owner_pswd_new) == 0 )
{
	$print_msg = $print_msg .  "パスワードが入力されていないようです<br>\n";
} else {
	// 「パスワード」のチェック
	if (strlen($owner_pswd) < 4 || strlen($owner_pswd) > 8)
	{
		$print_msg = $print_msg .  "パスワードは、半角で４文字以上、８文字以内で入力してください。<br>\n";
	} else {
		if ( !(ereg ("^[0-9a-zA-Z]{4,}$", $owner_pswd)) )
		{
			$print_msg = $print_msg .  "パスワードが正しく入力されていないようです。<br>\n";
		}
	}
}
if ($print_msg != "")
{
	$Chk_flg = FALSE;
}

?>
