<?
$top = './..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mn_mypage.php");

// ��˥���̾����
$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$monitor_name = db_fetch1($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<link rel="stylesheet" type="text/css" href="default.css">
	<title>��������Ƥ���</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form method="post" name="form1" action="m_affi_mail_chk.php">
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;"><br>��������᡼��ϡ�<?=htmlspecialchars($monitor_name)?>���󤬤������ơ�net�ˤ���Ͽ���������Ƥ���<b>�֤�̾���ס֥᡼�륢�ɥ쥹��</b>�������Ԥ�����ɽ������ޤ��������򤴳�ǧ�ξ塢����Ƥ���������<br>��</div></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<th width="100">������</th>
			<th width="10">��</th>
			<td><input type="text" name="to" value="" size="80" class="textbox"></td>
		</tr>
		<tr class="line0">
			<th width="100">��̾</th>
			<th width="10">��</th>
			<td><input type="text" name="subject" value="" size="80" class="textbox"></td>
		</tr>
		<tr class="line1">
			<th valign="top">��ʸ</th>
			<th valign="top">��</th>
			<td><textarea name="content" wrap="hard" cols="80" rows="20" class="textbox"></textarea></td>
		</tr>
		<tr class="line1">
			<td colspan="3" align="center">
				<input type="submit" value="�������Ƥ���" class="button">
				<input type="reset" value="���Ƥ����ƾõ��" class="button">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>
<input type="hidden" name="affiliate_id" <?=value($id)?>>
</form>
</body>

</html>
