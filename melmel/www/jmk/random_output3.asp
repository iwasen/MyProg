<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�����_���A�E�g�v�b�g����ςݐݒ菈��
'******************************************************

Dim DB, SQL, param_seq_no, seq_no, first

Set param_seq_no = Request.Form("seq_no")
If Not IsEmpty(param_seq_no.Item) Then
  Set DB = OpenDB()

  SQL = "UPDATE T_HMAILLOG SET HM_print_flag=1 WHERE HM_seq_no IN ("
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
  DB.Execute SQL
End If

Response.Redirect("index.asp")
%>
