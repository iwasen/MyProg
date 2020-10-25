<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:��λ�᡼�����깹���ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// ����־������
$sql = "SELECT jb_end_subject,jb_end_from_name,jb_end_content,jb_end_send_date,jb_end_add_mail"
		. " FROM t_job"
		. " WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// ������ǯ������ʬ��ʬ��
	if ($fetch->jb_end_send_date) {
		$send_date = sql_time($fetch->jb_end_send_date);
		$send_year = date('Y', $send_date);
		$send_month = date('m', $send_date);
		$send_day = date('d', $send_date);
		$send_hour = date('H', $send_date);
		$send_minute = date('i', $send_date);
	} else
		$send_year = date('Y');
} else
	program_err(__FILE__, __LINE__);
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
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
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
function onsubmit_form1(f) {
	if (f.subject.value == "") {
		alert("���֥������Ȥ����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.content.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.content.focus();
		return false;
	}
	if (!check_date(f.send_year.value, f.send_month.value, f.send_day.value)) {
		alert("�������λ��꤬����������ޤ���");
		f.send_day.focus();
		return(false);
	}
	var now = new Date();
	var send_date = new Date(f.send_year.value, f.send_month.value - 1, f.send_day.value, f.send_hour.value, f.send_minute.value);
	if (send_date < now) {
		if (!confirm("�������˲������դ����Ϥ���Ƥ��ޤ�����������Ǥ�����")) {
			f.send_year.focus();
			return false;
		}
	}
	switch (f.proc.value) {
	case "preview":
		f.action = "endmail_preview.php";
		f.target = "_blank";
		return true;
	case "testmail":
		f.action = "endmail_testmail.php";
		f.target = "_self";
		return confirm("�ƥ��ȥ᡼����������ޤ���������Ǥ�����");
	case "update":
		f.action = "endmail_update_comp.php";
		f.target = "_self";
		return confirm("��λ�᡼��򹹿����ޤ���������Ǥ�����");
	}
	return false;
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="���Υ���֤ξܺ٤�ɽ�����ޤ�"><?=htmlspecialchars($job_name_cd)?>����</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		��λ�᡼�����깹��
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
<form method="post" name="form1" action="endmail_update_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>����λ�᡼�����깹��</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">���֥�������</th>
			<td width="560"><input type="text" name="subject" <?=value($fetch->jb_end_subject)?> size="60" class="form"></td>
		</tr>
		<tr>
			<th>���п�̾��</th>
			<td><input type="text" name="from_name" <?=value($fetch->jb_end_from_name)?> size="30" class="form"></td>
		</tr>
		<tr>
			<th>��ʸ</th>
			<td><textarea name="content" cols="80" rows="30" class="form"><?=htmlspecialchars($fetch->jb_end_content)?></textarea></td>
		</tr>
		<tr>
			<th>������</th>
			<td><select name="send_year" class="form"><? select_year(2004, '', $send_year) ?></select>ǯ
				<select name="send_month" class="form"><? select_month('', $send_month) ?></select>��
				<select name="send_day" class="form"><? select_day('', $send_day) ?></select>��
				<select name="send_hour" class="form"><? select_hour('', $send_hour) ?></select>��
				<select name="send_minute" class="form"><? select_minute('', $send_minute) ?></select>ʬ
			</td>
		</tr>
		<tr>
			<th>Ʊ����</th>
			<td><textarea name="add_mail" cols="60" rows="5" class="form"><?=str_replace(' ', ",\n", htmlspecialchars($fetch->jb_end_add_mail))?></textarea><br>
����޶��ڤ�����Ϥ��Ʋ�������</td>
		</tr>
		</table>
		</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center">
		<input type="submit" value="�᡼��ץ�ӥ塼" class="form" onclick="document.form1.proc.value='preview'">
		<input type="submit" value="�ƥ��ȥ᡼������" class="form" onclick="document.form1.proc.value='testmail'">
		<input type="submit" value="��������" class="form" onclick="document.form1.proc.value='update'">
		<input type="button" value="����󥻥�" class="form" onclick="history.back()">
		<input type="hidden" name="proc">
	</td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
