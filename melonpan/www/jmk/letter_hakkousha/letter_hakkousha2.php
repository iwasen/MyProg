<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者宛てメール
'******************************************************/
$title_text = $TT_letter_hakkousha;
$title_color = $TC_HAKKOUSHA;

//埋め込み変数名セット
$variable[1] = "@hakkousha@";
$variable[2] = "@publisher_id@";
$variable[3] = "@point@";

//全発行者選択
if ($chk == "all") {
	$where = " publisher_id<>0";
//個別選択
} elseif ($chk == "choice") {
	$pub_nm = explode("\n", trim($mailto));
	$pub_id_list = "";
	for ($i = 0; $i < count($pub_nm); $i++) {
		$pub_id = explode("(", $pub_nm[$i]);
		$pub_id[$i] = $pub_id[0];
		$pub_id_list .= $pub_id[$i] . ",";
	}
	$pub_id_list = substr($pub_id_list, 0, strlen($pub_id_list) - 1);

	$pub_id = explode(",", trim($pub_id_list));
	for ($i = 0; $i < count($pub_id); $i++) {
		if ($pub_id[$i] == ""){
			$msg = "宛先が正しく入力されていないようです。ご確認ください。";
			$err_flg = "1";
			break;
		}
	}
	$where = " publisher_id In (" . $pub_id_list . ")";
} else {
	$msg = "宛先が正しく入力されていないようです。ご確認ください。";
	$err_flg = "1";
}
//メールアドレス他取得
if ($err_flg != '1') {
	$sql = "SELECT publisher_id, mail_add_main, owner_nm_kanj, pt_melonpan FROM M_PUBLISHER WHERE" . $where . " ORDER BY mail_add_main";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$i = 1; $old_add_main = "";
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$new_add_main = $fetch->mail_add_main;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$publisher_id = $fetch->publisher_id;
		$pt_melonpan = $fetch->pt_melonpan;
		if ($new_add_main != $old_add_main) {
			$mail_add_main[$i] = $new_add_main;
			$embed[1][$i] = $owner_nm_kanj;
			$embed[2][$i] = $publisher_id;
			$embed[3][$i] = $pt_melonpan;
		}
		$old_add_main = $new_add_main;
	}
	$send_count = $i;
	//T_SEND_MAIl_DATAデータセット
	$sql = "INSERT INTO T_SEND_MAIl_DATA (SD_subject, SD_from, SD_body, SD_status)"
		. " VALUES ('$subject', '$mailfrom', '$body', '0')";
	if (db_exec($sql)) {
		$sql = "select currval('t_send_mail_d_sd_send_mail__seq') as send_mail_id_seq";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$send_mail_id_seq = $fetch->send_mail_id_seq;
		}
	}
	//T_SEND_MAIL_LISTデータセット
	for ($i = 0; $i < count($mail_add_main); $i++) {
		$sql = "INSERT INTO T_SEND_MAIL_LIST (SL_send_mail_id, SL_to, SL_sub_seq) VALUES ($send_mail_id_seq, '$mail_add_main[$i]', $i + 1)";
		db_exec($sql);
	}
	$sub_seq_cnt = $i - 1;

	//T_SEND_MAIL_EMBEDデータセット
	for ($i = 0; $i <= $sub_seq_cnt; $i++) {
		for ($n = 1; $n <= count($variable); $n++) {
			$SE_embed_text = $embed[$n][$i];
			$sql = "INSERT INTO T_SEND_MAIL_EMBED (SE_send_mail_id, SE_sub_seq, SE_variable_name, SE_embed_text)"
				. " VALUES ($send_mail_id_seq, $i + 1, '$variable[$n]', '$SE_embed_text')";
			db_exec($sql);
		}
	}

	//メール送信
	$sql = "UPDATE T_SEND_MAIL_DATA SET SD_status='1' WHERE SD_send_mail_id=$send_mail_id_seq";
	db_exec($sql);
	
	$msg = $send_count . "通、送信しました。";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<form method="post" name="form1" action="hon_touroku3.php">
<center>

<?= $msg ?><br><br>

<?
if ($err_flg == '1') {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='letter_hakkousha.php'">
<?
}
?>
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
