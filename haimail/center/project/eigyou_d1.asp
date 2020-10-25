<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�㗝�X���o�^����
'******************************************************

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

'=== ���b�v�I���� ===
Sub SelectRep(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_dairiten_id,DA_name FROM T_DAIRITEN WHERE DA_class='A'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(dairiten_id, "") & ">- �I�����Ă������� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("DA_dairiten_id").Value & "'" & Selected(dairiten_id, ds("DA_dairiten_id").Value) & ">" & ds("DA_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== ���C������ ===
Dim dairiten_id, rep_id, d_class
Dim pm_own, pm_agent, pm_client, pm_def

Set pm_own = New CPermission
Set pm_agent = New CPermission
Set pm_client = New CPermission
Set pm_def = New CPermission
pm_def.SetAll

rep_id = Request.QueryString("rep")
If Not IsEmpty(rep_id) Then
	GetPermission rep_id
	d_class = "B"
End If

SetHeaderTitle "�㗝�X���o�^"
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
		if (d_class.value == "") {
			alert("�㗝�X��ʂ�I�����Ă��������B");
			d_class.focus();
			return false;
		}
		if (name.value == "") {
			alert("�㗝�X���̂���͂��Ă��������B");
			name.focus();
			return false;
		}
	}
	return confirm("�o�^���܂��B��낵���ł����H");
}
function OnChange_d_class(c) {
	with (document.all) {
		if (c.value == "B")
			dairiten_kind.style.display = "";
		else {
			document.form1.rep.selectedIndex = 0;
			dairiten_kind.style.display = "none";
		}
		if (c.value == "A") {
			regist_agent1.disabled = false;
			agent_permission1.disabled = false;
			sanka.style.display = "";
		} else {
			regist_agent1.checked = false;
			regist_agent1.disabled = true;
			agent_permission1.disabled = true;
			sanka.style.display = "none";
		}
	}
}
function OnChange_rep(c) {
	if (c.selectedIndex > 0)
		location.href = "eigyou_d1.asp?rep=" + c.value;
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
<form method="post" action="eigyou_d3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">���㗝�X���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">�㗝�X���</td>
						<td class="n3">
							<select name="d_class" onchange="OnChange_d_class(this)">
								<option<%ValueSelected "", d_class%>>- �I�����Ă������� -</option>
								<option<%ValueSelected "A", d_class%>>���b�v</option>
								<option<%ValueSelected "B", d_class%>>���b�v�P���̑㗝�X</option>
								<option<%ValueSelected "C", d_class%>>���̑��̑㗝�X</option>
							</select>
							<span id="dairiten_kind"<%If d_class<>"B" Then Response.Write "style='display:none'"%>>&nbsp;
								���b�v	<select name="rep_id" onchange="OnChange_rep(this)"><%SelectRep rep_id%></select>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="30%">�㗝�X����</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50>
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�㗝�X���́i���ȁj</td>
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
			<td class="m0">�����̑㗝�X�ɋ����錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 1, pm_own, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	<span id="sanka"<%If d_class<>"A" Then Response.Write "style='display:none'"%>>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">���P���̑㗝�X�ɋ����錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 2, pm_agent, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	</span>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">���N���C�A���g�ɋ����錠��</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm_client, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="location.href='eigyou.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
