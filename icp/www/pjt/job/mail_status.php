<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�᡼�륹�ơ������ѹ���λ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/check.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/err_msg.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_GET['job_id'];
$kind = $_GET['kind'];
$status = $_GET['status'];

switch ($kind) {
case 'guide':
	$kind_s = '����';
	$rec['jb_guide_status'] = sql_number($status);
	break;
case 'start':
	$kind_s = '����';
	$rec['jb_start_status'] = sql_number($status);
	break;
case 'end':
	$kind_s = '��λ';
	$rec['jb_end_status'] = sql_number($status);
	break;
}
db_update('t_job', $rec, "jb_job_id=$job_id");

switch ($status) {
case 1:
	$msg = "{$kind_s}�᡼����������֤��ᤷ�ޤ�����";
	break;
case 2:
	$msg = "{$kind_s}�᡼��������λ���֤����ꤷ�ޤ�����";
	break;
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
		<?=$kind_s?>�᡼�륹�ơ������ѹ�
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

	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="javascript:goto('detail.php')" title="����־ܺ٤����">����־ܺ٤����</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
