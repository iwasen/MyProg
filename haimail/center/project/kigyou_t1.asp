<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S���ҏ��o�^����
'******************************************************

SetHeaderTitle "�S���ҏ��o�^"
'Response.CacheControl = "no-cache"

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
		if (name1.value == "") {
			alert("�S���Җ�����͂��Ă��������B");
			name1.focus();
			return false;
		}
		if (!isA(mail_adr.value)) {
			alert("e-mail�A�h���X�͔��p�p���œ��͂��Ă��������B");
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
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
Dim sql, ds, company_id

company_id = Request.QueryString("company_id")

sql = "SELECT *" & vbCrLf & _
			"FROM T_COMPANY" & vbCrLf & _
			"WHERE CM_company_id='" & company_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>

<center>
<form method="post" action="kigyou_t3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="id_flag" value=1>
		<input type="hidden" name="company_id" value=<%=ds("CM_company_id").Value%>>
		<tr>
			<td class="m0">����Ə��</td>
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
						<td class="m3">�S����ID</td>
						<td class="n3">
							<input size=20 name="tantousha_id" maxlength=10>
							<font class="note">�i���p�P�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3">
							<input size=20 name="password" maxlength=14>
							<font class="note">�i���p�P�S�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ�</td>
						<td class="n3">
							<font class="note">��</font><input size=20 name="name1" maxlength=10>
							<font class="note">��</font><input size=20 name="name2" maxlength=10>
							<font class="note">�i�S�p�P�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�S���Җ��i�J�i�j</td>
						<td class="n3">
							<font class="note">��</font><input size=20 name="name1_kana" maxlength=10>
							<font class="note">��</font><input size=20 name="name2_kana" maxlength=10>
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">e-mail�A�h���X</td>
						<td class="n3">
							<input size=50 name="mail_adr" maxlength=50>
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�d�b�ԍ�</td>
						<td class="n3">
							<input size=20 name="tel_no" maxlength=20>
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">FAX�ԍ�</td>
						<td class="n3">
							<input size=20 name="fax_no" maxlength=20>
							<font class="note">�i���p�Q�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="location.href='kigyou.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
