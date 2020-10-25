<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:企業担当者情報更新処理
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

'=== メイン処理 ===
Dim ds, sql, company_id, tantousha_id, permission

tantousha_id = Request.Form("tantousha_id")

sql = "SELECT max(CM_company_id) FROM T_COMPANY"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
company_id = MakeID(NVL(ds(0), 0) + 1, 6)

sql = "SELECT * FROM T_TANTOUSHA WHERE TS_tantousha_id='" & tantousha_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
If ds.EOF Then
	ds.Addnew
	ds("TS_tantousha_id").Value = tantousha_id
	ds("TS_company_id").Value = company_id
	ds("TS_password").Value = Request.Form("password")
	ds("TS_name1").Value = Request.Form("name1")
	ds("TS_name2").Value = Request.Form("name2")
	ds("TS_regist_date").Value = Now
	ds("TS_update_date").Value = Now
	ds.Update

	sql = "SELECT * FROM T_COMPANY WHERE CM_company_id='" & company_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	ds.Addnew
	ds("CM_company_id").Value = company_id
	ds("CM_dairiten_id").Value = GetDairitenID(g_login_id)
	ds("CM_name").Value = Request.Form("name")
	ds("CM_regist_date").Value = Now
	ds("CM_update_date").Value = Now
	permission = String(100, "0")
	permission = SetAt(permission, PM_CLICK_REPORT, "1")
	permission = SetAt(permission, PM_CLICK_URL, "1")
	permission = SetAt(permission, PM_IMP_NUMBER, "1")
	ds("CM_permission").Value = permission

	ds.Update

	Writeln "<script LANGUAGE=javascript>"
	Writeln "opener.AddCompany('" & company_id & "','" & Request.Form("name") & "','" & tantousha_id & "','" & Request.Form("name1") & " " & Request.Form("name2") & "');"
	Writeln "window.close();"
	Writeln "</script>"
Else
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
<p>入力した担当者IDは既に登録されています。</p>
<input type="button" value=" 戻る " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
End If
%>