<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店情報更新処理
'******************************************************

'=== 代理店ID取得 ===
Private Function GetDairitenID(operator_id)
	Dim sql, ds

	sql = "SELECT OP_dairiten_id FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetDairitenID = ds(0).Value
	End If
End Function

'=== 権限取得 ===
Function GetPermission(n)
	Dim pm, item

	Set pm = New CPermission
	For Each item In Request.Form("pm" & n)
		pm.SetPermission CInt(item), True
	Next

	GetPermission = pm.permission
End Function

'=== メイン処理 ===
SetHeaderTitle "代理店情報更新"

Dim ds, sql, dairiten_id

dairiten_id = Request.Form("dairiten_id")
If IsEmpty(dairiten_id) Then
	sql = "SELECT max(DA_dairiten_id) FROM T_DAIRITEN"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	dairiten_id = MakeID(NVL(ds(0), 0) + 1, 5)
End If

sql = "SELECT * FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("DA_dairiten_id").Value = dairiten_id
	ds("DA_class").Value = "B"
	ds("DA_regist_date").Value = Now
Else
	ds.Edit
End If
ds("DA_name").Value = Request.Form("name")
ds("DA_name_kana").Value = Request.Form("name_kana")
ds("DA_zipcode").Value = Request.Form("zipcode1") & Request.Form("zipcode2")
ds("DA_address1").Value = Request.Form("address1")
ds("DA_address2").Value = Request.Form("address2")
ds("DA_update_date").Value = Now
ds("DA_rep_id").Value = GetDairitenID(g_login_id)
ds("DA_permission").Value = GetPermission(2)
ds("DA_pm_client").Value = GetPermission(3)
ds.Update
%>
<HTML>
<HEAD>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</HEAD>
<BODY>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</BODY>
</HTML>
