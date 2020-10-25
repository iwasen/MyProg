<!--#INCLUDE FILE="melmel_inc.asp"-->

<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
</HEAD>
<BODY>

<%
Dim DB, SQL, RS, mail_adr

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MM_mail_adr FROM T_MELMEL"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
Do Until RS.EOF
	mail_adr = RS(0)
	If Not IsNull(mail_adr) Then
		If mail_adr <> "" Then
			If MailAdrCheck(mail_adr) = False Then
				Writeln "NG " & mail_adr & "<br>"
			End IF
		End If
	End If
	RS.MoveNext
Loop
RS.Close
%>


</BODY>
</HTML>
