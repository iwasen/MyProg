<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�Q�X�g���O�C����ʏ���
'******************************************************

Const g_login_type = "G"

SetHeaderTitle "�͂��߁[��E�����o�[�����C���^�[�t�F�C�X"
top_link = ""
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
	document.form1.login_id.focus();
}
function OnSubmit_form1(form) {
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
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="guest_check.asp" onSubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�����O�C��ID�ƃp�X���[�h����͂��Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">���O�C��ID</td>
						<td class="n3"><input type="text" name="login_id"></td>
					</tr>
					<tr>
						<td class="m3" width="25%">�p�X���[�h</td>
						<td class="n3"><input type="password" name="password"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<font class="note">�{�V�X�e���� Internet Explorer 5.0 �ȏ�ł����p���������B�iNetscape�ł͓��삵�܂���j</font>
	<br><br>
	<input type="submit" value=" ���O�C�� ">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
