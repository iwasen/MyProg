<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クリックカウンタエクスポート
'******************************************************

Dim sql, ds

Response.ContentType = "application/x-download"
Response.AddHeader "Content-Disposition", "inline; filename=""click_counter.dat"""

sql = "SELECT CC_ct_id,CC_jump_url FROM T_CLICK_COUNT WHERE (CC_end_date IS NULL OR CC_end_date>=SYSDATE) ORDER BY CC_ct_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
Do Until ds.EOF
	Writeln ds("CC_ct_id").Value & "," & ds("CC_jump_url").Value
	ds.MoveNext
Loop
%>