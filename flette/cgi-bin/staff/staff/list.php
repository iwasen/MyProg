#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����åհ����ꥹ��ɽ��
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>

<? page_header('��Ͽ�Ѥߥ����åհ���', true, true) ?>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������åհ���</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>ID</th>
		<th>�᡼�륢�ɥ쥹</th>
		<th>̾��</th>
		<th>��°</th>
		<th>����</th>
		<th>���ơ�����</th>
	</tr>
<?
$sql = "SELECT st_staff_id,st_mail_addr,st_name1,st_name2,st_section,st_auth,st_status FROM m_staff ORDER BY st_staff_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->st_staff_id?></td>
		<td><a href="edit.php?staff_id=<?=$fetch->st_staff_id?>" title="�����åվ����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->st_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->st_name1 $fetch->st_name2")?></td>
		<td><?=htmlspecialchars($fetch->st_section)?></td>
		<td align="center"><?=decode_staff_auth($fetch->st_auth)?></td>
		<td align="center"><?=decode_staff_status($fetch->st_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>

</body>
</html>
