<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'**************************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（個人ユーザーサポート:強制一時停止）
'**************************************************************

'=== 状態取得 ===
Private Sub GetStatus(member_id)
	Dim sql, ds

	sql = "select MM_status from T_MEMBER where MM_member_id='" & member_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		status = ds(0).Value
	End If
End Sub

'=== メイン処理 ===
Dim member_id, status, msg, new_status

member_id = Request.QueryString("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If

GetStatus member_id

Select Case status
Case "0"
	msg = "このメンバーは現在有効状態です。一時停止させますか？"
	new_status = "1"
Case "1"
	msg = "このメンバーは現在一時停止中です。サービスを再開しますか？"
	new_status = "0"
Case "9"
	msg = "このメンバーは現在退会しています。"
	new_status = ""
End Select

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
<form method="post" action="6e-6.asp" name="form1">
	<p>
	<%=msg%>
	<input type="hidden" name="id" value="<%=member_id%>">
	<input type="hidden" name="status" value="<%=new_status%>">
	</p>
	<br>
<%If new_status <> "" Then%>
	<input type="submit" value=" 更新 ">
<%End If%>
	<input type="button" value=" 戻る " onClick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
