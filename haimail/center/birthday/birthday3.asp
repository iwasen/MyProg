<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�a�������[���폜����
'******************************************************

'=== ���C������ ===
Dim sql, ds
Dim seq_no

seq_no = Request.QueryString("id")
If IsEmpty(seq_no) Then
	Response.Redirect "index.asp"
End If

sql = "DELETE T_BIRTHDAY_MAIL WHERE BM_seq_no=" & seq_no
ExecuteSQL sql

Response.Redirect "index.asp"
%>
