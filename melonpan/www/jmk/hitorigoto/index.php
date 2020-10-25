<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ֺ����ΤҤȤꤴ�ȡ�����
'******************************************************/

$title_text = $TT_hitorigoto;
$title_color = $TC_MASTER;

//'=== �Կ�ɽ�� ===
function DispLine($nline) {
	if ($nline == '0') {
		print "<option value='0' selected>����</option>";
	} else {
		print "<option value='0'>����</option>";
	}
	for ($i = 10; $i <= 100; $i = $i + 10) {
		if ($i == $nline) {
			print "<option value=" . $i ." selected>". $i . "��</option>\n";
		} else {
			print "<option value=" . $i .">". $i . "��</option>\n";
		}
	}
}

//'=== Main ===
if ($nline == "") {
	$nline = 30;
}
$today = date("Ymd");

$sql = "SELECT * FROM T_ADMIN_MSG WHERE msg_id='2'"
		. " ORDER BY access_day DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>���ֺ����ΤҤȤꤴ�ȡװ���
				<font size="-1">����ɽ����</font><select name="nline" onchange="submit()"><? DispLine($nline); ?></select></td>
			<td align="right">
				<input type="button" value="������Ͽ" onclick="location.href='hitorigoto1.php'">
				<input type="button" value=" ��� " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>ɽ����</nobr></th>
			<th align="center"><nobr>��å�����</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sqmsg_id = $fetch->sqmsg_id;
	$access_day = $fetch->access_day;
	$admin_msg = $fetch->admin_msg;
	$info_year = substr($access_day, 0, 4);
	$info_month = substr($access_day, 4, 2);
	$info_day = substr($access_day, 6, 2);

	if ($access_day >= $today) {
		$info_date = "<a href='hitorigoto2.php?sqmsg_id=" . $sqmsg_id . "'>" . $info_year . "/" . $info_month . "/" .$info_day . "</a>";
	} else {
		$info_date = $info_year . "/" . $info_month . "/" .$info_day;
	}
	if ($line >= $nline && $nline <> '0') {
		break;
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $info_date ?></td>
			<td><?= htmlspecialchars($admin_msg) ?></td>
		</tr>
<?php
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
