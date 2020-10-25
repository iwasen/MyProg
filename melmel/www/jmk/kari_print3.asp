<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :‚ß‚é‚ß‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:‰¼“o˜^ƒƒ‹ƒ}ƒKˆóüÏ‚ÝÝ’èˆ—
'******************************************************

Dim DB, SQL, param_seq_no, seq_no, first

Set param_seq_no = Request.Form("seq_no")
If Not IsEmpty(param_seq_no.Item) Then
  Set DB = OpenDB()

  SQL = "UPDATE T_KMAILLOG SET KM_print_flag=1 WHERE KM_seq_no IN ("
  first = true
  For Each seq_no In param_seq_no
    If first Then
      first = False
    Else
      SQL = SQL + ","
    End If
    SQL = SQL & seq_no
  Next
  SQL = SQL + ")"

  If first = False Then
    DB.Execute SQL
  End If
End If

Response.Redirect("index.asp")
%>
