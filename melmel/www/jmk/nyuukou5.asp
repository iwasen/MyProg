<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:広告削除処理
'******************************************************

SetHeaderTitle "広告削除", TC_KOUKOKU

Dim DB, SQL, koukoku_id

koukoku_id = Request.QueryString("id")
If Not IsEmpty(koukoku_id) Then
  Set DB = OpenDB()
  SQL = "DELETE V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
  DB.Execute SQL
  SQL = "DELETE T_KEISAI WHERE KS_koukoku_id='" & koukoku_id & "'"
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
<input type="button" value=" ＯＫ " onClick="location.href='nyuukou.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("index.asp")
End If
%>