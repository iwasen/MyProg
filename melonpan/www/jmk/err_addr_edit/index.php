<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�������ɥ쥹��������
'******************************************************/

$title_text = "�������ɥ쥹����";
$title_color = $TC_MASTER;

//'=== Main ====
switch ($kind) {
case "":
	$where = " WHERE err_count=4 AND";
	break;
case "3":
	$where = " WHERE err_count=3 AND";
	break;
case "2":
	$where = " WHERE err_count=2 AND";
	break;
case "1":
	$where = " WHERE err_count=1 AND";
	break;
case "9":
	$where = " WHERE err_count >0 AND";
	break;
}
$sub = "SELECT COUNT(*) as mag_count, mail_addr as cnt_mail_addr FROM T_MAILADDR GROUP BY mail_addr";
$sql = "SELECT mail_id, mag_id, reader_id, mail_addr, to_char(modify_dt, 'YYYY/MM/DD') as modify_dt, mag_count, err_count"
	. " FROM T_MAILADDR, (" . $sub . ") as MC" . $where	. " mail_addr=cnt_mail_addr ORDER BY mail_addr";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_kind() {
  location.href = "index.php?kind=" + document.form1.kind.value;
}
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_mymelonpan(reader_id) {
  var win;
  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + reader_id, "info_project", "resizable=yes,width=500,height=380");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="err_addr_edit1.php">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	  <tr>
	    <td>���������ɥ쥹����������<font size=-1>��ã�����</font>
				<select onchange="OnClick_kind()" name="kind">
					<option value=""<? If ($kind == "") {print " selected";} ?>>����</option>
					<option value="3"<? If ($kind == "3") {print " selected";} ?>>����</option>
					<option value="2"<? If ($kind == "2") {print " selected";} ?>>����</option>
					<option value="1"<? If ($kind == "1") {print " selected";} ?>>����</option>
					<option value="9"<? If ($kind == "9") {print " selected";} ?>>����</option>
				</select>
			</td>
			<td align="right">
				<input type="button" value=" ��� " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
  <table border=1 cellspacing=0 cellpadding=0 width='80%'>
    <tr bgcolor ="#ffcc99">
      <th class="h1" align="center">��ã<br>���</th>
      <th class="h1" align="center"><nobr>���ޥ�ID</nobr></th>
      <th class="h1" align="center"><nobr>������Ͽ��</nobr></th>
      <th class="h1" align="center"><nobr>�᡼�륢�ɥ쥹</nobr></th>
      <th class="h1" align="center"><nobr>���ɻ��</nobr></th>
      <th class="h1" align="center">My<br>����Ѥ�</th>
      <th class="h1" align="center"><nobr>���</nobr></th>
    </tr>
<?php
for ($j = 0; $j < $nrow; $j++) {
	$fetch = pg_fetch_object($result, $j);
	$err_count = $fetch->err_count;
	$mail_id = $fetch->mail_id;
	$mag_id = $fetch->mag_id;
	$modify_dt = $fetch->modify_dt;
	$mail_addr = $fetch->mail_addr;
	$reader_id = $fetch->reader_id;
	$mag_count = $fetch->mag_count;
	if (!addr_check2(trim($mail_addr))) {
		if ($reader_id <= 0) {
			$melonpa_info = "<br>";
		} else {
			$melonpa_info = "ɽ��";
		}
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="mail_id[]" value="<?= $mail_id ?>">
			<input type="hidden" name="reader_id[]" value="<?= $reader_id ?>">
			<input type="hidden" name="old_mail_addr[]" value="<?= $mail_addr ?>">
			<input type="hidden" name="mag_id[]" value="<?= $mag_id ?>">
			<td align="center"><?= $err_count ?></td>
			<td align="center"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= $mag_id ?></a></td>
			<td align="center"><?= $modify_dt ?></td>
			<td><input type="text" size=50 name=new_mail_addr[] maxlength=50 value="<?= htmlspecialchars($mail_addr) ?>"></td>
			<td align="right"><a href='mag_list.php?mail_addr=<?= $mail_addr ?>'><?= number_format($mag_count) ?></a></td>
			<td align="center"><a href='JavaScript:OnClick_mymelonpan("<?= $reader_id ?>")'><?= $melonpa_info ?></a></td>
			<td align="center"><input type="checkbox" name="delete_mag[<?= $i ?>]" value="<?= $mail_id ?>"></td>
<!--			<td align="right"><?= $mail_id ?></td> -->

		</tr>
<?php
	$line++; $i++;
	}
}
?>
	</table><br>
<input type="hidden" name="kind_id" value="<?= $kind ?>">
<input type="submit" value=" ��ǧ ">
<input type="reset" value="�ꥻ�å�">
<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
