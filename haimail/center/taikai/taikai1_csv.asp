<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:退会者管理CSV出力
'******************************************************

SetHeaderTitle "退会者CSV出力"

Dim search, sql, ds, find, count
Dim s_year, s_month, s_day, e_year, e_month, e_day, start_ymd, end_ymd

s_year = Request.Form("s_year")
s_month = Right("0" & Request.Form("s_month"), 2)
s_day = Right("0" & Request.Form("s_day"), 2)
e_year = Request.Form("e_year")
e_month = Right("0" & Request.Form("e_month"), 2)
e_day = Right("0" & Request.Form("e_day"), 2)

start_ymd = s_year & s_month & s_day
end_ymd = e_year & e_month & e_day

sql = "SELECT *" & vbCrLf & _
			"FROM T_MEMBER, T_TAIKAI" & vbCrLf & _
			"WHERE MM_member_id=TK_member_id AND TO_CHAR(TK_taikai_date,'YYYYMMDD') BETWEEN '" & start_ymd & "' AND '" & end_ymd & "'" & vbCrLf & _
			"ORDER BY MM_member_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
find = ds.RecordCount

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
<%
count = 0
Do Until ds.EOF
	Response.Write "<nobr>"
	Response.Write """"
	Response.Write Right("000" & DateDiff("d",ds("MM_regist_date").value,ds("TK_taikai_date").value)+1, 4)
	Response.Write ""","""
	Response.Write DateFormat(ds("MM_regist_date").value)
	Response.Write ""","""
	Response.Write DateFormat(ds("TK_taikai_date").value)
	Response.Write ""","""
	Response.Write ds("MM_member_id").value
	Response.Write ""","""
	Response.Write ds("MM_mail_adr").value
	Response.Write ""","""
	Response.Write ds("TK_taikai_riyuu").value
	Response.Write """"
	Response.Write "</nobr><br>"
	count = count + 1
	ds.MoveNext
Loop
%>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
