<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�s�B���[���A�h���X�C������
'******************************************************

SetHeaderTitle "�s�B���[���A�h���X�C��"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(f) {
	if (f.mail_adr.value == "") {
		alert("���[���A�h���X����͂��Ă��������B");
		f.mail_adr.focus();
		return false;
	}
	if (f.mail_adr.value == f.mail_adr_org.value) {
		alert("���[���A�h���X��ύX���Ă��������B");
		f.mail_adr.focus();
		return false;
	}
	return confirm("���[���A�h���X��ύX���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
Dim sql, ds, member_id

member_id = Request.QueryString("id")
sql = "SELECT MM_mail_adr FROM T_MEMBER WHERE MM_member_id='" & member_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="cl_update.asp" name="form1" onSubmit="return OnSubmit_form1(this);">
	<input type="hidden" name="member_id" value=<%=member_id%>>
	<input type="hidden" name="mail_adr_org" value="<%=HTMLEncode(ds("MM_mail_adr").Value)%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�����[���A�h���X���C�����Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">���[���A�h���X</td>
						<td class="n3"><input type="text" name="mail_adr" size=60 value="<%=HTMLEncode(ds("MM_mail_adr").Value)%>"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �ύX ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
