<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ����ʬ������
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/crypt.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// ���ϥѥ�᡼��
$job_id = $_SESSION[SCD]['job_id'];
$crypt_id = $_GET['id'];
$remark_id = decrypt_id($crypt_id);

// �˥å��͡������
$sql = "SELECT COALESCE(jm_nickname,pm_nickname) AS pm_nickname FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=$job_id AND jm_member_id={$_SESSION[SCD]['member_id']}";
$nickname_to = db_fetch1($sql);

// ������å���������
$sql = "SELECT rm_seq_no,rm_job_id,rm_member_id,rm_subject,rm_date,rm_remark_type"
		. " FROM l_remark"
		. " WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// �˥å��͡�������ʳ��ϥ᡼��ξ��Ϻ��п�̾�������
	$nickname_from = get_nickname($fetch->rm_remark_type, $fetch->rm_job_id, $fetch->rm_member_id);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>ȯ��������</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<form method="post" name="form1" action="this_mail_send.php">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#e24b36" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f7c8c1" align="center" width="100%">
		<tr>
			<td class="text14b">���Υ᡼���<?=htmlspecialchars($nickname_to)?>������������ޤ���</td>
		</tr>
		<tr>
			<td class="text12">
				<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" class="text12">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>��[<?=$fetch->rm_seq_no?>] <?=htmlspecialchars($fetch->rm_subject)?>��<br>
								ȯ���ԡ�<?=htmlspecialchars($nickname_from)?>��<?=format_datetime($fetch->rm_date)?>�ˤ�<?=htmlspecialchars($nickname_to)?>����Υ��ɥ쥹���������ޤ���</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><input type="submit" value="��������" class="button">
					<input type="button" value="����󥻥�" class="button" onclick="history.back()"></td>
				</tr>
				</table></td>
		</tr>
		</table></td>
</tr>
</table>

<input type="hidden" name="id" <?=value($crypt_id)?>>
</form>
</body>

</html>
