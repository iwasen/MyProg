<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<%
Dim mail_id, seq_no, bindata, sql, ds

Response.CacheControl = "no-cache"

mail_id = Request.QueryString("id")
seq_no = Request.QueryString("seq")
If Not IsEmpty(mail_id) Then
	sql = "SELECT AT_file_name,AT_file_body FROM T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_seq_no=" & seq_no
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		ds("AT_file_body").Value.Read bindata

		Response.Expires = 0
		Response.Buffer = True
		Response.Clear
		Response.AddHeader "Content-Disposition", "inline; filename=""" & ds("AT_file_name").Value & """"
		Response.BinaryWrite bindata
	Else
		Response.Status = 404
	End If
End If
%>
