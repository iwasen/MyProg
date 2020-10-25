<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��Ͽ�����ѹ��ꥹ��
'******************************************************/

$title_text = $TT_henkou_list;
$title_color = $TC_MASTER;

//��������
$before_7day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-7,date(Y)));
$before_14day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-14,date(Y)));
$before_21day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-21,date(Y)));
$before_28day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-28,date(Y)));

//'=== Main ====
if ($kind == "") {
	$where = " WHERE to_char(MM.modefy_dt, 'YYYY/MM/DD')=to_char(now(), 'YYYY/MM/DD') AND";
} elseif ($kind == "1") {
	$where = " WHERE MM.modefy_dt BETWEEN to_date($before_7day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "2") {
	$where = " WHERE MM.modefy_dt BETWEEN to_date($before_14day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "3") {
	$where = " WHERE MM.modefy_dt BETWEEN to_date($before_21day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "4") {
	$where = " WHERE MM.modefy_dt BETWEEN to_date($before_28day, 'YYYYMMDD') AND 'now' AND";
}

$order_by = order_by(1, 1, 'modefy_dt', 'mag_id', 'mag_nm', 'owner_nm_kana', 'mag_count');
$sub = "SELECT COUNT(*) as mag_count, mag_id as cnt_mag_id FROM T_MAILADDR GROUP BY mag_id";
$sql = "SELECT MM.mag_id, MM.mag_nm, MP.owner_nm_kanj, owner_nm_kana, to_char(MM.modefy_dt, 'YYYY/MM/DD') as modefy_dt, mag_count"
	. " FROM M_MAGAZINE MM, M_PUBLISHER MP, (" . $sub . ") as CT" . $where	. " MM.publisher_id=MP.publisher_id AND MM.mag_id=cnt_mag_id"
	. " " . $order_by;
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
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}

function OnClick_kind() {
  location.href = "henkou_list2.php?kind=" + document.form1.kind.value;
}

function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	  <tr>
	    <td>�����ޥ������ѹ�����������<font size=-1>ɽ����</font>
				<select onchange="OnClick_kind()" name="kind">
					<option value=""<? If ($kind == "") {print " selected";} ?>>�����Τ�</option>
					<option value="1"<? If ($kind == "1") {print " selected";} ?>>������</option>
					<option value="2"<? If ($kind == "2") {print " selected";} ?>>������</option>
					<option value="3"<? If ($kind == "3") {print " selected";} ?>>������</option>
					<option value="4"<? If ($kind == "4") {print " selected";} ?>>������</option>
				</select>
			</td>
			<td align="right">���������<span id="rec_count"></span>�� <input type="button" value=" ��� " onclick="location.href='index.php'"><br></td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
  <table border=1 cellspacing=0 cellpadding=0 width='80%'>
    <tr bgcolor ="#ffcc99">
<?
		sort_header(1, '��Ͽ�ѹ���');
		sort_header(2, '���ޥ�ID');
		sort_header(3, '���ޥ�̾');
		sort_header(4, 'ȯ�Լ�');
		sort_header(5, '�ۿ���');

?>
    </tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$mag_count = $fetch->mag_count;
	$modefy_dt = $fetch->modefy_dt;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $modefy_dt ?></td>
			<td align="center"><?= $mag_id ?></td>
			<td align="left"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td align="left"><?= htmlspecialchars($owner_nm_kanj) ?></td>
			<td align="right"><?= number_format($mag_count) ?></td>
		</tr>
<?php
	$line++;
}
?>
	</table><br>
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
