<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��������̽���
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('kikasete', '', '�������ơ�net ��̳�ɥ�����', BACK_NONE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.login_id.focus();
}
function onSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("������ɣĤ����Ϥ��Ƥ���������");
    form.login_id.focus();
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
<body onload="onLoad_body()">

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">��������ID�ȥѥ���ɤ����Ϥ��Ƥ�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">������ID</td>
	          <td class="n1"><input class="alpha" type="text" name="login_id" size=30 <?=value($_COOKIE['admin_mail_addr'])?>></td>
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
