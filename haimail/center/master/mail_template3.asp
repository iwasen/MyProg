<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:���M���[���e���v���[�g�c�a�X�V����
'******************************************************

SetHeaderTitle "���M���[���e���v���[�g�X�V"

Dim ds, sql, id

id = Request.Form("id")
If Not IsEmpty(id) Then
	sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='" & id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		ds.Edit
		ds("MT_subject").Value = Request.Form("subject")
		ds("MT_from").Value = Request.Form("from")
		ds("MT_body").Value.Trim 0
		ds("MT_body").Value.Write Request.Form("body")
		ds.Update
	End If
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
<p>�X�V���܂����B</p>
<input type="button" value=" �n�j " onclick="location.href='mail_template.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	SystemError "empty id"
End If
%>