<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガ削除処理
'******************************************************

SetHeaderTitle "メルマガ削除", TC_MASTER

Dim DB, SQL, mag_id

mag_id = Request.QueryString("id")
If Not IsEmpty(mag_id) Then
  Set DB = OpenDB()
  SQL = "DELETE T_MELMAG WHERE MG_mag_id='" & mag_id & "'"
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
<input type="button" value=" ＯＫ " onClick="location.href='mag_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("mag_master.asp")
End If
%>