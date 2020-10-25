<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

//=== メール登録 ===
function regist_adr($stmt) {
	global $mail_id, $mag_id, $mail_adr, $reader_id;

	$sql = "SELECT DISTINCT CT.CT_mail_adr,COALESCE(MM.reader_id, 0) AS reader_id"
		. " FROM (T_CLEANING_TEMP AS CT LEFT JOIN M_MELONPA AS MM ON CT.CT_mail_adr=MM.mail_addr) LEFT JOIN T_MAILADDR AS TM ON TM.mag_id='$mag_id'"
		. " AND CT.CT_mail_adr=TM.mail_addr"
		. " WHERE TM.mail_addr IS NULL";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	$count = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mail_adr = $fetch->ct_mail_adr;
		$reader_id = $fetch->reader_id;

		if (($new_mail_id = update_squ_key("T_MAILADDR")) != "-1") {
			$mail_id = $new_mail_id;
			$sql1 = "INSERT INTO T_MAILADDR (mail_id, mag_id, reader_id, mail_addr, reg_flg, modify_dt)"
				. " values ($mail_id, '$mag_id', $reader_id, '$mail_adr', '0', 'now')";
			db_exec($sql1);
		} else {
			return -1;
		}
		$count++;
	}
	return $count;
}

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

$bakyun_id = "000013";
$letter_id = "000049";

$mag_id = sprintf("%06d", $mag_id);
$modify_dt = date("Y-m-d H:i:s");

//=== Main ===
$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mag_id' AND mag_status_flg='00' AND mag_pub_status_flg<>'02' AND mag_pub_status_flg<>'99' AND mag_pub_stop_flg<>'02'";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = "メルマガID：「" . $mag_id . "」は、現在発行されていないようです。";
	$err_flag = "1";
} else {
	$sql = "DELETE from T_CLEANING_TEMP";
	db_exec($sql);

	$fd = fopen ($importfile, "r");
	while (!feof($fd)) {
		$mail_adr = strtolower(trim(fgets($fd, 100)));
		if ($mail_adr <> "") {
			$sql = "INSERT INTO T_CLEANING_TEMP VALUES ('$mail_adr')";
			db_exec($sql);
		}
	}
	fclose ($fd);

	db_begin_trans();

	$err = false;
	if (($count = regist_adr($stmt)) != -1)
		$msg = "指定メルマガ：$count 件";
	else
		$err = true;

	if (!$err && $bakyun == "1") {
		$mag_id = $bakyun_id;
		if (($count = regist_adr($stmt)) != -1)
			$msg .= "，バ・キューン：$count 件";
		else
			$err = true;
	}

	if (!$err && $letter == "1") {
		$mag_id = $letter_id;
		if (($count3 = regist_adr($stmt)) != -1)
			$msg .= "，めろんぱんレター：$count3 件";
		else
			$err = true;
	}

	if (!$err) {
		db_commit_trans();
		$msg .= "をインポートしました。";
	} else {
		db_rollback();
		$msg = "登録中にエラーが発生しました。";
	}
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
<center>
<form method="post" name="form1">

<?= $msg ?><br><br>

<? if ($err_flag == "1") { ?>
<input type="button" value=" 戻る " onclick="history.back()">
<? } else { ?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<? } ?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
