<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/point.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（送受信履歴）
'******************************************************

'=== MailID取得 ===
Private Function GetMailId(job_id)
	Dim sql, ds

	sql = "select JB_mail_id from T_JOB where JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetMailId = ds(0).value
	End If
End Function

'=== メイン処理 ===
Dim member_id, job_id, point, mail_id
Dim sql, ds

member_id = Request.Form("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If

job_id = Request.Form("job_id")
point = Request.Form("point")

If Not IsEmpty(job_id) Then
	mail_id = GetMailId(job_id)
End If

AddPoint member_id, "13", point, mail_id

'■タイトル設定
SetHeaderTitle "メンバー個人検索（手動ポイント加算）"
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
<p>手動ポイントを加算しました。</p>
<input type="button" value="　戻る　" onclick="location.href='6b-4.asp?id=<%=member_id%>'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
