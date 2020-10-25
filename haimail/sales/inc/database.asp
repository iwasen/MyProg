<%
'=== Oracle Constant ===
Const ORADB_DEFAULT = &H0&
Const ORADYN_DEFAULT = &H0&
Const ORADYN_READONLY = &H4&

'=== DataSource ===
Const ORA_DBNAME = "haimail"
'Const ORA_DBNAME = "strawberry"
Const ORA_USER = "haimail/do!house"

'=== �f�[�^�x�[�X�I�u�W�F�N�g ===
Dim db

'=== �f�[�^�x�[�X�I�[�v�� ===
Sub OpenDB()
	If Not IsObject(db) Then
		Set db = OraSession.OpenDatabase(ORA_DBNAME, ORA_USER, ORADB_DEFAULT)
	End If
End Sub

'=== �f�[�^�x�[�X�N���[�Y ===
Sub CloseDB()
	If IsObject(db) Then
		db.Close
		Set db = Nothing
	End If
End Sub

'=== �_�C�i�Z�b�g�쐬 ===
Function CreateDynaset(sql, opt)
	OpenDB
	Set CreateDynaset = db.CreateDynaset(sql, opt)
End Function

'=== SELECT���ȊO��SQL���s ===
Function ExecuteSQL(sql)
	OpenDB
	ExecuteSQL = db.ExecuteSQL(sql)
End Function

'=== �g�����U�N�V�����J�n ===
Sub BeginTrans()
	OpenDB
	db.BeginTrans
End Sub

'=== �g�����U�N�V�����R�~�b�g ===
Sub CommitTrans()
	OpenDB
	db.CommitTrans
End Sub

'=== �g�����U�N�V�������[���o�b�N ===
Sub Rollback()
	OpenDB
	db.Rollback
End Sub
%>