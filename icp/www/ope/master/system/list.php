<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�����ƥ����ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// �����ƥ�����������
$sql = "SELECT * FROM m_system ORDER BY sy_key";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net ���ڥ졼�����ڡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

<div align="center">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td>�������ƥ�����������</td>
		<td align="right">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
</form>

<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list">
	<tr>
<?
		sort_header(0, '����', '40%');
		sort_header(0, '��������', '60%');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="line<?=$i % 2?>">
		<td><a href="edit.php?key=<?=$fetch->sy_key?>"><?=htmlspecialchars($fetch->sy_caption)?></a></td>
		<td><?=htmlspecialchars($fetch->sy_value)?></td>
	</tr>
<?
}
?>
</table>
</div>

			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
