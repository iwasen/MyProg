<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載状況チェック判定結果設定処理
'******************************************************

Dim DB, RS, SQL, rc, i, hantei, seq_no, index_no, check_st, flag, mag_id, koukoku_id, point

Set DB = OpenDB()

rc = Request.Form("rc")
If Not IsEmpty(rc) Then
  rc = CLng(rc)

  For i = 1 To rc
    hantei = Request.Form("hantei" & i)
    seq_no = Request.Form("seq_no" & i)
    index_no = Request.Form("index_no" & i)

    If hantei = "ok" Then
      check_st = 1
      flag = True
    ElseIf hantei = "ng" Then
      check_st = 2
      flag = True
    Else
      flag = False
    End If

    If flag Then
      'チェック状態を設定
      Set RS = Server.CreateObject("ADODB.RecordSet")
      SQL = "SELECT HD_mag_id,HD_koukoku_id,HD_check_status FROM T_HMAILDAT WHERE HD_seq_no=" & seq_no & " AND HD_index_no=" & index_no
      RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
      If Not RS.EOF Then
        mag_id = RS("HD_mag_id")
        koukoku_id = RS("HD_koukoku_id")
        RS("HD_check_status") = check_st
        RS.Update
        RS.Close

        '実績ポイントを再計算するために回数にNULLをセット（自動処理で再計算される）
        SQL = "UPDATE T_JPOINT SET JP_kaisuu=NULL WHERE JP_mag_id='" & mag_id & "' AND JP_koukoku_id='" & koukoku_id & "'"
        DB.Execute SQL
      End If
    End If
  Next
End If

Response.Redirect("keisai_check.asp")
%>
