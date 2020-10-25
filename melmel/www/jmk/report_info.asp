<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載報告書内容確認画面
'******************************************************

SetHeaderTitle "掲載報告書内容確認", TC_KOUKOKU
Response.CacheControl = "no-cache"
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

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT *" & vbCrLf & _
      "FROM T_REPORTLOG" & vbCrLf & _
      "WHERE RP_seq_no=" & Request.QueryString("seq")
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If Not RS.EOF Then
%>
<center>
<form>
  <table border=0 cellspacing=2 cellpadding=3 width='100%'>
    <tr>
      <td class="m2" width="15%">Subject</td>
      <td class="n2"><%=ReplaceTag(RS("RP_subject"))%></td>
    </tr>
    <tr>
      <td class="m2">From</td>
      <td class="n2"><%=ReplaceTag(RS("RP_from"))%></td>
    </tr>
    <tr>
      <td class="m2">Date</td>
      <td class="n2"><%=RS("RP_Date")%></td>
    </tr>
    <tr>
      <td class="m2">報告内容</td>
      <td class="n2"><textarea cols=80 rows=25 readonly class="np"><%=RS("RP_body")%></textarea></td>
    </tr>
  </table>
</form>
</center>
<%
End If
RS.Close
%>
</body>
</html>
