<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
Dim pr_id, bindata, sql, ds

Response.CacheControl = "no-cache"

pr_id = Request.QueryString("id")
If Not IsEmpty(pr_id) Then
	sql = "SELECT PR_contents_pict FROM T_PR WHERE PR_pr_id='" & pr_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		ds(0).Value.Read bindata

		Response.Expires = 0
		Response.Buffer = True
		Response.Clear
		If AscB(Left(bindata, 1)) = &h47 Then
			Response.ContentType = "image/gif"
		ElseIf AscB(Left(bindata, 1)) = &hff Then
			Response.ContentType = "image/jpeg"
		End If
		Response.BinaryWrite bindata
	Else
		Response.ContentType = "image/jpeg"
		'Response.Status = 404
	End If
End If
%>
