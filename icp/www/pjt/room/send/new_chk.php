<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:������Ƴ�ǧ
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// ���ϥѥ�᡼��
$subject = $_POST['subject'];
$content = $_POST['content'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>��ǧ���Ƥ�������</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form method="post" name="form1" action="new_send.php">
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;">��ǧ���Ƥ���������</div></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<th width="100">���֥�������</th>
			<th width="10">��</th>
			<td width="530"><?=htmlspecialchars($subject)?></td>
		</tr>
		<tr class="line1">
			<th valign="top">��ʸ</th>
			<th valign="top">��</th>
			<td><pre><?=htmlspecialchars($content)?></pre></td>
		</tr>
		<tr class="line0">
			<td colspan="3" align="center"><input type="submit" value="�������Ƥ���" class="button">
			<input type="button" value="��������" class="button" onclick="history.back()"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>

<input type="hidden" name="subject" <?=value($subject)?>>
<input type="hidden" name="content" <?=value($content)?>>
</form>
</body>

</html>
