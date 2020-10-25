<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:ポイント管理登録処理
'******************************************************

Dim DB, RS, SQL, mel_id, point

mel_id = Request.Form("mel_id")
point = Request.Form("point")
If Not IsEmpty(mel_id) And IsNumeric(point) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  DB.BeginTrans

  SQL = "SELECT * FROM T_MPOINT WHERE 0=1"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  RS.AddNew
  RS("MP_mel_id") = mel_id
  RS("MP_point") = Request.Form("point")
  RS("MP_comment") = Request.Form("comment")
  RS("MP_date") = Now()
  RS.Update
  RS.Close

  SQL = "UPDATE T_MELMEL SET MM_point=MM_point+" & point & " WHERE MM_mel_id='" & mel_id & "'"
  DB.Execute SQL

  DB.CommitTrans

End If

Response.Redirect("point.asp")
%>
