<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�^�G���[���[���������
'******************************************************

SetHeaderTitle "�o�^�G���[���[������"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_center() {
	if (!confirm("���̃��[���������Ǐ����Ƃ��܂��B��낵���ł����H"))
		return;
	document.form1.process.value = "1";
	document.form1.submit();
}
function OnClick_reply() {
	if (!confirm("���̃��[���𑗐M�҂ɕԐM���܂��B��낵���ł����H"))
		return;
	document.form1.process.value = "2";
	document.form1.submit();
}
function OnClick_cancel() {
	if (!confirm("���̃��[����j�����܂��B��낵���ł����H"))
		return;
	document.form1.process.value = "3";
	document.form1.submit();
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

sql = "SELECT *" & vbCrLf & _
			"FROM T_TOUROKU_MAIL,T_TOUROKU_ERR" & vbCrLf & _
			"WHERE TE_seq_no=" & id & " AND TM_seq_no=TE_seq_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="error_mail3.asp" name="form1" onSubmit="return false">
	<input type="hidden" name="seq_no" value="<%=ds("TE_seq_no").Value%>">
	<input type="hidden" name="process">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">���o�^�G���[���[�� <font size="-1">�i�G���[���[���̓��e���m�F���A�K�v�Ȃ�ΏC�����Ă��������j</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3"><%=ReplaceTag(ds("TM_subject").Value)%></td>
					</tr>
					<tr>
						<td class="m3">���M�҃A�h���X</td>
						<td class="n3"><%=ReplaceTag(ds("TM_from").Value)%></td>
					</tr>
					<tr>
						<td class="m3">�{��</td>
						<td class="n3"><textarea cols=80 rows=30 name="body"><%=ReadCLOB(ds("TE_reply").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="center" value="�����Ǐ���" onclick="OnClick_center()">
	<input type="button" name="sender" value="���M�҂ɕԐM" onclick="OnClick_reply()">
	<input type="button" name="cancel" value=" �j�� " onclick="OnClick_cancel()">
	<input type="button" name="original" value="�����\��" onclick="window.open('error_mail4.asp?no=<%=ds("TE_seq_no").Value%>','error_mail4')">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
