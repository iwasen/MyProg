<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者削除処理
'******************************************************

SetHeaderTitle "発行者削除", TC_MASTER

Dim DB, SQL, mel_id

mel_id = Request.QueryString("id")
If Not IsEmpty(mel_id) Then
  Set DB = OpenDB()
  SQL = "DELETE T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
  DB.Execute SQL
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<TITLE><%=title_text%></TITLE>
<LINK rel="stylesheet" type="text/css" href="admin.css">
</HEAD>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<p>
削除しました。
</p>
<input type="button" value=" ＯＫ " onClick="location.href='mel_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("mel_master.asp")
End If
%>