<%
'=== Oracle Constant ===
Const ORADB_DEFAULT = &H0&
Const ORADYN_DEFAULT = &H0&
Const ORADYN_READONLY = &H4&

'=== DataSource ===
Const ORA_DBNAME = "haimail"
'Const ORA_DBNAME = "strawberry"
Const ORA_USER = "haimail/do!house"

'=== データベースオブジェクト ===
Dim db

'=== データベースオープン ===
Sub OpenDB()
	If Not IsObject(db) Then
		Set db = OraSession.OpenDatabase(ORA_DBNAME, ORA_USER, ORADB_DEFAULT)
	End If
End Sub

'=== データベースクローズ ===
Sub CloseDB()
	If IsObject(db) Then
		db.Close
		Set db = Nothing
	End If
End Sub

'=== ダイナセット作成 ===
Function CreateDynaset(sql, opt)
	OpenDB
	Set CreateDynaset = db.CreateDynaset(sql, opt)
End Function

'=== SELECT文以外のSQL実行 ===
Function ExecuteSQL(sql)
	OpenDB
	ExecuteSQL = db.ExecuteSQL(sql)
End Function

'=== トランザクション開始 ===
Sub BeginTrans()
	OpenDB
	db.BeginTrans
End Sub

'=== トランザクションコミット ===
Sub CommitTrans()
	OpenDB
	db.CommitTrans
End Sub

'=== トランザクションロールバック ===
Sub Rollback()
	OpenDB
	db.Rollback
End Sub
%>