<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����ǃ}�X�^�C��
'******************************************************

'=== �����I�� ===
Private Sub SelectPrivilege(privilege)
	Dim i

	For i = 1 To 9
		Writeln "<option value='" & i & "'" & Selected(CStr(i), privilege) & ">" & i & "</option>"
	Next
End Sub

'=== ���C������ ===
SetHeaderTitle "�����ǃ}�X�^�C��"
Response.CacheControl = "no-cache"

Dim sql, ds, user_id
user_id = Request.QueryString("id")
If IsEmpty(user_id) Then
	SystemError "empty user_id"
End If

sql = "SELECT * FROM T_CENTER WHERE CN_user_id='" & user_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "not found user_id"
End If
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
function OnClick_sakujo() {
	if (confirm("���̃��[�U���폜���Ă�낵���ł����H")) {
		location.href = "center5.asp?id=<%=user_id%>";
	}
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
							<input type="text" name="user_id" size=10 maxlength=10 value="<%=ds("CN_user_id").Value%>">
							<font class="note">�i���p�P�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3">
							<input type="text" name="password" size=14 maxlength=14 value="<%=ds("CN_password").Value%>">
							<font class="note">�i���p�P�S�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3">
							<input type="text" name="name" size=50 maxlength=25 value="<%=HTMLEncode(ds("CN_name").Value)%>">
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���[���A�h���X</td>
						<td class="n3">
							<input type="text" name="mail_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CN_mail_adr").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3"><select name="privilege"><%SelectPrivilege ds("CN_privilege").Value%></select></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �X�V ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
