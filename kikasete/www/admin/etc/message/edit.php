<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��̳�ɤ���Τ��Τ餻��Ͽ�ѹ�����
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
function select_cs_status($sel) {
	echo "<option ", value_selected('0', $sel), ">ͭ��</option>";
	echo "<option ", value_selected('9', $sel), ">̵��</option>";
}
//ɽ��check
function cs_checked($data) {
	if ($data) {
		if ($data == 't') {
			print "checked";
		}
	}
}
// �ᥤ�����
set_global('etc', '����¾����', '��̳�ɤ���Τ��Τ餻', BACK_TOP);

$sql = "SELECT * FROM t_center_msg WHERE cs_seq_no=$cs_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('��å�����No.������', __FILE__);
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
	if (f.cs_title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.cs_title.focus();
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
	return confirm("��̳�ɤ���Τ��Τ餻����򹹿����ޤ���������Ǥ�����");
}
function delete_check(f) {
	return confirm("��̳�ɤ���Τ��Τ餻�������ޤ���������Ǥ�����");
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
		<td class="m0" colspan=2>����̳�ɤ���Τ��Τ餻��������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1">�����ȥ�<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cs_title" size=75 maxlength=100 <?=value($fetch->cs_title)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��å�����<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="cs_message" cols=70 rows=15><?=htmlspecialchars($fetch->cs_message)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ���ڡ���<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="checkbox" name="cs_monitor_top" value='t' <? cs_checked($fetch->cs_monitor_top) ?>><font size=-1> ��˥����ȥåץڡ���</font><br>
			<input type="checkbox" name="cs_monitor_mypage" value='t' <? cs_checked($fetch->cs_monitor_mypage) ?>><font size=-1> ��˥���My�ڡ���</font><br>
			<input type="checkbox" name="cs_marketer_top" value='t' <? cs_checked($fetch->cs_marketer_top) ?>><font size=-1> �ޡ��������ȥåץڡ���</font><br>
			<input type="checkbox" name="cs_marketer1_mypage" value='t' <? cs_checked($fetch->cs_marketer1_mypage) ?>><font size=-1> �ޡ�������̵��My�ڡ���</font><br>
			<input type="checkbox" name="cs_marketer2_mypage" value='t' <? cs_checked($fetch->cs_marketer2_mypage) ?>><font size=-1> �ޡ�������ͭ��My�ڡ���</font><br>
			<input type="checkbox" name="cs_agent_top" value='t' <? cs_checked($fetch->cs_agent_top) ?>><font size=-1> ����������ȥȥåץڡ���</font><br>
			<input type="checkbox" name="cs_agent_mypage" value='t' <? cs_checked($fetch->cs_agent_mypage) ?>><font size=-1> �����������My�ڡ���</font><br>
		</td>
	</tr>
	<tr>
		<td class="m1">ɽ������</td>
		<td class="n1">������
			<select name="start_year"><? select_year('2000',' ', get_datepart('Y', $fetch->cs_start_date)) ?></select>��
			<select name="start_month"><? select_month(' ', get_datepart('M', $fetch->cs_start_date)) ?></select>��
			<select name="start_day"><? select_day(' ', get_datepart('D', $fetch->cs_start_date)) ?></select>��
			<br>��λ��
			<select name="end_year"><? select_year('2000',' ', get_datepart('Y', $fetch->cs_end_date)) ?></select>��
			<select name="end_month"><? select_month(' ', get_datepart('M', $fetch->cs_end_date)) ?></select>��
			<select name="end_day"><? select_day(' ', get_datepart('D', $fetch->cs_end_date)) ?></select>��
		</td>
	</tr>
	<tr>
		<td class="m1">����<?=MUST_ITEM?></td>
		<td class="n1"><select name="cs_status"><? select_cs_status($fetch->cs_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="cs_no" <?=value($cs_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
