<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:Eco�������󤫤�Τ��Τ餻��Ͽ�ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// ͭ��/̵�������
function select_tm_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}
//ɽ��check
function tm_checked($data) {
	if ($data) {
		if ($data == 't') {
			print "checked";
		}
	}
}
// �ᥤ�����
set_global('etc', '����¾����', 'Eco�������󤫤�Τ��Τ餻', BACK_TOP);
if (!$next_action) {
	$sql = "SELECT * FROM t_top_msg WHERE tm_seq_no=$tm_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		system_error('��å�����No.������', __FILE__);
	$fetch = pg_fetch_object($result, 0);
	$tm_status = $fetch->tm_status;
	$start_date = $fetch->tm_start_date;
	$end_date = $fetch->tm_end_date;
	$tm_title = $fetch->tm_title;
	$tm_message = $fetch->tm_message;
} else {
	$start_date = "$start_year-$start_month-$start_day";
	$end_date = "$end_year-$end_month-$end_day";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC_JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function disp_msg() {
	var f = document.form1;
	f.next_action.value = 'on';
	f.action = "edit.php";
	f.submit();
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
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}

function update_check(f) {
	if (f.tm_title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.tm_title.focus();
		return false;
	}
	if (!check_date(f.start_year.value, f.start_month.value, f.start_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		f.start_day.focus();
		return(false);
	}
	if (!check_date(f.end_year.value, f.end_month.value, f.end_day.value)) {
		alert("���դλ��꤬����������ޤ���");
		f.end_day.focus();
		return(false);
	}
	return confirm("Eco�������󤫤�Τ��Τ餻����򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("Eco�������󤫤�Τ��Τ餻�������ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��Eco�������󤫤�Τ��Τ餻��������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="tm_title" size=70 maxlength=100 <?=value($tm_title)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=2>��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="tm_message" cols=50 rows=8><?=htmlspecialchars($tm_message)?></textarea>
			<input type="button" onclick="disp_msg()" value="���Ҏ�����ɽ��">
		</td>
	</tr>
	<tr>
		<td class="n1">
			<table border=1 cellspacing=1 cellpadding=1 width=212 height=90>
				<tr>
					<span class="note">��ɽ�����᡼����</span>
					<td class="n3" valign="top" bgcolor="lightyellow"><?=$tm_message?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ������</td>
		<td class="n1">������
			<select name="start_year"><? select_year('2000',' ', get_datepart('Y', $start_date)) ?></select>��
			<select name="start_month"><? select_month(' ', get_datepart('M', $start_date)) ?></select>��
			<select name="start_day"><? select_day(' ', get_datepart('D', $start_date)) ?></select>��
			<br>��λ��
			<select name="end_year"><? select_year('2000',' ', get_datepart('Y', $end_date)) ?></select>��
			<select name="end_month"><? select_month(' ', get_datepart('M', $end_date)) ?></select>��
			<select name="end_day"><? select_day(' ', get_datepart('D', $end_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="tm_status"><? select_tm_status($tm_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="tm_no" <?=value($tm_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����롡" onclick="location.href='list.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
