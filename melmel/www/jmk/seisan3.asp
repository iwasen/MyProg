<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���Z�ςݐݒ菈��
'******************************************************

Dim DB, SQL, param_seq_no, seq_no, first

Set param_seq_no = Request.Form("seq_no")
If Not IsEmpty(param_seq_no.Item) Then
  Set DB = OpenDB()

  SQL = "UPDATE T_SPOINT SET SP_file=1 WHERE SP_seq_no IN ("
  first = true
  For Each seq_no In param_seq_no
    If first Then
      first = False
    Else
      SQL = SQL + ","
    End If
    SQL = SQL & "'" & seq_no & "'"
  Next
  SQL = SQL + ")"

  If first = False Then
    DB.Execute SQL
  End If
End If

Response.Redirect("seisan.asp")
%>
