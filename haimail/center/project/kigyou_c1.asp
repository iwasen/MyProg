<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��Ə��o�^����
'******************************************************

'=== �㗝�X�I���� ===
Private Sub SelectDairiten(dairiten_id)
	Dim sql, ds

	sql = "SELECT * FROM T_DAIRITEN ORDER BY DA_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(dairiten_id, "") & ">- �I�����Ă������� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("DA_dairiten_id").Value & "'" & Selected(dairiten_id, ds("DA_dairiten_id").Value) & ">" & ReplaceTag(ds("DA_name")) & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== �N���C�A���g�����擾 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm_own.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== ���C������ ===
Dim dairiten_id, pm_own, pm_def

Set pm_own = New CPermission
Set pm_def = New CPermission
pm_def.SetAll

dairiten_id = Request.QueryString("dairiten_id")
If Not IsEmpty(dairiten_id) Then
	GetPermission dairiten_id
End If

SetHeaderTitle "��Ə��o�^"
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
		if (name.value == "") {
			alert("��Ɩ�����͂��Ă��������B");
			name.focus();
			return false;
		}
		if (dairiten_id.value == "") {
			alert("�㗝�X��I�����Ă��������B");
			dairiten_id.focus();
			return false;
		}
	}
	return confirm("�o�^���܂��B��낵���ł����H");
}
function OnChange_dairiten(c) {
	if (c.selectedIndex > 0)
		location.href = "kigyou_c1.asp?dairiten_id=" + c.value;
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
<form method="post" action="kigyou_c3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">����Ə��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�㗝�X��</td>
						<td class="n3"><select name="dairiten_id" onchange="OnChange_dairiten(this)"><%SelectDairiten(dairiten_id)%></select></td>
					</tr>
					<tr>
						<td class="m3">��Ɩ�</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50>
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">��Ɩ��i���ȁj</td>
						<td class="n3">
							<input size=50 name="name_kana" maxlength=100>
							<font class="note">�i���p�P�O�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�X�֔ԍ�</td>
						<td class="n3">
							<input size=3 name="zipcode1" maxlength=3> - <input size=4 name="zipcode2" maxlength=4>
							<font class="note">�i�����R���|�S���j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�Z���P</td>
						<td class="n3">
							<input size=50 name="address1" maxlength=50>
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�Z���Q</td>
						<td class="n3">
							<input size=50 name="address2" maxlength=50>
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
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
PermissionForm 3, pm_own, pm_def
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
