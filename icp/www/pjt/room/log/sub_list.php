<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ��Ͽ����å�ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/crypt.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/subject_common.php");
include("$inc/thread.php");

// ���¥����å�
if (!check_staff_auth('ȯ��Ͽ�������ݡ���'))
	redirect("$top/room/top.php");

// ���ϥѥ�᡼��
$job_id = $_SESSION[SCD]['job_id'];
$remark_id = decrypt_id($_GET['id']);

// �ޡ����󥰿��ɤ߹���
get_marking_data();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>����å���ʬ</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<form>

<div id="viewoption">

<table border="0" cellspacing="0" cellpadding="10" width="100%" class="text12">
<tr>
	<td class="text14b">ȯ��Ͽ���������
	</td>
</tr>
</table>
<div align="center">
<table border="0" cellspacing="0" cellpadding="0" width="90%">
<tr>
	<td><p class="text12">����������������������������������������������������������������������������������������
	��������������������������������������������������������������������������������
	������������������������������������������������������������������������
	��������������������������������������������������������������������������������
	��������</p></td>
</tr>
</table>
</div>
	




</form>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<table border="0" cellspacing="0" cellpadding="10" width="100%" class="text12">
<tr>
	<td class="text14b">�������</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td>
<pre>
<?
output_thread($job_id, 1, 0, 0, 0, false, $remark_id);
?>
</pre>

	</td>
</tr>
</table>

<hr size="1" width="100%" align="center" color="#c0c0c0" noshade>

<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><a href="#top" title="���Υڡ����Υȥåפ����">�����Υڡ����Υȥåפ����</a></td>
</tr>
</table>
</body>

</html>
