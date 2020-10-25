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

$del_adr = replace_single($del_adr);
$adr = split("\n",$del_adr);

$ok = "";
db_begin_trans();
$cnt = 0;
$err_adr = "";
while (list($key,$val) = each($adr)) {
	$val = strtolower(trim($val));
	if ($val == "") { continue; }
	$sql = "select mail_addr, reader_id from T_MAILADDR where mag_id = '$mag_id' and mail_addr = '$val'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) {
		$err_adr .= $val."<br>";
		continue;
	} else {
		$sql = "delete from T_MAILADDR where mag_id = '$mag_id' and mail_addr = '$val'";
		if (db_exec($sql)) {
			$cnt++;
			//T_DELREASONに解除理由追加
			if ($reader_id == 0) { $reader_id = ""; }
			$delete_id = update_squ_key ("T_DELREASON");
			$reason = "事務局による代理解除";
			$sql = "INSERT INTO T_DELREASON (delete_id, mag_id, reader_id, reason, modify_dt) VALUES ($delete_id, '$mag_id'," . sql_number($reader_id) . " , '$reason', 'now')";
			if (db_exec($sql)) {
				$ok = "ok";
			}
		}
	}
}
if ($ok == "") {
	db_rollback();
	$msg = "該当データがないか、処理中にエラーが発生したため処理できませんでした。";
} else {
	$msg = $cnt . "件削除しました。";
 db_commit_trans();
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

<?= $msg ?><br><br><br>

<?
if ($err_adr <> "") {
?>
<font size=-1>※下記のメールアドレスは読者登録されていないようです。</font><br>
<table border=1 cellspacing=0 cellpadding=0 width=300>
	<tr>
		<td><?= $err_adr ?></td>
	</tr>
</table><br>
<?
}
?>
<input type="button" value=" 戻る " onclick="location.href='dokusha_ikkatsu1.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
