<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����ֺƿ�����λ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/status.php");
include("$inc/mail.php");
include("$inc/job_mail.php");
include("$inc/variable.php");
include("$inc/decode.php");
include("$inc/format.php");

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

db_begin_trans();

// ����֤κƿ�����ե饰�򥻥å�
$rec['jb_reorder_flag'] = sql_bool(true);
db_update('t_job', $rec, "jb_job_id=$job_id");

// �ƿ����ơ��֥���ɲ�
$rec['jr_name'] = sql_char($_POST['name']);
$rec['jr_room_type'] = sql_char($_POST['room_type']);
$rec['jr_start_date'] = sql_char($_POST['start_date']);
$rec['jr_end_date'] = sql_char($_POST['end_date']);
$rec['jr_member_max'] = sql_char($_POST['member_max']);
$rec['jr_open_mail_addr'] = sql_char($_POST['open_mail_addr']);
$rec['jr_attachment_file'] = sql_char($_POST['attachment_file']);
$sql = "SELECT COUNT(*) FROM t_job_reorder WHERE jr_job_id=$job_id";
if (db_fetch1($sql) == 0) {
	$rec['jr_job_id'] = sql_number($job_id);
	db_insert('t_job_reorder', $rec);
} else
	db_update('t_job_reorder', $rec, "jr_job_id=$job_id");

// ���Υ᡼������
send_job_notify_mail($job_id, 'job_reorder', true);

db_commit_trans();
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
		�ƿ�����λ
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
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>������֤κƿ����򤷤ޤ����� ����ǧ��������λ����ޤǾ������Ԥ�����������</td>
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
	<td width="780" align="center"><a href="../index.php" title="�ۡ�������">�ۡ�������</a>���á�
	<a href="javascript:goto('detail.php')" title="����־ܺ٤����">����־ܺ٤����</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
