<%
Dim objCon

Const ADO_PROVIDER = "sqloledb"
Const ADO_SERVER = ""
Const ADO_DATABASE = "enqcount"
Const ADO_USERID = "enqcount"
Const ADO_PASSWORD = "enqpass"

'=== �f�[�^�x�[�X�I�[�v�� ===
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

'=== �f�[�^�x�[�X�N���[�Y ===
Sub CloseDB()
	If IsObject(objCon) Then
		objCon.Close
		Set objCon = Nothing
	End If
End Sub

'=== Recoedset�쐬 ===
Function CreateRecordset(sql, CursorType, LockType)
	OpenDB
	Set CreateRecordset = Server.CreateObject("ADODB.Recordset")
	CreateRecordset.Open sql, objCon, CursorType, LockType
End Function

'=== SELECT���ȊO��SQL���s ===
Function ExecuteSQL(sql)
	OpenDB
	objCon.Execute sql
End Function

'=== �g�����U�N�V�����J�n ===
Sub BeginTrans()
	OpenDB
	objCon.BeginTrans
End Sub

'=== �g�����U�N�V�����R�~�b�g ===
Sub CommitTrans()
	OpenDB
	objCon.CommitTrans
End Sub

'=== �g�����U�N�V�������[���o�b�N v
Sub Rollback()
	OpenDB
	objCon.Rollback
End Sub

'=== ������f�[�^ ===
Function sql_char(dat)
	If dat <> "" Then
		sql_char = "'" & Replace(dat, "'", "''") & "'"
 	Else
		sql_char = "NULL"
	End If
End Function

'=== ���l�f�[�^ ===
Function sql_number(dat)
	if Not IsNull(dat) Then
		sql_number = dat
	Else
		sql_number = "NULL"
	End If
End Function

'=== �ŏIID�擾 ===
Function get_last_id()
	Dim sql, rs

	sql = "SELECT @@IDENTITY"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	If Not rs.EOF Then
		get_last_id = rs(0)
	End If
End Function
%>