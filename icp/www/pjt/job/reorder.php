<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����ֺƿ�����ǧ�ڡ���
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
$name = $_POST['name'];
$room_type = $_POST['room_type'];
$start_date = "{$_POST['start_year']}/{$_POST['start_month']}/{$_POST['start_day']}";
$end_date = "{$_POST['end_year']}/{$_POST['end_month']}/{$_POST['end_day']}";
$member_max = $_POST['member_max'];
$open_mail_addr = $_POST['open_mail_addr'];
$attachment_file = $_POST['attachment_file'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// ����־������
$sql = "SELECT jb_description FROM t_job WHERE jb_job_id=" . sql_number($job_id);
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
		�ƿ�����ǧ
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
			<td>������������ǿ������ޤ���</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">�����̾�ʥ����ID��</th>
			<td width="560"><?=htmlspecialchars($name)?>��<?=$job_id?>��</td>
		</tr>
		<tr>
			<th>����־ܺ�</th>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		<tr>
			<th>��ļ�������</th>
			<td><?=decode_room_type($room_type)?></td>
		</tr>
		<tr>
			<th>�»ܴ���</th>
			<td><?=format_date_j($start_date)?> �� <?=format_date_j($end_date)?></td>
		</tr>
		<tr>
			<th>������ѿͿ�</th>
			<td><?=$member_max ? number_format($member_max) . '��' : '���¤ʤ�'?></td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹����</th>
			<td><?=$open_mail_addr == DBFALSE ? '�᡼�륢�ɥ쥹�򳫼����ʤ����ִ������' : '�᡼�륢�ɥ쥹�򳫼�������ִ����ʤ���'?></td>
		</tr>
		<tr>
			<th>ź�եե�����</th>
			<td><?=$attachment_file == DBFALSE ? '���Ѥ��ʤ�' : '���Ѥ���'?></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<form method="post" name="form1" action="reapply_comp.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="name" <?=value($name)?>>
<input type="hidden" name="room_type" <?=value($room_type)?>>
<input type="hidden" name="start_date" <?=value($start_date)?>>
<input type="hidden" name="end_date" <?=value($end_date)?>>
<input type="hidden" name="member_max" <?=value($member_max)?>>
<input type="hidden" name="open_mail_addr" <?=value($open_mail_addr)?>>
<input type="hidden" name="attachment_file" <?=value($attachment_file)?>>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="�ƿ���" class="form">
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
