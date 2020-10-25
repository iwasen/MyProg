<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:���ϥ᡼��ƥ��ȥ᡼������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/variable.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$pjt_id = $_SESSION[SCD]['pjt_id'];
$job_id = $_POST['job_id'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// �إå����եå�����
$sql = "SELECT pj_pjt_id,pj_pjt_cd,pj_name,jb_job_id,jb_job_cd,jb_name,jb_start_date,jb_end_date,jb_room_type,jb_room_header,jb_room_footer"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);

// �إå����եå��ղ�
$content = make_contents($fetch->jb_room_header, $_POST['content'], $fetch->jb_room_footer);

// ���Ѻ��������ѿ�̾�����ʲ�ļ���Ϣ��
$var_name_ary = get_room_var_name($content);

// ���������ѿ�����ʲ�ļ���Ϣ��
$var_val_ary = get_room_var_val($var_name_ary,
		$fetch->pj_pjt_id,
		$fetch->pj_pjt_cd,
		$fetch->pj_name,
		$fetch->jb_job_id,
		$fetch->jb_job_cd,
		$fetch->jb_name,
		$fetch->jb_start_date,
		$fetch->jb_end_date,
		$fetch->jb_room_type);

// ��ʸ���������ѿ��ִ�
$content = replace_var($var_val_ary, $content);

// �����᡼��ǡ������å�
$pjt_cd = $fetch->pj_pjt_cd;
$job_cd = $fetch->jb_job_cd;
$from = join_mail_from(get_help_mail_addr($pjt_cd, $job_cd), $_POST['from_name']);
$reply_to = get_help_mail_addr($pjt_cd, $job_cd);
$send_mail_id = send_mail_data($_POST['subject'] . ' �ڥƥ���������', $from, $reply_to, $content);

db_begin_trans();

// �ץ������Ƚ�ͭ���ڥ졼�����ȥ���ִ����Ը��½�ͭ���Ф˥ƥ��ȥ᡼������
$to_ary = array();
$sql = "SELECT jm_member_cd,pm_mail_addr,pm_name1,pm_name2,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_password,pm_org_id,pm_org_member_id"
		. " FROM t_pjt_member"
		. " JOIN t_job_member ON jm_job_id=$job_id AND jm_member_id=pm_member_id"
		. " WHERE pm_pjt_id=$pjt_id AND pm_status=1 AND (pm_operator_flag=1 OR " . cond_staff_auth('����ִ���') . ")";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	// �ۿ���ꥹ������
	send_mail_list($send_mail_id, $fetch->pm_mail_addr);
}

send_mail_ok($send_mail_id);

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
		���ϥ᡼��ƥ�������
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
			<td>�����ϥ᡼���ƥ����������ޤ�����</td>
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
	<td width="780" align="center"><a href="javascript:history.back()" title="���ϥ᡼�����깹����">���ϥ᡼�����깹�������</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
