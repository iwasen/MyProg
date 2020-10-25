<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="approve_sub.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:ジョブ承認要求画面処理
'******************************************************

'=== 承認要求処理 ===
Private Sub ApproveRequest(job_id)
	Dim sql

	sql = "UPDATE T_JOB SET JB_status='1' WHERE JB_job_id=" & job_id & " AND JB_status='0'"
	ExecuteSQL sql
End Sub

'=== メイン処理 ===
Dim job_id

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

'変更期限チェック
'If system_id = "sales" Then
'	If Not LimitCheck(job_id) Then
'		Response.Redirect "approve_err.asp?job_id=" & job_id
'	End If
'End If

ApproveRequest job_id

SetHeaderTitle "承認要求"
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
<p>ジョブID：<%=job_id%> の承認を要求しました。</p>
<input type="button" value=" OK " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
