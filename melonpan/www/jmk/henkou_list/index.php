<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��Ͽ�����ѹ��ꥹ��
'******************************************************/

$title_text = $TT_henkou_list;
$title_color = $TC_MASTER;

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

<center><br>
	<table border=0 cellspacing=0 cellpadding=0 width='40%'>
		<tr>
			<td>����Ͽ�����ѹ��ꥹ��</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=5 width='40%'>
	<form method="post" name="form1">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>ȯ�ԼԸĿ;���</nobr></td>
			<td align="center" class="h2"><input type="button" value=" ���� "onclick="location.href='henkou_list1.php'"></td>
		</tr>
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>���ޥ�����</nobr></td>
			<td align="center" class="h2"><input type="button" value=" ���� " onclick="location.href='henkou_list2.php'"></td>
		</tr>
		<tr>
			<td align="center" class="m6s">3
			</td>
			<td class="m6s"><nobr>My����Ѥ�Ŀ;���</nobr></td>
			<td align="center" class="h2"><input type="button" value=" ���� " onclick="location.href='henkou_list3.php'"></td>
		</tr>
	</form>
	</table><br>
	<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</center>

<? footer_jmk(0); ?>

</body>
</html>
