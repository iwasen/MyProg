<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者ID統合処理
'******************************************************

SetHeaderTitle "発行者ID統合処理結果", TC_HAKKOUSHA
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

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, RS2, SQL, mel_id, mel_id2, msg

'パラメーラ取得
mel_id = Request.Form("mel_id")
mel_id2 = Request.Form("mel_id2")
If Not IsEmpty(mel_id) and Not IsEmpty(mel_id2) Then
  Set DB = OpenDB()
  DB.BeginTrans
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT MM_point FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    Set RS2 = Server.CreateObject("ADODB.RecordSet")
    SQL = "SELECT MM_point FROM T_MELMEL WHERE MM_mel_id='" & mel_id2 & "'"
    RS2.Open SQL, DB, adOpenKeyset, adLockPessimistic
    If Not RS2.EOF Then
      'ポイント加算
      RS("MM_point") = CDbl(RS("MM_point")) + CDbl(RS2("MM_point"))
      RS.Update

      'メルマガマスタの発行者ID変更
      SQL = "UPDATE T_MELMAG SET MG_mel_id='" & mel_id & "' WHERE MG_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      'ポイント精算ログの発行者ID変更
      SQL = "UPDATE T_SPOINT SET SP_mel_id='" & mel_id & "' WHERE SP_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      'ポイント管理ログの発行者ID変更
      SQL = "UPDATE T_MPOINT SET MP_mel_id='" & mel_id & "' WHERE MP_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      '統合するIDを削除
      RS2.Delete

      msg = "処理は正常に終了しました。"
    Else
      msg = "発行者ID[" & mel_id2 & "]は登録されていません。"
    End If
    RS2.Close
  Else
    msg = "発行者ID[" & mel_id & "]は登録されていません。"
  End If
  RS.Close

  DB.CommitTrans
End If
%>

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onClick="location.href='merge.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
