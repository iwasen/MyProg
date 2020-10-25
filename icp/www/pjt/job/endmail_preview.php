<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:��λ�᡼��ץ�ӥ塼
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/mail.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/room.php");
include("$inc/variable.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];

// �إå����եå�����
$sql = "SELECT jb_room_header,jb_room_footer FROM t_job WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	program_err(__FILE__, __LINE__);

// �إå����եå��ղ�
$content = make_contents($fetch->jb_room_header, $_POST['content'], $fetch->jb_room_footer);

// ���������ѿ�����
$content = replace_all_var($job_id, $_SESSION[SCD]['member_id'], $content);
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

<? pjt_header('close') ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td>�ۡ���
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<?=htmlspecialchars($job_name_cd)?>����
		&nbsp;&nbsp;��&nbsp;&nbsp;
		��λ�᡼�����깹��
		&nbsp;&nbsp;��&nbsp;&nbsp;
		�᡼��ץ�ӥ塼
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
			<td>����λ�᡼��ץ�ӥ塼</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">���֥�������</th>
			<td width="560"><?=htmlspecialchars($_POST['subject'])?></td>
		</tr>
		<tr>
			<th>���п�̾��</th>
			<td><?=htmlspecialchars($_POST['from_name'])?></td>
		</tr>
		<tr>
			<th>��ʸ</th>
			<td><pre><?=htmlspecialchars($content)?><pre></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center">
		<a href="javascript:window.close()">���Υ�����ɥ����Ĥ���</a>
	</td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>

<? pjt_footer() ?>

</div>
</body>

</html>
