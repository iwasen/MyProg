<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���[���Ǘ��^�L�[���[�h�X�V
'******************************************************

'=== ���C������ ===
Dim ds, sql, keyword_id

SetHeaderTitle "�N���[���L�[���[�h�X�V"
Response.CacheControl = "no-cache"

keyword_id = Request.Form("keyword_id")
If IsEmpty(keyword_id) Then
	SystemError "empty keyword_id"
End If

sql = "SELECT * FROM T_CLAIM_KEYWORD WHERE CK_keyword_id='" & keyword_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("CK_keyword_id").Value = keyword_id
Else
	ds.Edit
End If
ds("CK_keyword").Value = Request.Form("keyword")
ds.Update
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
<input type="button" value=" �n�j " onclick="location.href='keyword.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
