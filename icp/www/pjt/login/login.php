<?
/******************************************************
' System :ICP�ץ��������ȥڡ���
' Content:�ץ��������ȥڡ��������������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net����������ڡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function login() {
	var f = document.form1;
  if (f.mail_addr.value == "") {
    alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
    f.mail_addr.focus();
    return false;
  }
  if (f.password.value == "") {
    alert("�ѥ���ɤ����Ϥ��Ƥ���������");
    f.password.focus();
    return false;
  }
	f.action = "login_check.php";
  return true;
}
function cancel() {
	var f = document.form1;
	f.mail_addr.value = "";
	f.password.value = "";
	f.mail_addr.focus();
}
function reminder() {
	var f = document.form1;
  if (f.mail_addr.value == "") {
    alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
    f.mail_addr.focus();
    return false;
  }
	f.action = "reminder.php";
	f.submit();
}
//-->
	</script>
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
<table border="0" cellspacing="0" cellpadding="0" width="800" height="500" class="main">
<tr>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
	<td align="center" width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td>
<?
if ($_GET['fail'] == '') {
?>
				������֥����ɤȥ᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ��Ƥ���������
<?
} elseif ($_GET['fail'] == '1') {
?>
				<font color="red">���Ϥ�������֥����ɤޤ��ϥ᡼�륢�ɥ쥹�ޤ��ϥѥ���ɤ��㤦�褦�Ǥ���</font><br>
				�����١�����֥����ɤȥ᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ��ơ��������󤷤Ƥ���������
<?
} elseif ($_GET['fail'] == '2') {
?>
				<font color="red">��ļ��Ϥ������޽�����Ǥ���</font><br>
<?
} elseif ($_GET['fail'] == '3') {
?>
				<font color="red">��ļ��Ϥ������޵ٻ���Ǥ���</font><br>
<?
} elseif ($_GET['fail'] == '4') {
?>
				<font color="red">���β�ļ��Ͻ�λ���ޤ�����</font><br>
<?
}
?>
			</td>
		</tr>
		</table>
		<table border="0" cellspacing="2" cellpadding="5" width="400" class="login">
		<tr>
			<th width="150">����֥�����</th>
			<td width="250"><input type="text" name="job_cd" <?=value($_GET['job_cd'])?> size="30" class="form"></td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹</th>
			<td><input type="text" name="mail_addr" size="30" class="form" <?=value($_GET['mail_addr'] != '' ? $_GET['mail_addr'] : $_COOKIE['icp_login_member'])?>></td>
		</tr>
		<tr>
			<th>�ѥ����</th>
			<td><input type="password" name="password" value="" size="30" class="form"></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="5" width="400">
		<tr>
			<td align="center"><input type="submit" value="��������" class="btn">
			<input type="button" value="����󥻥�" class="btn" onclick="cancel()"></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td align="right"><a href="javascript:void(reminder())" title="�ѥ���ɤ�˺�줿��">�ѥ���ɤ�˺�줿��</a></td>
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