<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�I�y���[�^���ύX����
'******************************************************

SetHeaderTitle "�I�y���[�^���ύX"
Response.CacheControl = "no-cache"

Dim sql, ds, operator_id
operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

sql = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR, T_DAIRITEN" & vbCrLf & _
			"WHERE OP_operator_id='" & operator_id & "' AND OP_dairiten_id=DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
		if (operator_id.value == "") {
			alert("�I�y���[�^ID����͂��Ă��������B");
			operator_id.focus();
			return false;
		}
		if (!isA(operator_id.value)) {
			alert("�I�y���[�^ID�͔��p�p���œ��͂��Ă��������B");
			operator_id.focus();
			return false;
		}
		if (password.value == "") {
			alert("�p�X���[�h����͂��Ă��������B");
			password.focus();
			return false;
		}
		if (!isA(password.value)) {
			alert("�p�X���[�h�͔��p�p���œ��͂��Ă��������B");
			password.focus();
			return false;
		}
		if (name1.value == "") {
			alert("�S���Җ�����͂��Ă��������B");
			name1.focus();
			return false;
		}
		if (!isA(mail_adr.value)) {
			alert("���[���A�h���X�͔��p�p���œ��͂��Ă��������B");
			mail_adr.focus();
			return false;
		}
		if (!isA(tel_no.value)) {
			alert("�d�b�ԍ��͔��p�p���œ��͂��Ă��������B");
			tel_no.focus();
			return false;
		}
		if (!isA(fax_no.value)) {
			alert("FAX�ԍ��͔��p�p���œ��͂��Ă��������B");
			fax_no.focus();
			return false;
		}
	}
	return confirm("�X�V���܂��B��낵���ł����H");
}
function OnClick_sakujo() {
	if (confirm("���̃I�y���[�^�����폜���Ă�낵���ł����H")) {
		location.href = "eigyou_o4.asp?id=<%=operator_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="eigyou_o3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="operator_id" value="<%=ds("OP_operator_id").Value%>">
		<input type="hidden" name="dairiten_id" value="<%=ds("OP_dairiten_id").Value%>">
		<tr>
			<td class="m0">���㗝�X���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">�㗝�X�h�c</td>
						<td class="n3"><%=ds("OP_dairiten_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">�㗝�X����</td>
						<td class="n3"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td class="m0">���I�y���[�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">�I�y���[�^ID</td>
						<td class="n3"><%=ds("OP_operator_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3">
							<input size=20 name="password" maxlength=14 value="<%=ds("OP_password").Value%>">
							<font class="note">�i���p�P�S�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ�</td>
						<td class="n3">
							<font class="note">��</font><input size=20 name="name1" maxlength=10 value="<%=HTMLEncode(ds("OP_name1").Value)%>">
							<font class="note">��</font><input size=20 name="name2" maxlength=10 value="<%=HTMLEncode(ds("OP_name2").Value)%>">
							<font class="note">�i�S�p�P�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ��i�J�i�j</td>
						<td class="n3">
							<font class="note">��</font><input size=20 name="name1_kana" maxlength=10 value="<%=HTMLEncode(ds("OP_name1_kana").Value)%>">
							<font class="note">��</font><input size=20 name="name2_kana" maxlength=10 value="<%=HTMLEncode(ds("OP_name2_kana").Value)%>">
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���[���A�h���X</td>
						<td class="n3">
							<input size=50 name="mail_adr" maxlength=50 value="<%=HTMLEncode(ds("OP_mail_adr").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�d�b�ԍ�</td>
						<td class="n3">
							<input size=20 name="tel_no" maxlength=20 value="<%=ds("OP_tel_no").Value%>">
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">FAX�ԍ�</td>
						<td class="n3">
							<input size=20 name="fax_no" maxlength=20 value="<%=ds("OP_fax_no").Value%>">
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�o�^����</td>
						<td class="n3"><%=ds("OP_regist_date").Value%></td>
					</tr>
					<tr>
						<td class="m3">�ŏI�X�V����</td>
						<td class="n3"><%=ds("OP_update_date").Value%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �X�V ">
	<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
