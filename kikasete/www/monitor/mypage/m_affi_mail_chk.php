<?
$top = './..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mn_mypage.php");
include("$inc/mn_error.php");

if ($to == '')
	$err_msg[] = '�����褬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($to))
	$err_msg[] = '�����褬���������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($subject == '')
	$err_msg[] = '��̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($content == '')
	$err_msg[] = '��ʸ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if (!$err_msg) {
	// ��˥���̾�ȥ᡼�륢�ɥ쥹����
	$sql = "SELECT mn_mail_addr,mn_name1,mn_name2 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$from = "$fetch->mn_name1 $fetch->mn_name2 ��{$fetch->mn_mail_addr}��";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="default.css">
	<title>��ǧ���Ƥ�������</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form method="post" name="form1" action="m_affi_mail_send.php">
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;">��ǧ���Ƥ���������</div></td>
</tr>
<tr>
	<td>
<?
if (!$err_msg) {
?>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<th width="100">������</th>
			<th width="10">��</th>
			<td><?=htmlspecialchars($to)?></td>
		</tr>
		<tr class="line0">
			<th width="100">������</th>
			<th width="10">��</th>
			<td><?=htmlspecialchars($from)?></td>
		</tr>
		<tr class="line0">
			<th width="100">��̾</th>
			<th width="10">��</th>
			<td><?=htmlspecialchars($subject)?></td>
		</tr>
		<tr class="line1">
			<th valign="top">��ʸ</th>
			<th valign="top">��</th>
			<td><pre><?=htmlspecialchars($content)?></pre></td>
		</tr>
		<tr class="line0">
			<td colspan="3" align="center">
				<input type="submit" value="�������Ƥ���" class="button">
				<input type="button" name="" value="��������" class="button" onclick="history.back()">
			</td>
		</tr>
		</table>
<?
} else
	error_msg($err_msg);
?>
	</td>
</tr>
</table>
<input type="hidden" name="affiliate_id" <?=value($affiliate_id)?>>
<input type="hidden" name="to" <?=value($to)?>>
<input type="hidden" name="subject" <?=value($subject)?>>
<input type="hidden" name="content" <?=value($content)?>>

</div>

</form>
</body>

</html>
