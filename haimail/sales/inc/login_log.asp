<%
'=== ƒƒOƒCƒ“ƒƒO‘‚«ž‚Ý ===
Sub LoginLog(login_type, login_id, password, success)
	Dim sql, ip

	ip = Request.ServerVariables("REMOTE_ADDR")

	On Error Resume Next
	sql = "INSERT INTO T_LOGIN_LOG" & vbCrLf & _
				"VALUES(LL_seq_no.nextval,'" & login_type & "','" & Left(login_id,10) & "','" & Left(password,8) & "','" & success & "','" & ip & "',SYSDATE)"
	ExecuteSQL sql
End Sub
%>