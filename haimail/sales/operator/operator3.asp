<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:オペレータ情報更新処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "オペレータ情報更新"
Response.CacheControl = "no-cache"

Dim ds, sql, operator_id, id_flag

operator_id = Request.Form("operator_id")
id_flag = Request.Form("id_flag")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

If id_flag = 1 Then
	sql = "SELECT * FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	If Not ds.EOF Then
		Response.Redirect "operator_id_error.asp"
	End If
End If

sql = "SELECT * FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

If ds.EOF Then
	ds.Addnew
	ds("OP_operator_id").Value = Request.Form("operator_id")
	ds("OP_dairiten_id").Value = Request.Form("dairiten_id")
	ds("OP_regist_date").Value = Now
Else
	ds.Edit
End If
ds("OP_mail_adr").Value = Request.Form("mail_adr")
ds("OP_password").Value = Request.Form("password")
ds("OP_name1").Value = Request.Form("name1")
ds("OP_name2").Value = Request.Form("name2")
ds("OP_name1_kana").Value = Request.Form("name1_kana")
ds("OP_name2_kana").Value = Request.Form("name2_kana")
ds("OP_tel_no").Value = Request.Form("tel_no")
ds("OP_fax_no").Value = Request.Form("fax_no")
ds("OP_update_date").Value = Now
ds.Update
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
