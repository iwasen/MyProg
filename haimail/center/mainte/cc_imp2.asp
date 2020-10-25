<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クリック履歴インポート
'******************************************************

'=== クリックログ書き込み ===
Function WriteClickLog(ct_id, member_id, ip, dt)
	Dim sql, ds

	sql = "SELECT SR_member_id" & vbCrLf & _
				"FROM T_SENDMAIL_RR,T_JOB,T_CLICK_COUNT" & vbCrLf & _
				"WHERE CC_ct_id=" & ct_id & " AND (CC_start_date IS NULL OR CC_start_date<=SYSDATE) AND (CC_end_date IS NULL OR CC_end_date>=SYSDATE) AND JB_job_id=CC_job_id AND SR_mail_id=JB_mail_id AND SR_member_id='" & member_id & "' AND SR_status='1'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		ip = Request.ServerVariables("REMOTE_ADDR")

		sql = "INSERT INTO T_MAIL_CLICK" & vbCrLf & _
		      "VALUES(MC_seq_no.nextval," & ct_id & ",'" & member_id & "','" & ip & "',TO_DATE('" & dt & "','YYYY/MM/DD HH24:MI:SS'))"
		ExecuteSQL sql

		sql = "UPDATE T_CLICK_COUNT SET CC_click_num=NVL(CC_click_num,0)+1 WHERE CC_ct_id=" & ct_id
		ExecuteSQL sql

		WriteClickLog = True
	Else
		WriteClickLog = False
	End If
End Function

SetHeaderTitle "クリック履歴インポート"

Dim b, basp, out, click_log, ary, ary2, rec, count

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

count = 0
click_log = basp.Kconv(basp.FormBinary(b, "click_log"), 4, 1)
ary = Split(click_log, vbCrLf)
If IsArray(ary) Then
	BeginTrans
	For Each rec In ary
		ary2 = Split(rec, ",")
		If Ubound(ary2) = 3 Then
			If WriteClickLog(ary2(0), ary2(1), ary2(2), ary2(3)) Then
				count = count + 1
			End If
		End If
	Next
	CommitTrans
End If
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
<p><%=NumFormat(count)%> 件インポートしました。</p>
<form>
<input type="button" value=" ＯＫ " onclick="location.href='index.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
