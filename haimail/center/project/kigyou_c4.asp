<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��ƕ�폜����
'******************************************************

SetHeaderTitle "��Ə��폜"

Dim sql, company_id

company_id = Request.QueryString("id")
If IsEmpty(company_id) Then
	SystemError "empty company_id"
End If

BeginTrans
sql = "DELETE T_COMPANY WHERE CM_company_id='" & company_id & "'"
ExecuteSQL(sql)
sql = "DELETE T_TANTOUSHA WHERE TS_company_id='" & company_id & "'"
ExecuteSQL(sql)
CommitTrans
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>�폜���܂����B</p>
<input type="button" value=" �n�j " onclick="location.href='kigyou.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
