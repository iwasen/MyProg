<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�A���P�[�g�G���[���[���������
'******************************************************

SetHeaderTitle "�A���P�[�g�G���[���[������"

Dim ds, sql, seq_no

seq_no = Request.QueryString("seq_no")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

sql = "SELECT MM_member_id,MM_mail_adr,RM_subject,EE_seq_no,EE_body" & vbCrLf & _
			"FROM T_MEMBER,T_RECV_MAIL,T_ENQUETE_ERR" & vbCrLf & _
			"WHERE EE_seq_no=" & seq_no & " AND RM_seq_no=EE_seq_no AND MM_member_id=RM_member_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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
	if (!confirm("���̃��[���������Ǐ������܂��B��낵���ł����H"))
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
	if (!confirm("���̃��[���𖳏����Ƃ��܂��B��낵���ł����H"))
		return;
	document.form1.process.value = "3";
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="enq_error3.asp" name="form1" onSubmit="return false">
	<input type="hidden" name="seq_no" value="<%=ds("EE_seq_no").Value%>">
	<input type="hidden" name="process">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">���A���P�[�g�G���[���[�� <font size="-1">�i�G���[���[���̓��e���m�F���A�K�v�Ȃ�ΏC�����Ă��������j</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">�����o�[ID</td>
						<td class="n3"><%=ds("MM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">���[���A�h���X</td>
						<td class="n3"><%=ReplaceTag(ds("MM_mail_adr").Value)%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3"><%=ReplaceTag(ds("RM_subject").Value)%></td>
					</tr>
					<tr>
						<td class="m3">�{��</td>
						<td class="n3"><textarea cols=80 rows=30 name="body"><%=ReadCLOB(ds("EE_body").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="center" value="�����Ǐ���" onclick="OnClick_center()">
	<input type="button" name="sender" value="���M�҂ɕԐM" onclick="OnClick_reply()">
	<input type="button" name="cancel" value=" ������ " onclick="OnClick_cancel()">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
