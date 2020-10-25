<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����֥��й����ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];
$member_id = $_POST['member_id'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// �᡼�륢�ɥ쥹��ʣ�����å�
$sql = sprintf("SELECT COUNT(*) FROM t_pjt_member WHERE pm_pjt_id=%s AND pm_member_id<>%s AND pm_mail_addr=%s",
		sql_number($_SESSION[SCD]['pjt_id']),
		sql_number($_POST['member_id']),
		sql_char(strtolower($_POST['mail_addr'])));
if (db_fetch1($sql) > 0)
	$msg[] = "���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���";

if (!$msg) {
	db_begin_trans();

	// �ץ������ȥ��С����󹹿�
	$rec['pm_mail_addr'] = sql_char(strtolower($_POST['mail_addr']));
	$rec['pm_password'] = sql_char($_POST['password']);
	$rec['pm_name1'] = sql_char($_POST['name1']);
	$rec['pm_name2'] = sql_char($_POST['name2']);
	$rec['pm_name1_kana'] = sql_char($_POST['name1_kana']);
	$rec['pm_name2_kana'] = sql_char($_POST['name2_kana']);
	db_update('t_pjt_member', $rec, sprintf("pm_member_id=%s", sql_char($_POST['member_id'])));

	// ����֥��С����󹹿�
	$rec['jm_participant_auth'] = sql_char(str_participant_auth($_POST['participant_auth']));
	$rec['jm_nickname'] = sql_char($_POST['nickname']);
	db_update('t_job_member', $rec, sprintf("jm_job_id=%s AND jm_member_id=%s", sql_number($job_id), sql_number($member_id)));

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
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="���Υ���֤ξܺ٤�ɽ�����ޤ�"><?=htmlspecialchars($job_name_cd)?>����</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<a href="javascript:goto('list.php')" title="���С��ꥹ��">���С��ꥹ��</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		���깹��<? disp_result($msg) ?>
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

<?
if (!$msg) {
?>
<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td><?=htmlspecialchars("{$_POST['name1']}{$_POST['name2']}")?>�ͤξ���򹹿����ޤ�����</td>
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
	<a href="javascript:goto('list.php')" title="�ꥹ�Ȥ����">�ꥹ�Ȥ����</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->
<?
} else
	disp_err('���С����󹹿��Ǽ��Υ��顼������ޤ���', $msg);
?>

<? pjt_footer() ?>

</div>
</body>

</html>
