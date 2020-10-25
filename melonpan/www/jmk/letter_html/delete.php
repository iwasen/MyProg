<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_html;
$title_color = $TC_MASTER;

$sql = "SELECT * FROM T_PUB_RESERVE WHERE index_id=$index_id AND resrv_flg='0 '";
$result = db_exec($sql);
if (!pg_numrows($result)) {
	$msg = 'メールは既に送信されているため、削除できません。';
} else {
	db_begin_trans();

	$sql = "DELETE FROM T_BODY WHERE index_id=$index_id";
	db_exec($sql);

	$sql = "DELETE FROM T_PUB_RESERVE WHERE index_id=$index_id";
	db_exec($sql);

	$sql = "DELETE FROM t_html_letter WHERE hl_letter_id=$letter_id";
	db_exec($sql);

	db_commit_trans();

	$msg = "削除しました。";
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

<form method="post" name="form1">
<center>

<?= $msg ?><br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</center>
</form>

<? footer_jmk(0); ?>

</body>
</html>
