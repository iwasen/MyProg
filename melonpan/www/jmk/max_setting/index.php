<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����ʸ�����������/���������ɲåݥ��������
'******************************************************/

$title_text = $TT_max_setting;
$title_color = $TC_DOKUSHA;

//'=== Main ===
$sql = "SELECT osusume_pt FROM M_OSUSUMEPT WHERE pt_id=1";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow > 0) {
	$fetch = pg_fetch_object($result, 0);
	$osusume_pt = $fetch->osusume_pt;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.choice_max.focus();
}
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1() {
  with (document.form1) {
		if (!num_chk(recom_max.value)) {
			alert("����ʸ����ͤ�Ⱦ�ѿ��ͤ����Ϥ��Ƥ���������");
			recom_max.focus();
			return false;
		}
		if (!num_chk(osusume_pt.value)) {
			alert("���������ɲåݥ���Ȥ�Ⱦ�ѿ��ͤ����Ϥ��Ƥ���������");
			osusume_pt.focus();
			return false;
		}
	}
  return confirm("��Ͽ���ޤ���������Ǥ�����");
}
function OnClick_cho() {
	var line = document.form1.line.value;
	var i;
	for (i = 0; i < line; i++) {
		document.form1("choice_num[" + i + "]").value = document.form1.choice_max.value;
	}
}
function OnClick_rec() {
	var line = document.form1.line.value;
	var i;
	for (i = 0; i < line; i++) {
		document.form1("recom_num[" + i + "]").value = document.form1.recom_max.value;
	}
}//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="max_setting1.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>�����ޥ��ʥӾ������</td>
		</tr>
	</table><br>
	<table border=1 cellspacing=0 cellpadding=1 width='40%'>
		<tr align="left">
			<td class="h9"><nobr>�ߤĤ�����°������</nobr></td>
			<td><input type="text" DIR=rtl name="choice_max" maxlength="4">
					<input type="button" name="choice" value=" ���� " onclick="OnClick_cho()">
			</td>
		</tr>
		<tr align="left">
			<td class="h9"><nobr>����ʸ��°������</nobr></td>
			<td><input type="text" DIR=rtl name="recom_max" maxlength="4">
					<input type="button" name="recom" value=" ���� " onclick="OnClick_rec()">
			</td>
		</tr>
		<tr>
			<td class="h9"><nobr>���������ɲåݥ��������</nobr><br><font size=-1>�ʢ������ٲû���</font></td>
			<td><input type="text" DIR=rtl name="osusume_pt" maxlength="4" value="<?= $osusume_pt ?>"></td>
		</tr>
	</table><br>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>ID</nobr></th>
			<th align="center"><nobr>��̾</nobr></th>
			<th align="center"><nobr>�ߤĤ������</nobr></th>
			<th align="center"><nobr>����ʸ���</nobr></th>
			<th align="center"><nobr>����������</nobr></th>
		</tr>
<?php
$line = 0;
$sql = "SELECT * FROM M_MELONPAI ORDER BY melonpai_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$melonpai_id = $fetch->melonpai_id;
	$melonpai_name = $fetch->melonpai_name;
	$choice_num = $fetch->choice_num;
	$recom_num = $fetch->recom_num;
	$osusume_num = $fetch->osusume_num;
	if ($cho != "") {
		$choice_num = $cho;
	}
	if ($rec != "") {
		$recom_num = $rec;
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="melonpai_id[]" value="<?= $melonpai_id ?>">
			<td align="center"><?= $melonpai_id ?></td>
			<td><?= htmlspecialchars($melonpai_name) ?></td>
			<td width="15%" align="center"><input type="text" DIR=rtl size="10" maxlength="4" name="choice_num[<?= $line ?>]" value="<?= $choice_num ?>"></td>
			<td width="15%" align="center"><input type="text" DIR=rtl size="10" maxlength="4" name="recom_num[<?= $line ?>]" value="<?= $recom_num ?>"></td>
			<td width="15%" align="center"><input type="text" DIR=rtl  size="10" maxlength="4" name="osusume_num[]" value="<?= $osusume_num ?>"></td>
		</tr>
<?php
$line++;
}
?>
	</table><br>
	<input type="hidden" name="line" value=<?= $line ?>>
	<input type="submit" value=" ��Ͽ ">
  <input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
