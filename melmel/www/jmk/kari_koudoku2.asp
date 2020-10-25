<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:‰¼“o˜^ƒƒ‹ƒ}ƒKw“ÇÏ‚ÝÝ’èˆ—
'******************************************************

Dim DB, SQL, seq_no

'ƒpƒ‰ƒ[ƒ‰Žæ“¾
seq_no = Request.QueryString("id")

If Not IsEmpty(seq_no) Then
  Set DB = OpenDB()
  SQL = "UPDATE T_PROVREG SET PR_koudoku_flag=1 WHERE PR_seq_no=" & CLng(seq_no)
  DB.Execute SQL
End If

Response.Redirect("kari_koudoku.asp")
%>
