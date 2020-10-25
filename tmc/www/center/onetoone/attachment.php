<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・添付ファイル登録
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body>

<? center_header('OneToOneメール｜添付ファイル追加') ?>

<div align="center">
<form method="post" name="form1" action="<?=$top?>/common/add_attach.php" enctype="multipart/form-data">
<table border=0 cellspacing=2 cellpadding=3>
	<tr>
		<td class="m0">■添付ファイル名を入力してください。</td>
	</tr>
<?
for ($i = 0; $i < 5; $i++) {
?>
	<tr>
		<td><input type="file" name="attachment<?=$i?>" size=70></td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="window.close()">
<input type="hidden" name="comm_id" <?=value($_GET['comm_id'])?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
