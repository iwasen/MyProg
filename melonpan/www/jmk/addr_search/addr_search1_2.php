<?php
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ɼԸ���/�����������
'******************************************************/

$title_text = "�ɼԸ���/�������";
$title_color = $TC_DOKUSHA;

$search_addr = replace_single($search_addr);

//'=== Main ===
$sql = "SELECT distinct(mail_addr) FROM T_MAILADDR WHERE mail_addr like '%" . $search_addr . "%' ORDER BY mail_addr";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
		header ("location: ../admin/err_msg.php?id=2108");
	exit;
} else {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mymelonpan(reader_id) {
  var win;
  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + reader_id, "info_project", "scrollbars=yes,resizable=yes,width=500,height=380");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='60%'>
		<tr>
			<td>��������̰����ڸ�����<?= $search_addr ?>��</td>
			<td align="right">
				<td align="right"><nobr><font size=-1>���������<span id="rec_count"></span>�� </font><input type="button" value=" ��� " onclick="history.back()"></nobr>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='60%'>
		<tr bgcolor="#ffcc99">
			<th>seq</th>
			<th>�᡼�륢�ɥ쥹</th>
		</tr>
<?php
	$line = 0; $seq = 1;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$reader_id = $fetch->reader_id;
		$mail_addr = $fetch->mail_addr;
		$regist_dt = $fetch->regist_dt;
		if ($regist_dt == "") { $regist_dt = "<br>"; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $seq ?></td>
			<td align="left" title="���ɥ��ޥ������ɽ�����ޤ�"><a href='addr_search2.php?mail_addr=<?= $mail_addr ?>'><?= htmlspecialchars($mail_addr) ?></a></td>
		</tr>
<?php
		$line++; $seq++;
	}
}
?>
	</table>
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
