<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����o�[�l�����i���[�U�[�T�|�[�g:�s�n�o�j
'******************************************************
'=== ���C������ ===
Dim member_id, mail_adr
Dim s_time, e_time

'���N�G��������擾
member_id = "": mail_adr = ""
If Trim(Request.QueryString("f1")) <> "" Then member_id = Request.QueryString("f1")
If Trim(Request.QueryString("f2")) <> "" Then mail_adr = Request.QueryString("f2")

'���^�C�g���ݒ�
SetHeaderTitle "�����o�[�l����"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function InputCheck() {
	with (document.form1) {
		if (id.value == "" && mail_adr.value == "") {
			alert("�͂��߁[��h�c�A���[���A�h���X�̂ǂ��炩�͕K�����͂��Ă��������B");
			id.focus();
			return false;
		}
		if (mail_adr.value != "" && mail_adr.value.indexOf("@") == -1) {
			alert("���[���A�h���X�𐳂������͂��ĉ�����");
			mail_adr.focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="6-3.asp" name="form1" onSubmit="return InputCheck()">
	<table border=0 cellspacing=3 cellpadding=1>
		<tr>
			<td align="right"><nobr><b><font color="#000080">�����o�[�h�c</font></b></nobr></td>
			<td><input type="text" name="id" size=70 value="<%=member_id%>"></td>
		</tr>
		<tr>
			<td align="right"><nobr><b><font color="#000080">���[���A�h���X</font></b></nobr></td>
			<td><input type="text" name="mail_adr" size=70 value="<%=HTMLEncode(mail_adr)%>"></td>
		</tr>
	</table>
	<br>
	<input type="submit" name="btn_ok" value=" �n�j ">
	<input type="button" name="btn_end" value=" �߂� " onClick="location.href='../menu.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
