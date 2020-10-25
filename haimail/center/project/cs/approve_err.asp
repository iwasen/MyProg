<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:ジョブ承認期限エラー画面処理
'******************************************************

Dim job_id, text

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

SetHeaderTitle "ジョブ承認要求エラー"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<p>ジョブの承認要求はメール発信開始日の４８時間以内には行えません。</p>
<input type="button" value=" OK " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
