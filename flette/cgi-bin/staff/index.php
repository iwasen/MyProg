#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����åեȥåץڡ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

if ($s_staff_auth == 1)
	$title = '�����ԥ����åեڡ���';
else
	$title = 'Ź�ޥ����åեڡ���';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>

<? page_header($title, false, true) ?>

<?
if ($s_staff_auth == 1) {
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="right">
			<input type="button" value="Ź�޿�����Ͽ" onclick="location.href='shop/new.php'">
			<input type="button" value="�����åմ���" onclick="location.href='staff/list.php'">
			<input type="button" value="�����᡼��ƥ�ץ졼�ȴ���" onclick="location.href='mail_temp/list.php'">
		</td>
	</tr>
</table>
<?
}
?>
��Ź�ް���
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>ID</th>
		<th>Ź��̾</th>
		<th>����</th>
<?
if ($s_staff_auth == 1) {
?>
		<th>Ź�޾�����</th>
		<th>����������</th>
<?
}
?>
		<th>ͽ���������ɽ��</th>
	</tr>
<?
$sql = "SELECT sh_shop_id,sh_shop_name,sh_shop_address FROM m_shop ORDER BY sh_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$shop_id = $fetch->sh_shop_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$shop_id?></td>
		<td><?=htmlspecialchars($fetch->sh_shop_name)?></td>
		<td><?=htmlspecialchars($fetch->sh_shop_address)?></td>
<?
if ($s_staff_auth == 1) {
?>

		<td align="center"><input type="button" value="Ź�޽���" onclick="location.href='shop/edit.php?shop_id=<?=$shop_id?>'"></td>
		<td align="center"><input type="button" value="��������" onclick="location.href='room/list.php?shop_id=<?=$shop_id?>'"></td>
<?
}
?>
		<td align="center"><input type="button" value="ͽ������" onclick="location.href='reserve/monthly.php?shop_id=<?=$shop_id?>'"></td>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>

</body>
</html>
