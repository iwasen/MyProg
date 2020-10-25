<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�㗝�X���o�^����
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

'=== �㗝�X�����擾 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm_own.permission = ds("DA_pm_agent").Value
		pm_client.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== ���C������ ===
Dim rep_id, d_class
Dim pm_own, pm_client

Set pm_own = New CPermission
Set pm_client = New CPermission

rep_id = GetDairitenID(g_login_id)
GetPermission rep_id
d_class = "B"

SetHeaderTitle "�㗝�X���o�^"
Response.CacheControl = "no-cache"
%>
<HTML>
<HEAD>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
 with (document.form1) {
    if (name.value == "") {
      alert("�㗝�X������͂��Ă��������B");
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
<form method="post" action="dairiten3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���㗝�X���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">�㗝�X����</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50>
							<font class="note">�i�S�p�T�O�����ȓ��j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�㗝�X���́i���ȁj</td>
						<td class="n3">
							<input size=50 name="name_kana" maxlength=100>
							<font class="note">�i���p�P�O�O�����ȓ��j</font>
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
							<font class="note">�i�S�p�T�O�����ȓ��j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�Z���Q</td>
						<td class="n3">
							<input size=50 name="address2" maxlength=50>
							<font class="note">�i�S�p�T�O�����ȓ��j</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<span <%If Not CheckPermission(PM_AGENT_PERMISSION) Then Response.Write "style='display:none'"%>>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">�����̑㗝�X�ɋ����錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 2, pm_own, pm_own
%>
			</td>
		</tr>
	</table>
	</span>
	<span <%If Not CheckPermission(PM_CLIENT_PERMISSION) Then Response.Write "style='display:none'"%>>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">���N���C�A���g�ɋ����錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm_client, pm_client
%>
			</td>
		</tr>
	</table>
	</span>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</BODY>
</HTML>
