<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���[���Ǘ��^���[�����M���e����
'******************************************************

'=== ���C������ ===
Dim sql, ds, seq_no, subject, from, body

SetHeaderTitle "�N���[���Ή����[�����M"
Response.CacheControl = "no-cache"

seq_no = Request.QueryString("seq")
If IsEmpty(seq_no) Then
	SystemError "empty seq_no"
End If

sql = "SELECT RM_member_id,RM_from,RM_body" & vbCrLf & _
			"FROM T_RECV_MAIL" & vbCrLf & _
			"WHERE RM_seq_no=" & seq_no
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "EOF"
End If

GetSendMailInfo "CLAIM", subject, from, body
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(frm) {
	if (frm.subject.value == "") {
		alert("��������͂��Ă��������B");
		frm.subject.focus();
		return false;
	}
	if (frm.to.value == "") {
		alert("���M��A�h���X����͂��Ă��������B");
		frm.to.focus();
		return false;
	}
	if (frm.body.value == "") {
		alert("���M���e����͂��Ă��������B");
		frm.body.focus();
		return false;
	}
	return confirm("���̃��[���𑗐M���܂��B��낵���ł����H");
}
function OnClick_recv_mail(chk) {
	document.all.show.style.display = (chk ? "" : "none");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="send_mail2.asp" onsubmit="return OnSubmit_form1(this)">
	<input type="hidden" name="seq_no" value="<%=seq_no%>">
	<input type="hidden" name="from" value="<%=HTMLEncode(from)%>">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">���N���[���Ή����[�����e</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">���M�҃����o�[ID</td>
						<td class="n3"><%=ds("RM_member_id").Value%></td>
					</tr>
					<tr>
						<td class="m3" width="22%">����</td>
						<td class="n3">
							<input type="text" name="subject" size=70 value="<%=HTMLEncode(subject)%>">
							<font class="note">�i�S�p�P�O�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���M��A�h���X</td>
						<td class="n3">
							<input type="text" name="to" size=70 value="<%=HTMLEncode(ds("RM_from").Value)%>">
							<font class="note">�i���p�P�O�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���M���e</td>
						<td class="n3"><textarea name="body" cols=78 rows=25><%=body%></textarea></td>
					</tr>
					<tr>
						<td class="m3">��M���e</td>
						<td class="n3">
							<input type="checkbox" onclick="OnClick_recv_mail(checked)">�\������
							<div id="show" style="display:none">
								<textarea cols=78 rows=25 readonly><%=ReadCLOB(ds("RM_body").Value)%></textarea>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" ���M ">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
