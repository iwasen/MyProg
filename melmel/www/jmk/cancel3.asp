<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:本登録解除処理
'******************************************************

SetHeaderTitle "本登録解除結果", TC_HAKKOUSHA
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim mag_id, msg

'パラメーラ取得
mag_id = Request.Form("mag_id")
If Not IsEmpty(mag_id) Then
  '解約処理
  If Kaiyaku(mag_id) Then
    msg = "指定したメルマガの登録を解除しました。"
  Else
    msg = "解除通知メールの送信に失敗しました。"
  End If
End If
%>

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onClick="location.href='cancel.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
