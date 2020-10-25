<%
Dim g_login_id
Dim g_login_type
Dim g_permission

If Request.Cookies("login") <> "" Then
	Dim super

	g_login_id = Request.Cookies("login")
	If Left(g_login_id, 1) = "S" Then
		g_login_id = Mid(g_login_id, 2)
		super = True
	End If
	g_login_type = Left(g_login_id, 1)
	g_login_id = Mid(g_login_id, 2)

	If super Then
		g_permission = String(100, "1")
	Else
		GetPermissions
	End If
Else
	Response.Redirect login_page
End If

'=== 権限取得 ===
Private Sub GetPermissions()
	Dim sql, ds

	g_permission = Session("permission")
	If IsEmpty(g_permission) Then
		Select Case g_login_type
		Case LOGIN_CENTER
			g_permission = String(100, "1")
		Case LOGIN_AGENT
			sql = "SELECT DA_permission FROM T_OPERATOR,T_DAIRITEN WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds.EOF Then
				g_permission = ds(0).Value
			End If
		Case LOGIN_CLIENT
			sql = "SELECT CM_permission FROM T_TANTOUSHA,T_COMPANY WHERE TS_tantousha_id='" & g_login_id & "' AND TS_company_id=CM_company_id"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds.EOF Then
				g_permission = ds(0).Value
			End If
		Case LOGIN_GUEST
			g_permission = String(100, "0")
			g_permission = SetAt(g_permission, PM_GUEST_SEARCH, "1")
		End Select
		Session("permission") = g_permission
	End If
End Sub

'=== 権限チェック ===
Function CheckPermission(pm)
	If Mid(g_permission, pm, 1) = "1" Then
		CheckPermission = True
	Else
		CheckPermission = False
	End If
End Function

'=== 権限が無い場合の処置 ===
Sub NoPermission()
	Response.Redirect top_url & "common/pm_err.asp"
End Sub
%>