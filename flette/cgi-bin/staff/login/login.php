#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:��������̽���
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/header.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onload_body() {
  document.form1.mail_addr.focus();
}
function onsubmit_form1(form) {
  if (form.mail_addr.value == "") {
    alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
    form.mail_addr.focus();
    return false;
  }
  if (form.password.value == "") {
    alert("�ѥ���ɤ����Ϥ��Ƥ���������");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>
<body onload="onload_body()">

<? page_header('�����åե�����', false, false) ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onsubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">���᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ��Ƥ�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">�᡼�륢�ɥ쥹</td>
	          <td class="n1"><input class="alpha" type="text" name="mail_addr" value="" size=30></td>
	        </tr>
	        <tr>
	          <td class="m1" width="25%">�ѥ����</td>
	          <td class="n1"><input type="password" name="password" size=30 maxlength=20></td>
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

<? page_footer() ?>

</body>
</html>
