<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:Ϣ����࿷����Ͽ����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/select.php");

// ����������
$today = getdate();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (f.message.value == "") {
		alert("Ϣ���������Ϥ��Ƥ���������");
		f.message.focus();
		return false;
	}
	return confirm("Ϣ��������Ͽ���ޤ���������Ǥ�����");
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</script>
</head>
<body onload="document.form1.title.focus()">

<? center_header('Ϣ�����񤭹��ߡÿ�����Ͽ') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��Ϣ���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size=70 maxlength=100>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ϣ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="message" cols=80 rows=5></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ���HTML�����λ��Ѥ��ǽ��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���襹���å�</td>
		<td class="n1"><select name="to_staff_id"><?select_staff('- ���� -')?></select></td>
	</tr>
	<tr>
		<td class="m1">ɽ����������</td>
		<td class="n1"><input type="checkbox" name="start_date_check" value="1" onclick="show_hide('start_date',checked)">���ꤹ��
			<span id="start_date" style="display:none">&nbsp;
				<select name="start_date_y"><?select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="start_date_m"><?select_month('', $today['mon'])?></select>��
				<select name="start_date_d"><?select_day('', $today['mday'])?></select>��
				<select name="start_date_h"><?select_hour()?></select>��
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ����λ����</td>
		<td class="n1"><input type="checkbox" name="end_date_check" value="1" onclick="show_hide('end_date',checked)">���ꤹ��
			<span id="end_date" style="display:none">&nbsp;
				<select name="end_date_y"><?select_year($today['year'], '', $today['year'])?></select>ǯ
				<select name="end_date_m"><?select_month('', $today['mon'])?></select>��
				<select name="end_date_d"><?select_day('', $today['mday'])?></select>��
				<select name="end_date_h"><?select_hour()?></select>��
			</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? center_footer() ?>

</body>
</html>
