<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�䤤��碌�б���ź�եե�������Ͽ
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

<? center_header('�䤤��碌�б���ź�եե������ɲ�') ?>

<div align="center">
<form method="post" name="form1" action="<?=$top?>/common/add_attach.php" enctype="multipart/form-data">
<table border=0 cellspacing=2 cellpadding=3>
	<tr>
		<td class="m0">��ź�եե�����̾�����Ϥ��Ƥ���������</td>
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
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="window.close()">
<input type="hidden" name="comm_id" <?=value($_GET['comm_id'])?>>
</form>
</div>

<? center_footer() ?>

</body>
</html>
