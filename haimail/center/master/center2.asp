<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����ǃ}�X�^�o�^
'******************************************************

'=== �����I�� ===
Private Sub SelectPrivilege(privilege)
	Dim i

	For i = 1 To 9
		Writeln "<option value='" & i & "'" & Selected(i, privilege) & ">" & i & "</option>"
	Next
End Sub

'=== ���C������ ===
SetHeaderTitle "�����ǃ}�X�^�o�^"
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
 with (document.form1) {
		if (user_id.value == "") {
			alert("���[�UID����͂��Ă��������B");
			user_id.focus();
			return false;
		}
		if (password.value == "") {
			alert("�p�X���[�h����͂��Ă��������B");
			password.focus();
			return false;
		}
		if (name.value == "") {
			alert("��������͂��Ă��������B");
			name.focus();
			return false;
		}
	}
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="center4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�������ǃ}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">���[�UID</td>
						<td class="n3">
							<input type="text" name="user_id" size=10 maxlength=10>
							<font class="note">�i���p�P�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3">
							<input type="text" name="password" size=14 maxlength=14>
							<font class="note">�i���p�P�S�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3">
							<input type="text" name="name" size=50 maxlength=25>
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���[���A�h���X</td>
						<td class="n3">
							<input type="text" name="mail_adr" size=50 maxlength=50>
							<font class="note">�i���p�T�O�����܂Łj</font>
							</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3"><select name="privilege"><%SelectPrivilege 9%></select></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
