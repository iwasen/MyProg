<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:���M���[���e���v���[�g�C�����
'******************************************************

SetHeaderTitle "���M���[���e���v���[�g�C��"
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
		if (subject.value == "") {
			alert("��������͂��Ă��������B");
			subject.focus();
			return false;
		}
		if (from.value == "") {
			alert("���M���A�h���X����͂��Ă��������B");
			from.focus();
			return false;
		}
		if (body.value == "") {
			alert("�{������͂��Ă��������B");
			body.focus();
			return false;
		}
	}
	return confirm("���M���[���e���v���[�g���X�V���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
Dim ds, sql, id

id = Request.QueryString("id")
If IsEmpty(id) Then
	SystemError "empty id"
End If

sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='" & id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="mail_template3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="id" value="<%=ds("MT_id").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">�����[����� <font size="-1">�i"%"�ň͂܂ꂽ�����̓v���O�����Ńf�[�^�𖄂ߍ��ނ̂ŁA�ύX���Ȃ��ł��������j</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">���</td>
						<td class="n3"><%=ds("MT_guide").Value%></td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3">
							<input type="text" name="subject" size=80 maxlength=100 value="<%=HTMLEncode(ds("MT_subject").Value)%>">
							<font class="note">�i�S�p�P�O�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���M���A�h���X</td>
						<td class="n3">
							<input type="text" name="from" size=80 maxlength=200 value="<%=HTMLEncode(ds("MT_from").Value)%>">
							<font class="note">�i���p�Q�O�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�{��</td>
						<td class="n3"><textarea cols=80 rows=30 name="body" class="np"><%=ReadCLOB(ds("MT_body").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="submit" value=" �X�V ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
