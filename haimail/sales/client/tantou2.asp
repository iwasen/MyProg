<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:��ƒS���ҏ��ύX����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "��ƒS���ҏ��ύX"
'Response.CacheControl = "no-cache"

Dim sql, ds, tantousha_id

tantousha_id = Request.QueryString("id")
If IsEmpty(tantousha_id) Then
	SystemError "empty tantousha_id"
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
		if (tantousha_id.value == "") {
			alert("�S����ID����͂��Ă��������B");
			tantousha_id.focus();
			return false;
		}
		if (!isA(tantousha_id.value)) {
			alert("�S����ID�͔��p�p���œ��͂��Ă��������B");
			tantousha_id.focus();
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
		location.href = "tantou4.asp?id=<%=tantousha_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<%
sql = "SELECT *" & vbCrLf & _
			"FROM T_TANTOUSHA, T_COMPANY" & vbCrLf & _
			"WHERE TS_tantousha_id='" & tantousha_id & "' AND TS_company_id=CM_company_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="tantou3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="tantousha_id" value="<%=tantousha_id%>">
		<input type="hidden" name="company_id" value="<%=ds("CM_company_id").Value%>">
		<tr>
			<td class="m0">
				<p><font color="#000000">���M��Ƃ��S���җl�̊�{���ύX���s���܂��B�ύX��u�X�V�v�{�^���������ĉ������B</font></p>
				<p>�����M��Ə��</p>
			</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">��Ƃh�c</td>
						<td class="n3"><%=ds("CM_company_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">��Ɩ�</td>
						<td class="n3"><%=ReplaceTag(ds("CM_name").Value)%></td>
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
						<td class="n3"><%=ds("TS_tantousha_id").Value%>�@<font class="note">���ύX�s��</font></td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3">
							<input size=20 name="password" maxlength=14 value="<%=ds("TS_password").Value%>">
							<font class="note">�i���p�p��10�����ȓ��j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ�</td>
						<td class="n3"> <font class="note">��</font>
							<input size=20 name="name1" maxlength=10 value="<%=HTMLEncode(ds("TS_name1").Value)%>">
							<font class="note">��</font>
							<input size=20 name="name2" maxlength=10 value="<%=HTMLEncode(ds("TS_name2").Value)%>">
							<font class="note">�i�S�p�j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ��i�J�i�j</td>
						<td class="n3"> <font class="note">��</font>
							<input size=20 name="name1_kana" maxlength=10 value="<%=HTMLEncode(ds("TS_name1_kana").Value)%>">
							<font class="note">��</font>
							<input size=20 name="name2_kana" maxlength=10 value="<%=HTMLEncode(ds("TS_name2_kana").Value)%>">
							<font class="note">�i���p�J�i�j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���[���A�h���X</td>
						<td class="n3">
							<input size=50 name="mail_adr" maxlength=50 value="<%=HTMLEncode(ds("TS_mail_adr").Value)%>">
							<font class="note">�i���p�p���j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�d�b�ԍ�</td>
						<td class="n3">
							<input size=20 name="tel_no" maxlength=20 value="<%=ds("TS_tel_no").Value%>">
						</td>
					</tr>
					<tr>
						<td class="m3">FAX�ԍ�</td>
						<td class="n3">
							<input size=20 name="fax_no" maxlength=20 value="<%=ds("TS_fax_no").Value%>">
						</td>
					</tr>
					<tr>
						<td class="m3">�o�^����</td>
						<td class="n3"><%=ds("TS_regist_date").Value%></td>
					</tr>
					<tr>
						<td class="m3">�ŏI�X�V����</td>
						<td class="n3"><%=ds("TS_update_date").Value%></td>
					</tr>
					<tr>
						<td>�@</td>
						<td>�@</td>
					</tr>
					<tr>
						<td colspan="2" class="np">�폜�{�^����������Ă��܂��܂��ƁA�ߋ��Ɏ��{���ꂽ�v���W�F�N�g�̉{����<b>�S�ĕs��</b>�ƂȂ�܂��B�����Ӊ������B</td>
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