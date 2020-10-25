<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:仮登録メルマガ印刷処理
'******************************************************

SetHeaderTitle "仮登録メルマガ印刷", TC_HAKKOUSHA
%>
<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<LINK rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function PagePrint() {
  if (confirm("印刷しますか？")) {
    print();
    if (confirm("このメルマガを印刷済みにマークしますか？\n（マークすると次回は印刷対象になりません）")) {
      form1.submit();
    }
  }
}
//-->
</SCRIPT>
</HEAD>
<body onLoad="PagePrint();">

<%
Dim DB, RS, SQL, param_seq_no, seq_no, first

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

first = True
Set param_seq_no = Request.Form("seq_no")

If Not IsEmpty(param_seq_no.Item) Then
  SQL = "SELECT * FROM T_KMAILLOG WHERE KM_seq_no IN ("
  first = true
  For Each seq_no In param_seq_no
    If first Then
      first = False
    Else
      SQL = SQL + ","
    End If
    SQL = SQL & seq_no
  Next
  SQL = SQL + ") ORDER BY KM_subject,KM_seq_no"

  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  first = True
  Do Until RS.EOF
    If first Then
      Response.Write "<div>" & vbCrLf
      first = False
    Else
      Response.Write "<div class='page'>" & vbCrLf
    End If
    Writeln "<table class='print' border=0 cellspacing=0 cellpadding=0>"
    Writeln "<tr><td>Subject:</td><td>" & ReplaceTag(RS("KM_subject")) & "</td></tr>"
    Writeln "<tr><td>From:</td><td>" & ReplaceTag(RS("KM_from")) & "</td></tr>"
    Writeln "<tr><td>Date:</td><td>" & RS("KM_date") & "</td></tr>"
    Writeln "</table>"
    Writeln "<br><xmp class='print'>" & ReplaceXmp(RS("KM_body")) & "</xmp>"
    Writeln "<hr class='print'>"
    Writeln "</div>"
    RS.MoveNext
  Loop

  RS.Close
End If

Writeln "<form method='post' name='form1' action='kari_print3.asp'>"
For Each seq_no In param_seq_no
  Writeln "<input type='hidden' name='seq_no' value='" & seq_no & "'>"
Next
Writeln "</form>"
%>

</body>
</html>
