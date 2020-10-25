<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���[���Ǘ��^�N���[������
'******************************************************

'=== ���C������ ===
Dim sql, ds, seq_no

SetHeaderTitle "�N���[���Ή�����"
Response.CacheControl = "no-cache"

seq_no = Request.QueryString("seq")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

sql = "SELECT CA_status,RM_subject,RM_member_id,RM_from,RM_body" & vbCrLf & _
			"FROM T_RECV_MAIL,T_CLAIM_MAIL" & vbCrLf & _
			"WHERE CA_seq_no=" & seq_no & " AND RM_seq_no=CA_seq_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "EOF"
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_send() {
	location.href = "send_mail.asp?seq=<%=seq_no%>";
}
function OnClick_nopro() {
	if (confirm("���̃��[������Ȃ��Ƃ��܂��B��낵���ł����H"))
		location.href = "set_status.asp?seq=<%=seq_no%>&status=2";
}
function OnClick_cancel() {
	if (confirm("���̃��[����j�����܂��B��낵���ł����H"))
		location.href = "set_status.asp?seq=<%=seq_no%>&status=3";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form>
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">���N���[�����[�����</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">����</td>
						<td class="n3"><%=ds("RM_subject").Value%></td>
					</tr>
					<tr>
						<td class="m3">���M�҃����o�[ID</td>
						<td class="n3"><%=ds("RM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">���M�҃A�h���X</td>
						<td class="n3"><%=ReplaceTag(ds("RM_from").Value)%></td>
					</tr>
					<tr>
						<td class="m3">�ԐM���e</td>
						<td class="n3"><textarea cols=78 rows=30 readonly><%=ReadCLOB(ds("RM_body").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If ds("CA_status").Value = "0" Then%>
	<input type="button" value="���[�����M" onclick="OnClick_send()">
	<input type="button" value="���Ȃ�" onclick="OnClick_nopro()">
	<input type="button" value="�@�j���@" onclick="OnClick_cancel()">
<%End If%>
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
