<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X�S���ҏ��ύX����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�㗝�X�S���ҏ��ύX"
'Response.CacheControl = "no-cache"

Dim sql, ds, operator_id

operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

sql = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR,T_DAIRITEN" & vbCrLf & _
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
			alert("�S����ID����͂��Ă��������B");
			operator_id.focus();
			return false;
		}
		if (!isA(operator_id.value)) {
			alert("�S����ID�͔��p�p���œ��͂��Ă��������B");
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
	if (confirm("���̒S���ҏ����폜���Ă�낵���ł����H")) {
		location.href = "operator4.asp?id=<%=operator_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="operator3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="operator_id" value="<%=operator_id%>">
		<tr>
			<td class="m0">
					<p><font color="#000000">�㗝�X���S���җl�̊�{���ύX���s���܂��B�ύX��A�X�V�{�^���������ĉ������B</font></p>
					<p>���㗝�X���</p>
				</td>
		</tr>
		<tr>
			<td>

					<table border=0 cellspacing=2 cellpadding=3 width="100%">
						<tr>
						<td class="m3" width="25%">�㗝�X�h�c</td>
						<td class="n3"><input type="hidden" name="dairiten_id" value="<%=ds("DA_dairiten_id").Value%>"><%=ds("DA_dairiten_id").Value%></td>
					</tr>
					<tr>

							<td class="m3" width="25%">�㗝�X��</td>
						<td class="n3"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td class="m0">���S���ҏ��</td>
		</tr>
		<tr>
			<td>

					<table border=0 cellspacing=2 cellpadding=3 width="100%">
						<tr>
							<td class="m3" width="25%">�S����ID</td>
							<td class="n3"><%=ds("OP_operator_id").Value%><font class="note">�i�K�{�F���p�p��5�����ȓ��j</font></td>
						</tr>
						<tr>
							<td class="m3" width="25%">�p�X���[�h</td>
							<td class="n3">
								<input size=20 name="password" maxlength=14 value="<%=ds("OP_password").Value%>">
								<font class="note">�i�K�{�F���p�p��10�����ȓ��j</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">�S���Җ�</td>
							<td class="n3"> <font class="note">��</font>
								<input size=20 name="name1" maxlength=10 value="<%=HTMLEncode(ds("OP_name1").Value)%>">
								<font class="note">��</font>
								<input size=20 name="name2" maxlength=10 value="<%=HTMLEncode(ds("OP_name2").Value)%>">
								<font class="note">�i�K�{�F�S�p�j</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">�S���Җ��i�J�i�j</td>
							<td class="n3"> <font class="note">��</font>
								<input size=20 name="name1_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name1_kana").Value)%>">
								<font class="note">��</font>
								<input size=20 name="name2_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name2_kana").Value)%>">
								<font class="note">�i�K�{�F���p�J�i�j</font> </td>
						</tr>
						<tr>
							<td class="m3" width="25%">���[���A�h���X</td>
							<td class="n3">
								<input size=50 name="mail_adr" maxlength=50 value="<%=HTMLEncode(ds("OP_mail_adr").Value)%>">
								<font class="note">�i�K�{�F���p�p���j</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">�d�b�ԍ�</td>
							<td class="n3">
								<input size=20 name="tel_no" maxlength=20 value="<%=ds("OP_tel_no").Value%>">
								<font class="note">�i�K�{�F���p�p���j</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">FAX�ԍ�</td>
							<td class="n3">
								<input size=20 name="fax_no" maxlength=20 value="<%=ds("OP_fax_no").Value%>">
								<font class="note">�i�K�{�F���p�p���j</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">�o�^����</td>
							<td class="n3"><%=ds("OP_regist_date").Value%></td>
						</tr>
						<tr>
							<td class="m3" width="25%">�ŏI�X�V����</td>
							<td class="n3"><%=ds("OP_update_date").Value%></td>
						</tr>
						<tr>
							<td width="25%">�@</td>
							<td>�@</td>
						</tr>
						<tr>
							<td colspan="2" class="np">�폜�{�^����������Ă��܂��܂��ƁA�ߋ��Ɏ��{���ꂽ�v���W�F�N�g�̉{����<b>�S�ĕs��</b>�ƂȂ�܂��B�����Ӊ������B
							</td>
						</tr>
					</table>
			</td>
		</tr>
	</table>
	<p>�@</p>
	<p>
		<input type="submit" value=" �X�V ">
		<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
		<input type="reset" value="���Z�b�g">
		<input type="button" value=" �߂� " onclick="history.back()">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
