<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��������̽���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.mail_addr.focus();
}
function onSubmit_form1(f) {
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
  return true;
}
//-->
</script>
</head>
<body onload="onLoad_body()">

<? center_header('<div align="center">' . SYSTEM_NAME . '��������</div>') ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">���᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ��Ƥ�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">�᡼�륢�ɥ쥹</td>
	          <td class="n1"><input class="alpha" type="text" name="mail_addr" size=40 <?=value($_COOKIE['login_mail_addr'])?>></td>
	        </tr>
	        <tr>
	          <td class="m1" width="25%">�ѥ����</td>
	          <td class="n1"><input type="password" name="password"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<span class="note">�ܥ����ƥ�� Internet Explorer 5.0 �ʾ�Ǥ����Ѥ�����������Netscape�Ǥ�ư��ޤ����</span>
  <br><br>
  <input type="submit" value=" ������ ">
</form>
</div>

<? center_footer() ?>

</body>
</html>
