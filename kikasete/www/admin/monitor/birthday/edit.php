<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�������᡼���ۿ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('monitor', '��˥�������', '�������᡼���ۿ�', BACK_TOP);

$sql = "SELECT * FROM t_birthday_mail WHERE bm_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('list.php');
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
function onLoad_body() {
	var f = document.form1;
	f.priority.focus();
}
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		return input_check(f, "�������᡼��򹹿����ޤ�����������Ǥ�����");
	case "delete":
		return confirm("�������᡼��������ޤ�����������Ǥ�����");
	case "test":
		return input_check(f, "�������᡼���ƥ����������ޤ�����������Ǥ�����");
	}
	return false;
}
function input_check(f, msg) {
	if (f.priority.value == "") {
		alert("ͥ���̤����Ϥ��Ƥ���������");
		f.priority.focus();
		return false;
	}
	if (!check_num(f.priority.value)) {
		alert("ͥ���̤�Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.priority.focus();
		return false;
	}
	if (!check_num(f.age_from.value)) {
		alert("ǯ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.age_from.focus();
		return false;
	}
	if (!check_num(f.age_to.value)) {
		alert("ǯ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.age_to.focus();
		return false;
	}
	if (f.point.value == "") {
		alert("��Ϳ�ݥ���Ȥ����Ϥ��Ƥ���������");
		f.point.focus();
		return false;
	}
	if (!check_num(f.point.value)) {
		alert("��Ϳ�ݥ���Ȥ�Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.point.focus();
		return false;
	}
	if (f.from_adr.value == "") {
		alert("���������ɥ쥹�����Ϥ��Ƥ���������");
		f.from_adr.focus();
		return false;
	}
	if (f.subject.value == "") {
		alert("��̾�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	return confirm(msg);
}
function check_num(s) {
	for (var i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9")
			return false;
	}
	return true;
}

//-->
</script>
</head>
<body onload="onLoad_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>���������᡼������Ϥ��Ƥ�������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ͥ����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="priority" size=4 maxlength=6 <?=value($fetch->bm_priority)?>>
			<span class="note">��1����99�ޤǤο��ͤ����Ϥ��Ƥ���������</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input type="radio" name="sex" <?=value_checked(0, $fetch->bm_sex)?>>����ʤ�
			<input type="radio" name="sex" <?=value_checked(1, $fetch->bm_sex)?>>����
			<input type="radio" name="sex" <?=value_checked(2, $fetch->bm_sex)?>>����
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ǯ��</td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="age_from" size=4 maxlength=3 <?=value($fetch->bm_age_from)?>>�͡�
			<input class="number" type="text" name="age_to" size=4 maxlength=3 <?=value($fetch->bm_age_to)?>>�ͤޤ�
			<span class="note">��ǯ������ꤷ�ʤ����ϥ֥�󥯤ˤ��Ƥ���������</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��Ͽ����������Ϳ�ݥ����<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="number" type="text" name="point" size=6 maxlength=6 <?=value($fetch->bm_point)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">���������ɥ쥹<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="alpha" type="text" name="from_adr" size=50 maxlength=50 <?=value($fetch->bm_from_adr)?>>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">��̾<?=MUST_ITEM?></td>
		<td class="n1" width="80%">
			<input class="kanji" type="text" name="subject" size=80 maxlength=100 <?=value($fetch->bm_subject)?>>
			<span class="note">�����ѣ�����ʸ���ޤǡ�</span>
		</td>
	</tr>
	<tr>
		<td class="m1">��ʸ<?=MUST_ITEM?></td>
		<td class="n1">
			<span class="note">��������ʸ�����Ȥ��ޤ����ʥ�˥���̾= %MONITOR_NAME%���᡼�륢�ɥ쥹=%MAIL_ADDR%����������URL=%LOGIN_URL% ��</span>
			<textarea name="body" cols=78 rows=20><?=$fetch->bm_body?></textarea><br>
		</td>
	</tr>
	<tr>
		<td class="m1">ͭ���ե饰<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="void_flag" <?=value_checked(1, $fetch->bm_void_flag)?>>ͭ��
			<input type="radio" name="void_flag" <?=value_checked(2, $fetch->bm_void_flag)?>>̵��
		</td>
	</tr>
</table>
<br>
<input type="submit" value="�ƥ�������" onclick="document.form1.next_action.value='test'">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<input type="button" value="����󥻥�" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="seq_no" <?=value($seq_no)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>