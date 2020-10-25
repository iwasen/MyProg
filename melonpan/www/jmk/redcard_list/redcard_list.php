<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���Υ�åɥ����ɥꥹ��
'******************************************************/

$title_text = "���Υ�åɥ����ɥꥹ��";
$title_color = $TC_HAKKOUSHA;

$order_by = order_by(1, 0, 'mag_pub_stop_dt', 'mag_id', 'mag_nm');
$sql = "select MAG_ID,MAG_NM,MAG_PUB_STOP_FLG"
	. ", to_char(MAG_PUB_STOP_DT,'yyyy/mm/dd') as mag_pub_stop_dt"
	. ", PUBLISHER_ID,MAG_PUB_STATUS_FLG "
	. " FROM M_MAGAZINE "
	. " WHERE (MAG_PUB_STOP_FLG = '02' OR MAG_PUB_STATUS_FLG = '02') AND mag_status_flg<>'99' AND mag_pub_status_flg<>'99'"
	. $order_by;
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
function OnClick_sakujo(mag_id) {
	if (confirm("�Ѵ�������¹Ԥ��ޤ���������Ǥ�����")) {
		location.href = "redcard_list2.php?mag_id=" + mag_id;
	}
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
<form style="margin:0" method="post" name="form1">
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>�����Υ�åɥ����ɥꥹ��<font size=-1>���ʢ�����̾�ʎ̎׎����ޤ�Ω�Ƥ���/���ޥ�ID/���ޥ�̾�ˤ򥯥�å�����ȥ����ȤǤ��ޤ�����</font></td>
			<td align="right">���������<span id="rec_count"></span>�� <input type="button" value=" ��� " onclick="location.href='../menu.php'"><br>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='90%'>
		<tr bgcolor="#ffcc99">
			<th width="5%" align="center"><nobr>���ߤΎ̎׎�����</nobr></th>
<?php
sort_header(1, '�̎׎����ޤ�Ω�Ƥ���');
sort_header(2, '���ޥ�ID');
sort_header(3, '���ޥ�̾');
?>
			<td class="h1" width="10%" align="center"><nobr>�Ѵ�����</nobr></td>	
		</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
	$mag_pub_status_flg = $fetch->mag_pub_status_flg;
	$mag_pub_stop_dt = $fetch->mag_pub_stop_dt;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;

	if ($mag_pub_stop_flg == "01") {
		$stop_flg = "Y";
	}
	elseif ($mag_pub_stop_flg== "02") {
		$stop_flg = "R";
	}
	else {
		$stop_flg = "-";
	}
	if ($mag_pub_status_flg == "02") {
		$status_flg = "��ǧ����";
	}
	else {
		$status_flg = "";
	}
	if ($mag_pub_stop_dt == "") {
		$mag_pub_stop_dt = "-";
	}
?>
		<tr>
			<td align="center" class="tc<?= $line % 2; ?>"><?= $stop_flg ?><?= $status_flg ?></td>
			<td align="center" class="tc<?= $line % 2; ?>"><?= $mag_pub_stop_dt ?></td>
			<td align="center" class="tc<?= $line % 2; ?>"><?= $mag_id ?></td>
			<td class="tc<?= $line % 2; ?>" title = "���ޥ��ξܺ٤�ɽ�����ޤ�"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td align="center" class="tc<?= $line % 2; ?>" title="�Ѵ�������¹Ԥ��ޤ�"><input type="button" name="submit1" value="�Ѵ�" onclick="OnClick_sakujo('<?= $mag_id ?>')"></td>
		</tr>
<?php
$line++;
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
