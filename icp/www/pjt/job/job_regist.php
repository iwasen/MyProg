<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�����������Ͽ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/auth.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>��icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
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
function check_num(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onsubmit_form1(f) {
	if (f.name.value == "") {
		alert("�����̾�����Ϥ��Ƥ���������");
		f.name.focus();
		return false;
	}
	if (f.room_name.value == "") {
		alert("��ļ�̾�����Ϥ��Ƥ���������");
		f.room_name.focus();
		return false;
	}
	var now = new Date();
	today = new Date(now.getYear(), now.getMonth(), now.getDate());
	if (!check_date(f.start_year.value, f.start_month.value, f.start_day.value)) {
		alert("�»ܳ������դλ��꤬����������ޤ���");
		f.start_day.focus();
		return(false);
	}
	var start_date = new Date(f.start_year.value, f.start_month.value - 1, f.start_day.value);
	if (start_date < today) {
		if (!confirm("�»ܳ������դ˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
			f.start_year.focus();
			return false;
		}
	}
	if (!check_date(f.end_year.value, f.end_month.value, f.end_day.value)) {
		alert("�»ܽ�λ���դλ��꤬����������ޤ���");
		f.end_day.focus();
		return(false);
	}
	var end_date = new Date(f.end_year.value, f.end_month.value - 1, f.end_day.value);
	if (start_date > end_date) {
		alert("�»ܽ�λ���դϼ»ܳ������դ�������դ����Ϥ��Ƥ���������");
		f.end_year.focus();
		return false;
	}
	if (f.member_max.value == "") {
		alert("������ѿͿ������Ϥ��Ƥ���������");
		f.member_max.focus();
		return false;
	}
	if (!check_num(f.member_max.value)) {
		alert("������ѿͿ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.member_max.focus();
		return false;
	}
	if (f.job_cd.value == "") {
		alert("����֥����ɤ����Ϥ��Ƥ���������");
		f.job_cd.focus();
		return false;
	}
	if (!f.job_cd.value.match(/^[\w_]+$/)) {
		alert("����֥����ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������");
		f.job_cd.focus();
		return false;
	}
	return confirm("����֤���Ͽ���ޤ���������Ǥ�����");
}
//-->
	</script>
</head>

<body onload="document.form1.name.focus()">
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�����������Ͽ
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- ��������� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��������� -->

<!-- main -->
<form method="post" name="form1" action="job_regist_comp.php" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td>�������������Ͽ</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">�����̾</th>
			<td width="560"><input type="text" name="name" value="" size="40" class="form"></td>
		</tr>
		<tr>
			<th>����־ܺ�</th>
			<td><textarea name="description" cols="60" rows="5" class="form"></textarea></td>
		</tr>
		<tr>
			<th width="200">��ļ�������</th>
			<td width="560"><input checked type="radio" name="room_type" value="1" id="gi"><label for="gi">�ͥåȥ��륤��</label><br>
			<input type="radio" name="room_type" value="2" id="pi"><label for="pi">�����᡼�륢�󥱡���</label><br>
			<input type="radio" name="room_type" value="3" id="sr"><label for="sr">�Хå��롼��</label></td>
		</tr>
		<tr>
			<th width="200">��ļ�̾</th>
			<td width="560"><input type="text" name="room_name" value="" size="40" class="form"></td>
		</tr>
		<tr>
			<th>�»ܴ���</th>
			<td><select name="start_year" class="form"><? select_year(2004, '', date('Y')) ?></select>ǯ
				<select name="start_month" class="form"><? select_month('', 1) ?></select>��
				<select name="start_day" class="form"><? select_day('', 1) ?></select>��������
				<select name="end_year" class="form"><? select_year(2004, '', date('Y')) ?></select>ǯ
				<select name="end_month" class="form"><? select_month('', 12) ?></select>��
				<select name="end_day" class="form"><? select_day('', 31) ?></select>��</td>
		</tr>
		<tr>
			<th>������ѿͿ�</th>
			<td><input type="text" name="member_max" size=4 maxlength=4 class="form">�� ��0�����Ϥ�������¤ʤ���</td>
		</tr>
		<tr>
			<th>����֥�����</th>
			<td><input type="text" name="job_cd" value="" size="40" class="form"> ��Ⱦ�ѱѿ����Τ�</td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹����</th>
			<td>
				<input type="radio" name="open_mail_addr" value="f" id="open_mail_addr_f" checked><label for="open_mail_addr_f">�᡼�륢�ɥ쥹�򳫼����ʤ����ִ������</label><br>
				<input type="radio" name="open_mail_addr" value="t" id="open_mail_addr_t"><label for="open_mail_addr_t">�᡼�륢�ɥ쥹�򳫼�������ִ����ʤ���</label>
			</td>
		</tr>
		<tr>
			<th>ź�եե�����</th>
			<td>
				<input type="radio" name="attachment_file" value="f" id="attachment_file_f" checked><label for="attachment_file_f">���Ѥ��ʤ�</label><br>
				<input type="radio" name="attachment_file" value="t" id="attachment_file_t"><label for="attachment_file_t">���Ѥ���</label>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<br>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="����Ͽ��" class="form">
	<input type="button" value="����󥻥�" class="form" onclick="history.back()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
