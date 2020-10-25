<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'**************************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（個人ユーザーサポート:強制一時停止）
'**************************************************************

'=== 状態コード設定 ===
Private Sub SetStatus(member_id, status)
	Dim sql

	sql = "update T_MEMBER set MM_status='" & status & "' where MM_member_id='" & member_id & "'"
	ExecuteSQL sql
End Sub

'=== メイン処理 ===
Dim member_id, status, msg

member_id = Request.Form("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If
status = Request.Form("status")

SetStatus member_id, status

If status = "1" Then
	msg = "メンバーID[" & member_id & "] さんを強制一時停止しました。"
Else
	msg = "メンバーID[" & member_id & "] さんのサービスを再開しました。"
End If

'■タイトル設定
SetHeaderTitle "メンバー個人検索（強制一時停止）"
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
<form>
<p>
<%=msg%>
</p>
<br>
<input type="button" value=" 戻る " onClick="location.href='6-3.asp?id=<%=member_id%>'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
