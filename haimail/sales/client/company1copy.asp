<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:��Ə��o�^����
'******************************************************

'=== �㗝�XID�擾 ===
Private Function GetDairitenID(operator_id)
	Dim sql, ds

	sql = "SELECT OP_dairiten_id FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetDairitenID = ds(0).Value
	End If
End Function

'=== �N���C�A���g�����擾 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== ���C������ ===
Dim dairiten_id, pm, d_class

Set pm = New CPermission

dairiten_id = GetDairitenID(g_login_id)
GetPermission dairiten_id

SetHeaderTitle "��Ə��o�^"
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
 with (document.form1) {
		if (name.value == "") {
			alert("��Ɩ�����͂��Ă��������B");
			name.focus();
			return false;
		}
	}
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
<%
PermissionScript
%>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="company3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">����Ə��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">��Ɩ�</td>
						<td class="n3"><input size=50 name="name" maxlength=100></td>
					</tr>
					<tr>
						<td class="m3">��Ɩ��i���ȁj</td>
						<td class="n3"><input size=50 name="name_kana" maxlength=100></td>
					</tr>
					<tr>
						<td class="m3">�X�֔ԍ�</td>
						<td class="n3"><input size=3 name="zipcode1" maxlength=3> - <input size=4 name="zipcode2" maxlength=4>
					</tr>
					<tr>
						<td class="m3">�Z���P</td>
						<td class="n3"><input size=50 name="address1" maxlength=50></td>
					</tr>
					<tr>
						<td class="m3">�Z���Q</td>
						<td class="n3"><input size=50 name="address2" maxlength=50></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">�������錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm, pm
%>
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
