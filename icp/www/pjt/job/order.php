<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����ֿ����ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];
$status = $_POST['status'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// ���ơ������ˤ���å�����
switch ($status) {
case $g_job_status['������']:
	$pan = '�»ܿ���';
	$msg = '����������ǿ������ޤ���<br>��������ϡ���ļ������ס��»ܴ��֡�������ѿͿ��ι����Ͻ���ޤ����ѹ�������ϡ��ƿ����򤪴ꤤ�פ��ޤ���';
	$btn = '��������';
	break;
case $g_job_status['������']:
	$pan = '�������';
	$msg = '���Υ���֤μ»ܿ�������ä��ޤ���';
	$btn = '�������';
	break;
case $g_job_status['�Ե���']:
	$pan = '���괰λ';
	$msg = '����֤������λ���ޤ���';
	$btn = '���괰λ';
	break;
case $g_job_status['��ǧ��']:
	$pan = '���괰λ���';
	$msg = '����֤����괰λ�������ޤ���';
	$btn = '���괰λ���';
	break;
case $g_job_status['�»���']:
	$pan = '��ļ��Ƴ�';
	$msg = '��ļ���Ƴ����ޤ���';
	$btn = '��ļ��Ƴ�';
	break;
case $g_job_status['�ٻ���']:
	$pan = '��ļ��ٻ�';
	$msg = '��ļ���ٻߤ��ޤ���';
	$btn = '��ļ��ٻ�';
	break;
default:
	program_err(__FILE__, __LINE__);
}

// ����־������
$sql = "SELECT * FROM t_job WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
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
		<?=$pan?>
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
			<td>��<?=$msg?></td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">�����̾�ʥ����ID��</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_name)?>��<?=$fetch->jb_job_id?>��</td>
		</tr>
		<tr>
			<th>����־ܺ�</th>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		<tr>
			<th>��ļ�������</th>
			<td><?=decode_room_type($fetch->jb_room_type)?></td>
		</tr>
		<tr>
			<th>�»ܴ���</th>
			<td><?=format_date_j($fetch->jb_start_date)?> �� <?=format_date_j($fetch->jb_end_date)?></td>
		</tr>
		<tr>
			<th>������ѿͿ�</th>
			<td><?=$fetch->jb_member_max ? number_format($fetch->jb_member_max) . '��' : '���¤ʤ�'?></td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹����</th>
			<td><?=$fetch->jb_open_mail_addr == DBFALSE ? '�᡼�륢�ɥ쥹�򳫼����ʤ����ִ������' : '�᡼�륢�ɥ쥹�򳫼�������ִ����ʤ���'?></td>
		</tr>
		<tr>
			<th>ź�եե�����</th>
			<td><?=$fetch->jb_attachment_file == DBFALSE ? '���Ѥ��ʤ�' : '���Ѥ���'?></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<form method="post" name="form1" action="order_comp.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="status" <?=value($status)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="<?=$btn?>" class="form">
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
