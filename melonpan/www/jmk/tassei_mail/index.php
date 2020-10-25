<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ɼ�ã���᡼������
'******************************************************/

$title_text = $TT_tassei;
$title_color = $TC_OTHER;

function f_color($status) {
	if ($status == '0')
		echo "#999999";
	else
		echo "#000000";
}

$sql = "SELECT tm_seq_no,tm_subject,tm_num,tm_status FROM M_TASSEI_MAIL order by tm_num";
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
	<table border=0 cellspacing=0 cellpadding=0 width=750>
		<tr>
			<td>���ɼ�ã���᡼�����</td>
			<td align="right">
				<input type="button" value="������Ͽ" onclick="location.href='new.php'">
				<input type="button" value=" ��� " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width=750>
		<tr bgcolor="#ffcc99">
			<th align="center" width=100><nobr>ã���Ϳ�</nobr></th>
			<th align="center"><nobr>��̾</nobr></th>
			<th align="center" width=100><nobr>ͭ��/̵��</nobr></th>
		</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$tm_seq_no = $fetch->tm_seq_no;
	$tm_subject = $fetch->tm_subject;
	$tm_num = $fetch->tm_num;
	$tm_status = $fetch->tm_status;
	if ($tm_status == '1') {
		$status = "ͭ��";
	} else {
		$status = "̵��";
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href='edit.php?seq_no=<?= $tm_seq_no ?>' title="ã���᡼������������ޤ�"><?= number_format($tm_num) ?></a></td>
			<td align="left"><font color="<?=f_color($tm_status)?>"><?= htmlspecialchars($tm_subject) ?></font></td>
			<td align="center"><font color="<?=f_color($tm_status)?>"><?= $status ?></font></td>
		</tr>
<?
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
