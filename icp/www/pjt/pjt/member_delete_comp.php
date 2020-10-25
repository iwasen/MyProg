<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�ץ������ȥ��к����λ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$member_id = $_POST['member_id'];

// ����̾����
$sql = "SELECT pm_name1||pm_name2"
		. " FROM t_pjt_member"
		. " WHERE pm_member_id=" . sql_number($member_id);
$name = db_fetch1($sql);

db_begin_trans();

// �ץ������ȥ��к��
$rec['pm_status'] = sql_number(9);
db_update('t_pjt_member', $rec, sprintf("pm_member_id=%s", sql_number($member_id)));

// ����֥��к��
$rec['jm_status'] = sql_number(9);
db_update('t_job_member', $rec, sprintf("jm_member_id IN (SELECT pm_member_id FROM t_pjt_member WHERE pm_member_id=%s) AND jm_status<>9", sql_number($member_id)));

// ����֤λ��å��п�����
$rec['jb_member_num'] = "(SELECT COUNT(*) FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=jb_job_id AND pm_operator_flag=0 AND jm_status=1)";
db_update('t_job', $rec, sprintf("jb_pjt_id=%s", sql_number($_SESSION[SCD]['pjt_id'])));

db_commit_trans();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>��icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�ץ������Ⱦ���
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<a href="list.php" title="���С��ꥹ��">���С��ꥹ��</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�����λ
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
		<table border="0" cellspacing="0" cellpadding="0" width="760" class="contents">
		<tr>
			<td><?=htmlspecialchars($name)?>�ͤξ���������ޤ�����</td>
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
	<a href="list.php" title="�ꥹ�Ȥ����">�ꥹ�Ȥ����</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
