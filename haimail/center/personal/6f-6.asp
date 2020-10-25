<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'**************************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（個人ユーザーサポート:強制退会）
'**************************************************************

'=== 状態コード設定 ===
Private Sub SetStatus(member_id, status)
	Dim sql

	sql = "update T_MEMBER set MM_status='" & status & "' where MM_member_id='" & member_id & "'"
	ExecuteSQL sql
End Sub

'=== 退会理由設定 ===
Private Sub SetTaikaiRiyuu(member_id)
	Dim sql

	sql = "insert into T_TAIKAI (TK_member_id,TK_taikai_riyuu,TK_taikai_date) values ('" & member_id & "','はいめーるセンターによる強制退会',SYSDATE)"
	ExecuteSQL sql
End Sub

'=== 退会理由削除 ===
Private Sub DeleteTaikaiRiyuu(member_id)
	Dim sql

	sql = "delete T_TAIKAI where TK_member_id='" & member_id & "'"
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

If status = "9" Then
	SetTaikaiRiyuu member_id
	msg = "メンバーID[" & member_id & "] さんを強制退会させました。"
Else
	DeleteTaikaiRiyuu member_id
	msg = "メンバーID[" & member_id & "] さんのサービスを再開しました。"
End If

'■タイトル設定
SetHeaderTitle "メンバー個人検索（強制退会）"
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
