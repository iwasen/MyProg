<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����������O�폜����
'******************************************************

Dim sql

If Request.QueryString("delete") = "1" Then
	sql = "DELETE T_AUTOLOG"
	ExecuteSQL sql
End If

Response.Redirect("auto_log.asp")
%>
