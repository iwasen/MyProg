<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:���O�C����ʏ���
'******************************************************

set_global "�A���P�[�g�W�v�T�[�r�X ���O�C��", BACK_NONE
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.login_id.focus();
}
function onSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("���O�C���h�c����͂��Ă��������B");
    form.login_id.focus();
    return false;
  }
  if (form.password.value == "") {
    alert("�p�X���[�h����͂��Ă��������B");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>
<body onload="onLoad_body()">

<% page_header() %>

<div align="center">
<form method="post" name="form1" action="login_check.asp" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">�����O�C��ID�ƃp�X���[�h����͂��Ă�������</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">���O�C��ID</td>
	          <td class="n1"><input class="alpha" type="text" name="login_id" size=30 <%=value(Request.Cookies("user_mail_addr"))%>></td>
	        </tr>
	        <tr>
	          <td class="m1" width="25%">�p�X���[�h</td>
	          <td class="n1"><input type="password" name="password" size=30 maxlength=20></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<span class="note">�{�V�X�e���� Internet Explorer 5.0 �ȏ�ł����p���������B�iNetscape�ł͓��삵�܂���j</span>
  <br><br>
  <input type="submit" value=" ���O�C�� ">
</form>
</div>

<% page_footer() %>

</body>
</html>
