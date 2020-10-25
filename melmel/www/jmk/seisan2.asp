<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:CSV形式表示
'******************************************************

SetHeaderTitle "精算要求リストCSV出力", TC_HAKKOUSHA
Response.CacheControl = "no-cache"

Dim param_seq_no

Set param_seq_no = Request.Form("seq_no")
If IsEmpty(param_seq_no.Item) Then
  Response.Redirect("seisan.asp")
End If

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
function OnSubmit_form2() {
  if (confirm("ここに表示されているデータを精算処理済に設定します。よろしいですか？"))
    return true;
  else
    return false;
}
//-->
</SCRIPT>
</HEAD>
<body onLoad="document.form1.csv.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<form name="form1">
<center>
<textarea cols=100 rows=30 name="csv" readonly><%
Dim DB, RS, SQL, seq_no, first

Set DB = OpenDB()

SQL = "SELECT SP_seq_no,SP_mel_id,MM_h_name,MM_mail_adr,SP_cur_point,SP_req_date,SP_req_point,SP_file,SP_seisan" & vbCrLf & _
      "FROM T_SPOINT INNER JOIN T_MELMEL ON SP_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE SP_seq_no IN ("
first = true
For Each seq_no In param_seq_no
  If first Then
    first = False
  Else
    SQL = SQL + ","
  End If
  SQL = SQL & "'" & seq_no & "'"
Next
SQL = SQL + ") ORDER BY SP_seq_no"

If first = False Then
  Set RS = Server.CreateObject("ADODB.RecordSet")
  RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
  Do Until RS.EOF
    Response.Write "0,"
    Response.Write RS("SP_mel_id") & ","
    Response.Write """" & RS("MM_h_name") & ""","
    Response.Write """" & RS("MM_mail_adr") & ""","
    Response.Write RS("SP_cur_point") & ","
    Response.Write RS("SP_req_date") & ","
    Response.Write RS("SP_req_point") & ","
    Response.Write RS("SP_file") & ","
    Response.Write RS("SP_seisan") & vbCrLf

    RS.MoveNext
  Loop
  RS.close
End If
%></textarea>
</center>
</form>

<form method="post" name="form2" action="seisan3.asp" onSubmit="return OnSubmit_form2();">
<%
For Each seq_no In param_seq_no
  Writeln "<input type='hidden' name='seq_no' value='" & seq_no & "'>"
Next
%>
<center>
<input type="submit" value="精算処理済み">
</center>
</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
