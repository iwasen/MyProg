<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:Welcome��å�������Ͽ
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

set_global('member', '����ȥåץڡ�������', 'Welcome��å�����', BACK_TOP);

$sql = "SELECT * from t_welcome_msg where wm_seq_no=$wm_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��å������ɣĤ�����', __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
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
	return confirm("Welcome��å������򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("Welcome��å������������ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>��Welcome��å�������������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">ɽ������<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="start_y"><? select_year(2002, '', get_datepart('Y', $fetch->wm_start_date)) ?></select>��
			<select name="start_m"><? select_month('', get_datepart('M', $fetch->wm_start_date)) ?></select>��
			<select name="start_d"><? select_day('', get_datepart('D', $fetch->wm_start_date)) ?></select>����
			<select name="end_y"><? select_year(2002, '', get_datepart('Y', $fetch->wm_start_date)) ?></select>��
			<select name="end_m"><? select_month('', get_datepart('M', $fetch->wm_start_date)) ?></select>��
			<select name="end_d"><? select_day('', get_datepart('D', $fetch->wm_end_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="text" class="kanji" name="msg" size=40 maxlength=50 <?=value($fetch->wm_text)?>>
			<span class="note">�����ѣ���ʸ�����١�</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="wm_no" <?=value($wm_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<!--
<input type="reset" value="����󥻥�">
-->
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
