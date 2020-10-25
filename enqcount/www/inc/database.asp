<%
Dim objCon

Const ADO_PROVIDER = "sqloledb"
Const ADO_SERVER = ""
Const ADO_DATABASE = "enqcount"
Const ADO_USERID = "enqcount"
Const ADO_PASSWORD = "enqpass"

'=== データベースオープン ===
Sub OpenDB()
	If Not IsObject(objCon) Then
	  Set objCon = Server.CreateObject("ADODB.Conxxxtion")
		With objCon
		.Provider = ADO_PROVIDER
		.Properties("Data Source").Value = ADO_SERVER
		.Properties("Initial Catalog").Value = ADO_DATABASE
		.Properties("User ID").Value = ADO_USERID
		.Properties("Password").Value = ADO_PASSWORD
		.Open
		End With
	End If
End Sub

'=== データベースクローズ ===
Sub CloseDB()
	If IsObject(objCon) Then
		objCon.Close
		Set objCon = Nothing
	End If
End Sub

'=== Recoedset作成 ===
Function CreateRecordset(sql, CursorType, LockType)
	OpenDB
	Set CreateRecordset = Server.CreateObject("ADODB.Recordset")
	CreateRecordset.Open sql, objCon, CursorType, LockType
End Function

'=== SELECT文以外のSQL実行 ===
Function ExecuteSQL(sql)
	OpenDB
	objCon.Execute sql
End Function

'=== トランザクション開始 ===
Sub BeginTrans()
	OpenDB
	objCon.BeginTrans
End Sub

'=== トランザクションコミット ===
Sub CommitTrans()
	OpenDB
	objCon.CommitTrans
End Sub

'=== トランザクションロールバック v
Sub Rollback()
	OpenDB
	objCon.Rollback
End Sub

'=== 文字列データ ===
Function sql_char(dat)
	If dat <> "" Then
		sql_char = "'" & Replace(dat, "'", "''") & "'"
 	Else
		sql_char = "NULL"
	End If
End Function

'=== 数値データ ===
Function sql_number(dat)
	if Not IsNull(dat) Then
		sql_number = dat
	Else
		sql_number = "NULL"
	End If
End Function

'=== 最終ID取得 ===
Function get_last_id()
	Dim sql, rs

	sql = "SELECT @@IDENTITY"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	If Not rs.EOF Then
		get_last_id = rs(0)
	End If
End Function
%>