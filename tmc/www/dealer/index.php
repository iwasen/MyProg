<?
/******************************************************
' System :�֤���������� �������������ӥ�������Ź���ѥڡ���
' Content:��������̽���
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>����Ź���ѥ�����ڡ���</title>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
  if (f.login_id.value == "") {
    alert("ID�����Ϥ��Ƥ���������");
    f.login_id.focus();
    return false;
  }
  if (f.password.value == "") {
    alert("�ѥ���ɤ����Ϥ��Ƥ���������");
    f.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" rightmargin="0" bottommargin="0" onload="document.form1.login_id.focus()">

<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%" height="100%" valign="center">
<tr>
	<td>

		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#33a2cc" align="center" width="500" height="50">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" bgcolor="#dbeff7" align="center" width="500" height="100%">
				<tr>
					<td class="titlefont" align="center">����Ź���ѥ�����ڡ���</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="10" align="center" width="400">
		<tr>
			<td class="text14">
<?
if ($_GET['fail'] == '') {
	$login_id = $_COOKIE['login_dealer_cd'];
?>
				ID�ȥѥ���ɤ����Ϥ��ơ������󤷤Ƥ���������
<?
} else {
	$login_id = '';
?>
				<font color="red">���Ϥ���ID�ޤ��ϥѥ���ɤ��㤦�褦�Ǥ���</font><br>
				���١�ID�ȥѥ���ɤ����Ϥ��ơ������󤷤Ƥ���������
<?
}
?>
			</td>
		</tr>
		</table>

		<div align="center">
		<form name="form1" action="login.php" method="post" onSubmit="return onsubmit_form1(this)">

		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#33a2cc" align="center" width="310">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" align="center" width="310" class="text12b">
				<tr>
					<td width="100">ID</td>
					<td width="10">��</td>
					<td width="200"><input type="text" name="login_id" <?=value($login_id)?> size="30" class="textbox"></td>
				</tr>
				<tr>
					<td>�ѥ����</td>
					<td>��</td>
					<td><input type="password" name="password" value="" size="30" class="textbox"></td>
				</tr>
				<tr>
					<td colspan="3" align="center"><input type="submit" name="" value="������" class="button"></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		</form>
		<p class="copyright">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>
		</div>

	</td>
</tr>
</table>

</body>

</html>
