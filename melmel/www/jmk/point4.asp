<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:ポイント管理削除処理
'******************************************************

Dim DB, RS, SQL, seq_no, mel_id, point

seq_no = Request.QueryString("no")
If Not IsEmpty(seq_no) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  DB.BeginTrans

  SQL = "SELECT * FROM T_MPOINT WHERE MP_seq_no=" & seq_no
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    mel_id = RS("MP_mel_id")
    point = RS("MP_point")
    RS.Delete
    RS.Close

    SQL = "UPDATE T_MELMEL SET MM_point=MM_point-" & point & " WHERE MM_mel_id='" & mel_id & "'"
    DB.Execute SQL
  End If

  DB.CommitTrans

End If

Response.Redirect("point.asp")
%>
