<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:仮登録審査結果処理
'******************************************************

Dim DB, SQL, RS, seq_no, ok, pwd
Dim subject, mailfrom, mailto, body, result

'パラメーラ取得
seq_no = CLng(Request.QueryString("id"))
ok = Request.QueryString("ok")

'仮登録データオープン
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_PROVREG WHERE PR_seq_no=" & seq_no
RS.Open SQL, DB, adOpenKeyset, adLockPessimistic

If Not RS.EOF Then
  If ok = "1" Then
    '審査OKの場合
    pwd = GenaratePassword()
    GetSendMailInfo "REGOK", subject, mailfrom, body
    mailto = RS("PR_mail_adr")
    body = Replace(body, "%MAGAZINE%", RS("PR_m_name"))
    body = Replace(body, "%HAKKOUSHA%", RS("PR_h_name"))
    body = Replace(body, "%ID%", Request.QueryString("id"))
    body = Replace(body, "%PASSWORD%", pwd)

    RS("PR_password") = pwd
    RS("PR_status") = 1
  Else
    '審査NGの場合
    GetSendMailInfo "REGNG", subject, mailfrom, body
    mailto = RS("PR_mail_adr")
    body = Replace(body, "%MAGAZINE%", RS("PR_m_name"))
    body = Replace(body, "%HAKKOUSHA%", RS("PR_h_name"))

    RS("PR_status") = 3
  End If

  'メール送信
  If Request.QueryString("nomail") = "1" Then
    RS.Update
  Else
    result = SendMail(mailto, mailfrom, subject, body)
    If result = "" Then
      'DB更新
      RS.Update
    Else
      RS.CancelUpdate
      ShowErrorMessage "メール送信に失敗しました。[" & result & "]"
    End If
  End If
End If

RS.Close

Response.Redirect("shinsa.asp")

'パスワード作成
Function GenaratePassword()
	Dim pwd, pch1, pch2, pch0
	Randomize
  pwd = ""
	Do While Len(pwd) < 8
	  pch1 = Int(Rnd * 100)
		pch2 = Int(Rnd * 10)
		pch0 = pch1 + pch2
		If (pch0 >= 48 And pch0 <= 57) Or (pch0 >= 65 And pch0 <= 90) Or (pch0 >= 97 And pch0 <= 122) Then
			pwd = pwd & Chr(pch0)
		End If
	Loop
  GenaratePassword = pwd
End Function
%>
