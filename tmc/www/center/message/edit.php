<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:Ϣ������ѹ�����
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

$sql = sprintf(
			"SELECT msg_start_date,msg_end_date,msg_to_staff_id,msg_title,msg_message" .
			" FROM t_message" .
			" WHERE msg_message_id=%s",
		sql_number($_GET['message_id']));
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	redirect("list.php");
$fetch = pg_fetch_object($result, 0);

if ($fetch->msg_start_date) {
	$start_date_check = '1';
	$start_date = sql_time($fetch->msg_start_date);
} else
	$start_date = time();
$start_date_y = date(Y, $start_date);
$start_date_m = date(m, $start_date);
$start_date_d = date(d, $start_date);
$start_date_h = date(H, $start_date);

if ($fetch->msg_end_date) {
	$end_date_check = '1';
	$end_date = sql_time($fetch->msg_end_date);
} else
	$end_date = time();
$end_date_y = date(Y, $end_date);
$end_date_m = date(m, $end_date);
$end_date_d = date(d, $end_date);
$end_date_h = date(H, $end_date);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onLoad_body() {
	var f = document.form1;
	show_hide("start_date", f.start_date_check.checked);
	show_hide("end_date", f.end_date_check.checked);
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}
function update_check(f) {
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
	return confirm("Ϣ�����򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("����Ϣ�����������ޤ���������Ǥ�����");
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
<body onload="onLoad_body()">

<? center_header('Ϣ�����񤭹��ߡ�Ϣ������ѹ�') ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��Ϣ���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="title" size=70 maxlength=100 <?=value($fetch->msg_title)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">Ϣ�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="message" cols=80 rows=5><?=htmlspecialchars($fetch->msg_message)?></textarea><br>
			<span class="note">��ʸ�������¤Ϥ���ޤ���HTML�����λ��Ѥ��ǽ��</span>
		</td>
	</tr>
	<tr>
		<td class="m1">���襹���å�</td>
		<td class="n1"><select name="to_staff_id"><? select_staff("- ���� -", $fetch->msg_to_staff_id) ?></select></td>
	</tr>
	<tr>
		<td class="m1">ɽ����������</td>
		<td class="n1"><input type="checkbox" name="start_date_check" <?=value_checked('1', $start_date_check)?> onclick="show_hide('start_date',checked)">���ꤹ��
			<span id="start_date">&nbsp;
				<select name="start_date_y"><?select_year(2001, '', $start_date_y)?></select>ǯ
				<select name="start_date_m"><?select_month('', $start_date_m)?></select>��
				<select name="start_date_d"><?select_day('', $start_date_d)?></select>��
				<select name="start_date_h"><?select_hour('', $start_date_h)?></select>��
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ����λ����</td>
		<td class="n1"><input type="checkbox" name="end_date_check" <?=value_checked('1', $end_date_check)?> onclick="show_hide('end_date',checked)">���ꤹ��
			<span id="end_date">&nbsp;
				<select name="end_date_y"><?select_year(2001, '', $end_date_y)?></select>ǯ
				<select name="end_date_m"><?select_month('', $end_date_m)?></select>��
				<select name="end_date_d"><?select_day('', $end_date_d)?></select>��
				<select name="end_date_h"><?select_hour('', $end_date_h)?></select>��
			</span>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="message_id" value="<?=$_GET['message_id']?>">
</form>
</div>

<? center_footer() ?>

</body>
</html>
