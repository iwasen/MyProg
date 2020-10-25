<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�N�J�E���^�o�^��ʏ���
'******************************************************

'=== �N���b�N�J�E���^���擾 ===
Private Sub ReadClickCount(ct_id)
	Dim sql, ds

	sql = "SELECT CC_url_name,CC_start_date,CC_end_date,CC_jump_url FROM T_CLICK_COUNT WHERE CC_ct_id=" & ct_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		url_name = ds("CC_url_name").Value
		start_date = ds("CC_start_date").Value
		end_date = ds("CC_end_date").Value
		jump_url = ds("CC_jump_url").Value
	End If
End Sub

'=== ���C������ ===
Dim job_id, ct_id, title, url_name, jump_url, start_date, end_date
Dim start_date_y, start_date_m, start_date_d, end_date_y, end_date_m, end_date_d

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ct_id = Request.QueryString("ct_id")
If IsEmpty(ct_id) Then
	title = "�N���b�N�J�E���^�V�K�쐬"
Else
	title = "�N���b�N�J�E���^�ύX"
	ReadClickCount ct_id

	If Not IsNull(start_date) Then
		start_date_y = Year(start_date)
		start_date_m = Month(start_date)
		start_date_d = Day(start_date)
	End If

	If Not IsNull(end_date) Then
		end_date_y = Year(end_date)
		end_date_m = Month(end_date)
		end_date_d = Day(end_date)
	End If
End If

SetHeaderTitle title
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
	document.form1.url_name.focus();
}
function OnSubmit_form1() {
	if (document.form1.action.value == "")
		return false;
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("�N���b�N�J�E���^��o�^���܂��B��낵���ł����H")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("�N���b�N�J�E���^���X�V���܂��B��낵���ł����H")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (confirm("�N���b�N�J�E���^���폜���܂��B��낵���ł����H")) {
		document.form1.action.value = "delete";
		document.form1.submit();
	}
}
function InputCheck() {
	if (document.form1.url_name.value == "") {
		alert("URL���̂���͂��Ă��������B");
		document.form1.url_name.focus();
		return false;
	}
	if (document.form1.jump_url.value == "") {
		alert("��ѐ�URL����͂��Ă��������B");
		document.form1.jump_url.focus();
		return false;
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1" action="click_count3.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<input type="hidden" name="ct_id" value="<%=ct_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���N���b�N�J�E���^������͂��Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%
If Not IsEmpty(ct_id) Then
%>
					<tr>
						<td class="m3" width="25%">CTID</td>
						<td class="n3"><%=ct_id%></td>
					</tr>
<%
End If
%>
					<tr>
						<td class="m3" width="25%">URL����</td>
						<td class="n3"><input type="text" name="url_name" size=80 maxlength=100 value="<%=HTMLEncode(url_name)%>"></td>
					</tr>
					<tr>
						<td class="m3" width="25%">��ѐ�URL</td>
						<td class="n3">
							<input type="text" name="jump_url" size=80 maxlength=200 value="<%=HTMLEncode(jump_url)%>">
							<input type="button" value="�\��" onclick="window.open(document.form1.jump_url.value)">
						</td>
					</tr>
					<tr>
						<td class="m3">�J�E���^����(�J�n)</td>
						<td class="n3">
							<select name="start_date_y"><%SelectYear2 start_date_y%></select>�N&nbsp;
							<select name="start_date_m"><%SelectMonth start_date_m%></select>��&nbsp;
							<select name="start_date_d"><%SelectDay start_date_d%></select>��&nbsp;<font class="note">�i�J�n���w�肵�Ȃ��ꍇ�͔N���u�����N�ɂ��Ă��������j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�J�E���^����(�I��)</td>
						<td class="n3">
							<select name="end_date_y"><%SelectYear2 end_date_y%></select>�N&nbsp;
							<select name="end_date_m"><%SelectMonth end_date_m%></select>��&nbsp;
							<select name="end_date_d"><%SelectDay end_date_d%></select>��&nbsp;<font class="note">�i�I�����w�肵�Ȃ��ꍇ�͔N���u�����N�ɂ��Ă��������j</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%
If IsEmpty(ct_id) Then
%>
	<input type="button" name="regist" value=" �o�^ " onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" �X�V " onclick="OnClick_update()">
	<input type="button" name="delete" value=" �폜 " onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
	<input type="hidden" name="action">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
