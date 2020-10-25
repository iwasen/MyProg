<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者一括登録/削除
'******************************************************/

$title_text = $TT_dokusha_ikkatsu;
$title_color = $TC_DOKUSHA;

//=== Main ===
$add_adr = replace_single($add_adr);
$adr = split("\n",$add_adr);

$cnt = 0;
$error_mail_husei = "";
$error_mail_sonzai = "";

while (list($key,$val) = each($adr)) {
	$reader_id = "";
	$val = strtolower(trim($val));
	if ($val == "") { continue; }
	if (!addr_check2($val)) {
		$error_mail_husei .= $val."<br>";
	} else {
		$sql = "select mail_addr from T_MAILADDR where mail_addr = '$val' and mag_id = '$mag_id'";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$error_mail_sonzai .= $val."<br>";
		} else {
			$sql = "select reader_id from M_MELONPA where mail_addr = '$val'";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$reader_id = $fetch->reader_id;
			}
			if (($mail_id = update_squ_key ("T_MAILADDR")) != "-1") {
				if (!$reader_id) { $reader_id = 0; }
				$sql = "insert into T_MAILADDR (mail_id,mag_id,reader_id,mail_addr,modify_dt,reg_flg) values ($mail_id,'$mag_id',$reader_id,'$val','now',0)";
				db_exec($sql);
				$cnt++;
			}
		}
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

<form method="post" name="form1" action="hon_touroku3.php">
<center>
<?= $cnt ?>件追加登録しました。<br><br><br>

<?
if ($error_mail_sonzai <> "") {
$err_flg = "1";
?>
<font size=-1>※下記のメールアドレスは既に存在しています。</font><br>
<table border=1 cellspacing=0 cellpadding=0 width=300>
	<tr>
		<td><?= $error_mail_sonzai ?></td>
	</tr>
</table><br>
<?
}
?>
<?
if ($error_mail_husei <> "") {
$err_flg = "1";
?>
<font size=-1>※下記のメールアドレスは正しくないようです。</font><br>
<table border=1 cellspacing=0 cellpadding=0 width=300>
	<tr>
		<td><?= $error_mail_husei ?></td>
	</tr>
</table><br>
<?
}
?>
<?
if ($err_flg == "1") {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='dokusha_ikkatsu1.php'">
<?
}
?>
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
