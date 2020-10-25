<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:ランダムアウトプット印刷処理
'******************************************************

SetHeaderTitle "ランダムアウトプット", TC_HAKKOUSHA
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
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
</head>
<body onLoad="PagePrint();">

<%
Dim DB, RS, SQL, busuu, start_date, end_date, seq_no, count, i, j, t, first

'パラメータ（印刷部数）取得
busuu = Request.Form("busuu")
If IsEmpty(busuu) Then
  Response.Redirect("random_output.asp")
End If
busuu = CLng(busuu)

'パラメータ（開始日付）取得
start_date = Request.Form("start_date")
If IsEmpty(start_date) Or Not IsDate(start_date) Then
  Response.Redirect("random_output.asp")
End If

'パラメータ（終了日付）取得
end_date = Request.Form("end_date")
If IsEmpty(end_date) Or Not IsDate(end_date) Then
  Response.Redirect("random_output.asp")
End If

'本登録メルマガログオープン
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT HM_seq_no FROM T_HMAILLOG WHERE HM_print_flag=0 AND HM_date BETWEEN '" & start_date & "' AND '" & end_date & "'"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly

'シーケンス番号をテーブルに取得
count = RS.RecordCount
ReDim seq_no(count)
i = 0
Do Until RS.EOF
  seq_no(i) = RS(0)
  i = i + 1
  RS.MoveNext
Loop
RS.Close

'ランダムに並べ替え
Randomize
For i = count - 1 to 1 step -1
  j = Int(rnd * (i + 1))
  t = seq_no(i)
  seq_no(i) = seq_no(j)
  seq_no(j) = t
Next

'指定された部数だけ印刷
If busuu > count Then
  busuu = count
End If

'ランダムに選択したログを取得
SQL = "SELECT * FROM T_HMAILLOG WHERE HM_seq_no IN ("
For i = 0 to busuu - 1
  If i = 0 then
    SQL = SQL & seq_no(i)
  Else
    SQL = SQL & "," & seq_no(i)
  End If
Next
SQL = SQL & ") ORDER BY HM_seq_no"
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
  Writeln "<tr><td>メルマガID:</td><td>" & RS("HM_mag_id") & "</td></tr>"
  Writeln "<tr><td>Subject:</td><td>" & ReplaceTag(RS("HM_subject")) & "</td></tr>"
  Writeln "<tr><td>From:</td><td>" & ReplaceTag(RS("HM_from")) & "</td></tr>"
  Writeln "<tr><td>Date:</td><td>" & RS("HM_date") & "</td></tr>"
  Writeln "</table>"
  Writeln "<br><xmp class='mail'>" & ReplaceXmp(RS("HM_body")) & "</xmp>"
  Writeln "<hr class='print'>"
  Writeln "</div>"

  RS.MoveNext
Loop

Writeln "<form method='post' name='form1' action='random_output3.asp'>"
For i = 0 to busuu - 1
  Writeln "<input type='hidden' name='seq_no' value='" & seq_no(i) & "'>"
Next
Writeln "</form>"

%>

</body>
</html>
