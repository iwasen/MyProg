<?php

include ("inc/header_jmk.php");
include ("inc/footer_jmk.php");
include ("inc/database_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��������̽���
'******************************************************/

$title_text = "����Ѥ��̳�� ���󥿡��ե�����";
$title_color = "#9fc741";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.login_id.focus();
}
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("������ɣĤ����Ϥ��Ƥ���������");
    form.login_id.focus();
    return false;
  }
	if (!num_chk(form.login_id.value)){
		alert("������ID��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
		form.login_id.focus();
		return(false);
	}
  if (form.password.value == "") {
    alert("�ѥ���ɤ����Ϥ��Ƥ���������");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,1); ?>

<center>
<form method="post" name="form1" action="login/login_check.php" onSubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">��������ID�ȥѥ���ɤ����Ϥ��Ƥ�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td bgcolor="#CCFFCC" width="25%">������ID</td>
	          <td class="n4"><input type="text" name="login_id"></td>
	        </tr>
	        <tr>
	          <td bgcolor="#CCFFCC" width="25%">�ѥ����</td>
	          <td class="n4"><input type="password" name="password"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<font class="note">�ܥ����ƥ�� Internet Explorer 5.0 �ʾ�Ǥ����Ѥ�����������Netscape�Ǥ�ư��ޤ����</font>
  </br></br>
  <input type="submit" value=" ������ ">
</form>
</center>

<? footer_jmk(1); ?>

</body>
</html>
