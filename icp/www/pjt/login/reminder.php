<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:�ץ������ȥڡ������������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/header.php");

$sql = sprintf(
			"SELECT pm_mail_addr,pm_password,pm_name1,pm_name2,pm_nickname"
		. " FROM t_pjt"
		. " JOIN t_pjt_member ON pm_pjt_id=pj_pjt_id"
		. " WHERE pj_pjt_cd=%s AND pm_mail_addr=%s AND pm_status=1",
		sql_char(get_pjt_cd()),
		sql_char(strtolower($_POST['mail_addr'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template('pjt_reminder', $subject, $from, $cc, $bcc, $repty_to, $body);

	// ���������ѿ�����
	$body = str_replace('%NAME1%', $fetch->pm_name1, $body);
	$body = str_replace('%NAME2%', $fetch->pm_name2, $body);
	$body = str_replace('%MAIL_ADDR%', $fetch->pm_mail_addr, $body);
	$body = str_replace('%PASSWORD%', $fetch->pm_password, $body);
	$body = str_replace('%NICKNAME%', $fetch->pm_nickname, $body);

	send_mail_one($subject, $from, $fetch->pm_mail_addr, $reply_to, $body);

	$msg = '�ѥ���ɤ�᡼��Ǥ����ꤷ�ޤ�����';
} else {
	$msg = '���Ϥ��줿�᡼�륢�ɥ쥹����Ͽ����Ƥ��ʤ��褦�Ǥ���';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net��������ڡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
</head>

<body>
<div align="center">

<? pjt_header('login') ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><img src="<?=$top?>/img/spacer.gif" width="20" height="20" border="0"></td>
</tr>
</table>
<!-- pankuzu -->

<!-- main -->
<form method="post" name="form1" action="login_check.php" onSubmit="return login(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" height="200" class="main">
<tr>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
	<td align="center" width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td>
				��<?=$msg?>
			</td>
		</tr>
		</table>
		<br>
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td align="right"><a href="javascript:history.back()" title="�ѥ���ɤ�˺�줿��">������ڡ��������</a></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
</form>
<!-- main -->
<!-- copyright -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="copyright">
<tr>
	<td align="center">
	�ܥ����ƥ�� Internet Explorer 5.0/Netscape 6.0 �ʾ�Ǥ����Ѥ���������<br>
	<?=COPYRIGHT?>
	</td>
</tr>
</table>

<!-- copyright -->
</div>
</body>

</html>
