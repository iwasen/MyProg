<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�����������Ͽ��λ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/status.php");
include("$inc/member_cd.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ����֥����ɽ�ʣ�����å�
$sql = sprintf("SELECT COUNT(*) FROM t_job WHERE jb_pjt_id=%s AND jb_job_cd=%s",
			sql_number($_SESSION[SCD]['pjt_id']),
			sql_char($_POST['job_cd']));
if (db_fetch1($sql) > 0)
	$msg[] = '���Ϥ�������֥����ɤϴ�����Ͽ����Ƥ���褦�Ǥ���';

if (!$msg) {
	db_begin_trans();

	// �������Ͽ
	$rec['jb_pjt_id'] = sql_number($_SESSION[SCD]['pjt_id']);
	$rec['jb_status'] = sql_number($g_job_status['������']);
	$rec['jb_job_cd'] = sql_char($_POST['job_cd']);
	$rec['jb_name'] = sql_char($_POST['name']);
	$rec['jb_description'] = sql_char($_POST['description']);
	$rec['jb_member_id'] = sql_number($g_member_id);
	$rec['jb_start_date'] = sql_date("{$_POST['start_year']}/{$_POST['start_month']}/{$_POST['start_day']}");
	$rec['jb_end_date'] = sql_date("{$_POST['end_year']}/{$_POST['end_month']}/{$_POST['end_day']}");
	$rec['jb_member_max'] = sql_number($_POST['member_max']);
	$rec['jb_member_num'] = sql_number(0);
	$rec['jb_room_name'] = sql_char($_POST['room_name']);
	$rec['jb_room_type'] = sql_number($_POST['room_type']);
	$rec['jb_open_mail_addr'] = sql_char($_POST['open_mail_addr']);
	$rec['jb_attachment_file'] = sql_char($_POST['attachment_file']);
	db_insert('t_job', $rec);
	$job_id = get_current_seq('t_job', 'jb_job_id');

	// ���ڥ졼���Υ��С�ID�����
	$sql = sprintf("SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=%s AND pm_operator_flag=1",
			sql_number($_SESSION[SCD]['pjt_id']));
	$operator_member_id = db_fetch1($sql);

	// ���ڥ졼���򥸥�֥��Ф��ɲ�
	$rec['jm_job_id'] = sql_number($job_id);
	$rec['jm_member_id'] = sql_number($operator_member_id);
	$rec['jm_member_cd'] = sql_char(create_member_cd());
	$rec['jm_status'] = sql_number(1);
	$rec['jm_participant_auth'] = sql_char(operator_participant_auth());
	db_insert('t_job_member', $rec);

	// �ץ������Ȥ˥���ֿ��򥻥å�
	$rec['pj_job_num'] = sprintf("(SELECT COUNT(*) FROM t_job WHERE jb_pjt_id=%s)", sql_number($_SESSION[SCD]['pjt_id']));
	db_update('t_pjt', $rec, sprintf("pj_pjt_id=%s", sql_number($_SESSION[SCD]['pjt_id'])));

	db_commit_trans();
}
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
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�����������Ͽ<? disp_result($msg) ?>
		</td>
</tr>
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

<?
if (!$msg) {
?>
<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>����������֤���Ͽ���ޤ���</td>
		</tr>
		</table>

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<input type="hidden" name="job_id" <?=value($job_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="javascript:goto('detail.php')" title="����־ܺ٤����">����־ܺ٤����</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->
<?
} else
	disp_err('��������֤���Ͽ�Ǽ��Υ��顼������ޤ���', $msg);
?>

<? pjt_footer() ?>

</div>
</body>

</html>
