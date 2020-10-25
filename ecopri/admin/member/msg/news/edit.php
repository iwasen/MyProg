<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:News!!��Ͽ
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

set_global('member', '����ȥåץڡ�������', 'News!!', BACK_TOP);

if (!$next_action) {
	$sql = "SELECT * from t_news where ns_seq_no=$ns_no";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0)
		system_error('��å������ɣĤ�����', __FILE__);
	$fetch = pg_fetch_object($result, 0);
	$start_date = $fetch->ns_start_date;
	$end_date = $fetch->ns_end_date;
	$title = $fetch->ns_title;
	$msg = $fetch->ns_text;
} else {
	$start_date = "$start_y-$start_m-$start_d";
	$end_date = "$end_y-$end_m-$end_d";
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
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

function onSubmit_form1(action) {
	var f = document.form1;
	f.next_action.value = action;
	switch (action) {
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
	if (!check_date(f.start_y.value, f.start_m.value, f.start_d.value)) {
		alert("�������λ��꤬����������ޤ���");
		f.start_d.focus();
		return(false);
	}
	if (!check_date(f.end_y.value, f.end_m.value, f.end_d.value)) {
		alert("��λ���λ��꤬����������ޤ���");
		f.end_d.focus();
		return(false);
	}
	if (f.title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.title.focus();
		return false;
	}
	if (f.msg.value == "") {
		alert("��å����������Ϥ��Ƥ���������");
		f.msg.focus();
		return false;
	}
	if (f.msg.value.indexOf('"') != -1) {
		alert("���֥륯�����ơ������ϻȤ��ޤ���");
		f.msg.focus();
		return false;
	}
	if (confirm("News!!�򹹿����ޤ���������Ǥ�����")) {
		f.action = "update.php";
		f.submit();
	}
}
function delete_check(f) {
	if (confirm("News!!�������ޤ���������Ǥ�����")) {
		f.action = "update.php";
		f.submit();
	}
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>��News!!��������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_y"><? select_year(2002, '', get_datepart('Y', $start_date)) ?></select>��
			<select name="start_m"><? select_month('', get_datepart('M', $start_date)) ?></select>��
			<select name="start_d"><? select_day('', get_datepart('D', $start_date)) ?></select>����
			<select name="end_y"><? select_year(2002, '', get_datepart('Y', $end_date)) ?></select>��
			<select name="end_m"><? select_month('', get_datepart('M', $end_date)) ?></select>��
			<select name="end_d"><? select_day(' ', get_datepart('D', $end_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" class="kanji" name="title" size=40 maxlength=50 <?=value($title)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" rowspan=2>��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="msg" rows=9 cols=60><?=$msg?></textarea>
			<input type="button" onclick="disp_msg()" value="���Ҏ�����ɽ��">
		</td>
	</tr>
	<tr>
		<td class="n1">
			<table border=1 cellspacing=1 cellpadding=1 width=200 height=105>
				<tr>
					<span class="note">��ɽ�����᡼����</span>
					<td class="n3" valign="top" bgcolor="lightyellow"><?=$msg?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="ns_no" <?=value($ns_no)?>>
<input type="hidden" name="next_action">
<input type="button" value="��������" onclick="onSubmit_form1('update')">
<input type="button" value="�������" onclick="onSubmit_form1('delete')">
<!--
<input type="reset" value="����󥻥�">
-->
<input type="button" value="����롡" onclick="location.href='list.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
