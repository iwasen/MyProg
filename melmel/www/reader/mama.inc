<% @LANGUAGE = "VBScript" %>
<% Option Explicit %>
<%
'=== init ===
Server.ScriptTimeout = 300
Session.Timeout = 20

'=== Common ===
'Const adOpenForwardOnly = 0 'Default
'Const adOpenKeyset = 1
'Const adOpenDynamic = 2
'Const adOpenStatic = 3
'Const adLockReadOnly = 1
'Const adLockPessimistic = 2
'Const adLockOptimistic = 3
'Const adLockBatchOptimistic = 4

'=== DataSource ===
Const ADO_PROVIDER = "{Microsoft Access Driver (*.mdb)}"
Const ADO_DATABASE = "C:\dat\present.mdb"

'=== SMTP/POP ===
Dim BASP21 : Set BASP21 = Server.CreateObject("basp21")
Const SMTP_SERVER = "smtp.xxxxxxx.co.jp"
Const OFFICE_ADDRESS = "‘å‰– <oshio1@dhmail2.xxxxxxx.co.jp>"
%>
