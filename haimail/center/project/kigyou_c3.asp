<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:企業情報更新処理
'******************************************************

'=== 権限取得 ===
Function ReadPermission(n)
	Dim pm, item

	Set pm = New CPermission
	For Each item In Request.Form("pm" & n)
		pm.SetPermission CInt(item), True
	Next

	ReadPermission = pm.permission
End Function

'=== メイン処理 ===
SetHeaderTitle "企業情報更新"

Dim ds, sql, company_id

company_id = Request.Form("company_id")
If IsEmpty(company_id) Then
	sql = "SELECT max(CM_company_id) FROM T_COMPANY"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	company_id = MakeID(NVL(ds(0), 0) + 1, 6)
End If

sql = "SELECT * FROM T_COMPANY WHERE CM_company_id='" & company_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("CM_company_id").Value = company_id
	ds("CM_regist_date").Value = Now
Else
	ds.Edit
End If
ds("CM_dairiten_id").Value = Request.Form("dairiten_id")
ds("CM_name").Value = Request.Form("name")
ds("CM_name_kana").Value = Request.Form("name_kana")
ds("CM_zipcode").Value = ToHankaku(Request.Form("zipcode1")) & ToHankaku(Request.Form("zipcode2"))
ds("CM_address1").Value = Request.Form("address1")
ds("CM_address2").Value = Request.Form("address2")
ds("CM_update_date").Value = Now
ds("CM_permission").Value = ReadPermission(3)
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
<input type="button" value=" ＯＫ " onclick="location.href='kigyou.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
