<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�V�K��Ɠo�^����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�V�K��Ɠo�^"
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
		if (name.value == "") {
			alert("��Ɩ�����͂��Ă��������B");
			name.focus();
			return false;
		}
		if (name1.value == "") {
			alert("�S���Җ�����͂��Ă��������B");
			name1.focus();
			return false;
		}
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
	}
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="regist_client2.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">����Ə��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">��Ɩ��@���P</td>
						<td class="n3"><input size=50 name="name" maxlength=100></td>
					</tr>
					<tr>
						<td class="m3">�S���Җ��@���Q</td>
						<td class="n3">
							<font class="note">��</font><input size=20 name="name1" maxlength=10>
							<font class="note">��</font><input size=20 name="name2" maxlength=10>
						</td>
					</tr>
					<tr>
						<td class="m3">�S����ID</td>
						<td class="n3"><input size=20 name="tantousha_id" maxlength=10> <font class="note">�i���p�p��6�����ȓ��j</font></td>
					</tr>
					<tr>
						<td class="m3">�p�X���[�h</td>
						<td class="n3"><input size=20 name="password" maxlength=14> <font class="note">�i���p�p��10�����ȓ��j</font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value="�@�o�^�@">
	<input type="button" value="�L�����Z��" onclick="window.close()">
</form>
  <p>�@</p>
    <table width="800" border="3" cellspacing="0" cellpadding="3" bordercolor="cd853f">
      <tr>
        <td>
          <table border="0" cellspacing="5" cellpadding="0" width="100%">
			<tr> 
				<td nowrap valign="top" class="info"> 
				<div align="right"><font color="336699">���P</font></div>
				</td>
				<td valign="top" class="info">���[���̃w�b�_�ɔ��M��Ɩ��Ƃ��ĕ\������܂��B���m�ɂ��L���������B</td>
				</tr>
			<tr>
				<td nowrap valign="top" class="info"> 
				<div align="right"><font color="336699">���Q</font></div>
				</td>
				<td valign="top" class="info">���S���җl���͕K�{�����ƂȂ��Ă���܂��B</td>
			</tr>
          </table>
        </td>
      </tr>
    </table>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
