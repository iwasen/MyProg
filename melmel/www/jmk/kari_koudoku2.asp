<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���o�^�����}�K�w�Ǎςݐݒ菈��
'******************************************************

Dim DB, SQL, seq_no

'�p�����[���擾
seq_no = Request.QueryString("id")

If Not IsEmpty(seq_no) Then
  Set DB = OpenDB()
  SQL = "UPDATE T_PROVREG SET PR_koudoku_flag=1 WHERE PR_seq_no=" & CLng(seq_no)
  DB.Execute SQL
End If

Response.Redirect("kari_koudoku.asp")
%>
