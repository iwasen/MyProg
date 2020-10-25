<%
'=== ログインチェック ===
Function LoginCheck(login_type, login_id, password, super)
	Dim sql, ds

	If super = "1" Then
		Response.Cookies("login") = "S" & login_type & login_id
		LoginLog login_type, login_id, "super", "2"
		LoginCheck = True
		Exit Function
	End If

	Select Case login_type
	Case LOGIN_CENTER
		sql = "SELECT CN_user_id" & vbCrLf & _
					"FROM T_CENTER" & vbCrLf & _
					"WHERE CN_user_id='" & login_id & "' AND CN_password='" & password & "'"
	Case LOGIN_AGENT
		sql = "SELECT OP_operator_id" & vbCrLf & _
					"FROM T_OPERATOR" & vbCrLf & _
					"WHERE OP_operator_id='" & login_id & "' AND OP_password='" & password & "'"
	Case LOGIN_CLIENT
		sql = "SELECT TS_tantousha_id" & vbCrLf & _
					"FROM T_TANTOUSHA" & vbCrLf & _
					"WHERE TS_tantousha_id='" & login_id & "' AND TS_password='" & password & "'"
	Case LOGIN_GUEST
		sql = "SELECT * FROM DUAL WHERE 'guest'='" & login_id & "' AND 'password'='" & password & "'"
	End Select

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		LoginLog login_type, login_id, password, "0"
		LoginCheck = False
	Else
		Response.Cookies("login") = login_type & login_id
		LoginLog login_type, login_id, password, "1"
		LoginCheck = True
	End If

	Session.Abandon
End Function

'=== ログインログ書き込み ===
Private Sub LoginLog(login_type, login_id, password, success)
	Dim sql, ip

	ip = Request.ServerVariables("REMOTE_ADDR")

	On Error Resume Next
	sql = "INSERT INTO T_LOGIN_LOG" & vbCrLf & _
				"VALUES(LL_seq_no.nextval,'" & login_type & "','" & Left(login_id,10) & "','" & Left(password,14) & "','" & success & "','" & ip & "',SYSDATE)"
	ExecuteSQL sql
End Sub
%>