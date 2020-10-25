<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�c�a�T�[�o�؂�ւ�
'******************************************************

Const main_server = "haimail"
Const backup_server = "lennon"

'=== �c�a�T�[�o���擾���� ===
Function GetDbServer(path)
	Dim fso, ts, text_all, main_text, backup_text
	
	Set fso = CreateObject("Scripting.FileSystemObject")
	Set ts = fso.OpenTextFile(path)
	text_all = ts.ReadAll
	ts.Close

	main_text = "ORA_DBNAME = """ & main_server & """"
	backup_text = "ORA_DBNAME = """ & backup_server & """"

	If InStr(text_all, main_text) > 0 Then
		GetDbServer = "main"
	ElseIf InStr(text_all, backup_text) > 0 Then
		GetDbServer = "backup"
	End If
End Function

'=== �c�a�T�[�o�؂�ւ����� ===
Sub ChangeDbServer(path, old_server, new_server)
	Dim fso, ts, text_all, old_text, new_text
	
	Set fso = CreateObject("Scripting.FileSystemObject")
	Set ts = fso.OpenTextFile(path, 1)
	text_all = ts.ReadAll
	ts.Close

	old_text = "ORA_DBNAME = """ & old_server & """"
	new_text = "ORA_DBNAME = """ & new_server & """"

	text_all = Replace(text_all, old_text, new_text, 1, 1)

	Set ts = fso.CreateTextFile(path, True)
	ts.Write text_all
	ts.Close
End Sub

'=== �V�[�P���X�č쐬 ===
Sub CreateSequenceAll()
	Dim sql, ds

	Set db = OraSession.OpenDatabase("haimail", ORA_USER, ORADB_DEFAULT)

	CreateSequence "T_MEMBER", "MM_MEMBER_ID", 10, "NOCYCLE"
	CreateSequence "T_KARI_TOUROKU", "KT_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_SHOUKAI", "SK_SHOUKAI_NO", 10, "NOCYCLE"
	CreateSequence "T_AUTOLOG", "AL_SEQ_NO", 10, "CYCLE"
	CreateSequence "T_TOUROKU_MAIL", "TM_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_CLICK_LOG", "CL_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_ZENHAISHIN", "ZH_JOB_ID", 8, "NOCYCLE"
	CreateSequence "T_SEND_MAIL", "SM_MAIL_ID", 8, "NOCYCLE"
	CreateSequence "T_BSHAISHIN", "BH_JOB_ID", 8, "NOCYCLE"
	CreateSequence "T_HAIMAIL_LETTER", "HL_HML_ID", 10, "NOCYCLE"
	CreateSequence "T_RECV_MAIL", "RM_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_PROJECT", "PJ_PROJECT_ID", 8, "NOCYCLE"
	CreateSequence "T_JOB", "JB_JOB_ID", 8, "NOCYCLE"
	CreateSequence "T_CLICK_COUNT", "CC_CT_ID", 7, "NOCYCLE"
	CreateSequence "T_ERROR_LOG", "EL_seq_no", 8, "CYCLE"
	CreateSequence "T_SEARCH", "SE_search_id", 8, "NOCYCLE"
	CreateSequence "T_MAIL_CLICK", "MC_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_LOGIN_LOG", "LL_SEQ_NO", 9, "NOCYCLE"
	CreateSequence "T_HTML_ANSWER", "HA_SEQ_NO", 10, "NOCYCLE"
	CreateSequence "T_SEND_MAIL_DATA", "SD_SEND_MAIL_ID", 8, "NOCYCLE"
End Sub

Sub CreateSequence(table, field, length, cycle)
	Dim sql, ds, start

	sql = "SELECT MAX(" & field & ") FROM " & table
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		start = 1
	Else
		start = CDbl(NVL(ds(0).value, 0)) + 1
	End If

	On Error Resume Next
	sql = "DROP SEQUENCE " & field
	ExecuteSQL sql
	On Error Goto 0

	sql = "CREATE SEQUENCE " & field & " INCREMENT BY 1 START WITH " & start & " MAXVALUE " & String(length, "9") & " MINVALUE 1 " & cycle & " NOCACHE ORDER"
	ExecuteSQL sql
End Sub

'=== ���C������ ===
SetHeaderTitle "�c�a�T�[�o�؂�ւ�"

Dim db_server, msg

db_server = Request("db_server")
If Not IsEmpty(db_server) Then
	Select Case db_server
	Case "main"
		ChangeDbServer "\\jun\haimail\inc\database.asp", backup_server, main_server
		ChangeDbServer Server.MapPath("../inc/database.asp"), backup_server, main_server
	Case "backup"
		ChangeDbServer "\\jun\haimail\inc\database.asp", main_server, backup_server
		ChangeDbServer Server.MapPath("../inc/database.asp"), main_server, backup_server
	End Select

	CreateSequenceAll
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	return confirm("�c�a�T�[�o��ύX���Ă�낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" onSubmit="return OnSubmit_form1()">
<%
Select Case GetDbServer(Server.MapPath("../inc/database.asp"))
Case "main"
	Writeln "<p>�����ݎg�p����Ă���c�a�T�[�o�̓��C���c�a�T�[�o(strawberry)�ł��B</p>"
	Writeln "<input type='submit' value='�o�b�N�A�b�v�c�a�T�[�o�ɕύX'>"
	Writeln "<input type='hidden' name='db_server' value='backup'>"
Case "backup"
	Writeln "<p>�����ݎg�p����Ă���c�a�T�[�o�̓o�b�N�A�b�v�c�a�T�[�o(lennon)�ł��B</p>"
	Writeln "<input type='submit' value='���C���c�a�T�[�o�ɕύX'>"
	Writeln "<input type='hidden' name='db_server' value='main'>"
Case Else
	Writeln "<p>�����ݎg�p����Ă���c�a�T�[�o�͕s���ł��B</p>"
End Select
%>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
