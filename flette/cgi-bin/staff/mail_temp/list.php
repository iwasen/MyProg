#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����᡼��ƥ�ץ졼�Ȱ����ꥹ��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$sql = "SELECT mt_template_id,mt_guide,mt_from_name,mt_from_addr,mt_to_addr,mt_subject FROM m_mail_template ORDER BY mt_template_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>

<? page_header('�����᡼��ƥ�ץ졼�Ȱ���', true, true) ?>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������᡼��ƥ�ץ졼�Ȱ���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>�ƥ�ץ졼��̾</th>
		<th>��̾</th>
		<th>���п�</th>
		<th>������</th>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?template_id=<?=$fetch->mt_template_id?>" title="�᡼��ƥ�ץ졼�Ȥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->mt_guide)?></a></td>
		<td><?=htmlspecialchars($fetch->mt_subject)?></td>
		<td><?=htmlspecialchars("$fetch->mt_from_name <$fetch->mt_from_addr>")?></td>
		<td><?=htmlspecialchars($fetch->mt_to_addr)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>

</body>
</html>
