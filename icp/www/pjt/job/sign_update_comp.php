<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:��̾���괰λ�ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/variable.php");

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
$room_header = ($_POST['head'] == '1') ? $_POST['header'] : '';
$room_footer = ($_POST['foot'] == '1') ? $_POST['footer'] : '';

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// �إå��λ����Բ��ѿ������å�
$err_var_ary = check_personal_var($room_header);
if ($err_var_ary) {
	foreach ($err_var_ary as $var_name)
		$msg[] = "�إå��˻��ѤǤ��ʤ����������ѿ� $var_name ���ޤޤ�Ƥ��ޤ���";
}

// �եå��λ����Բ��ѿ������å�
$err_var_ary = check_personal_var($room_footer);
if ($err_var_ary) {
	foreach ($err_var_ary as $var_name)
		$msg[] = "�եå��˻��ѤǤ��ʤ����������ѿ� $var_name ���ޤޤ�Ƥ��ޤ���";
}

// ��̾����򹹿�
if (!$msg) {
	$rec['jb_room_header'] = sql_char($room_header);
	$rec['jb_room_header_web'] = sql_bool($_POST['room_header_web']);
	$rec['jb_room_footer'] = sql_char($room_footer);
	$rec['jb_room_footer_web'] = sql_bool($_POST['room_footer_web']);
	db_update('t_job', $rec, "jb_job_id=" . sql_number($_POST['job_id']));
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
		��̾���깹����λ
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
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>����̾����򹹿����ޤ���</td>
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
<?
} else
	disp_err('��̾����Ǽ��Υ��顼������ޤ�����', $msg);
?>

<? pjt_footer() ?>

</div>
</body>

</html>
