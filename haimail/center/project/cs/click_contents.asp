<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:URL別クリック率画面処理
'******************************************************

'=== クリックカウント用URL名生成 ===
Private Function ClickCountURL(ct_id)
	ClickCountURL = "http://www.haimail.net/ct/c.asp?" & ct_id & "-%CLICK%"
End Function

'=== CTR取得 ===
Private Function GetCTR(ct)
	If send_count = 0 Then
		GetCTR = "-"
	Else
		GetCTR = FormatPercent(ct / send_count, 2)
	End If
End FUnction

'=== メイン処理 ===
Dim job_id, sub1, sql, ds, body, send_count, s, n, i, p, tmp, ct_total, ctu_total, line

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

SetHeaderTitle "URL別クリック率"

sql = "SELECT SM_body,SM_real_send" & vbCrLf & _
			"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
			"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	body = Split(ReadCLOB(ds(0).Value), vbCrLf)
	send_count = Number(ds(1).Value)
Else
	SystemError "not found mail_id"
End If

sub1 = "SELECT MC_ct_id,SUM(count) AS MC_count,COUNT(*) AS MC_count_uniq" & vbCrLf & _
			"FROM (SELECT MC_ct_id,COUNT(*) AS count FROM T_MAIL_CLICK WHERE MC_ct_id IN (SELECT CC_ct_id FROM T_CLICK_COUNT WHERE CC_job_id=" & job_id & ") GROUP BY MC_ct_id,MC_member_id)" & vbCrLf & _
			"GROUP BY MC_ct_id"
sql = "SELECT CC_ct_id,CC_jump_url,MC_count,MC_count_uniq" & vbCrLf & _
			"FROM T_CLICK_COUNT,(" & sub1 & ")" & vbCrLf & _
			"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id(+)"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
n = ds.RecordCount
Redim url1(n), url2(n), ct(n), ctu(n)
ct_total = 0
For i = 1 To n
	url1(i) = ClickCountURL(ds(0).Value)
	url2(i) = ds(1).Value
	ct(i) = Number(ds(2).Value)
	ctu(i) = Number(ds(3).Value)

	ct_total = ct_total + ct(i)
	ds.MoveNext
Next

sql = "SELECT COUNT(DISTINCT MC_member_id)" & vbCrLf & _
			"FROM T_MAIL_CLICK,T_CLICK_COUNT" & vbCrLf & _
			"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	ctu_total = 0
Else
	ctu_total = Number(ds(0).Value)
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<th align="center" style="BORDER: navy 2px solid">メールコンテンツ</th>
		<th width=10></th>
		<th width=250>
			<table border=1 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<th width="33%"><nobr>クリック数</nobr></th>
					<th width="33%"><nobr>CTR</nobr></th>
					<th width="33%"><nobr>CTR(U)</nobr></th>
				</tr>
			</table>
		</th>
	</tr>
<%
line = 1
For Each s In body
	s = ReplaceTag(s)
	tmp = ""
	If line = 1 Then
		tmp = "<table border=1 cellspacing=0 cellpadding=1 width='100%'><tr>" & _
					"<td align='right' width='33%'>" & NumFormat(ct_total) & "</td>" & _
					"<td align='right' width='33%'>" & GetCTR(ct_total) & "</td>" & _
					"<td align='right' width='33%'>" & GetCTR(ctu_total) & "</td>" & _
					"</tr></table>"
	Else
		For i = 1 To n
			p = InStr(s, url1(i))
			If p > 0 Then
				s = Replace(s, url1(i), "<a href='" & url2(i) & "' target='_blank'>" & url1(i) & "</a>")

				tmp = tmp & "<table border=1 cellspacing=0 cellpadding=1 width='100%'><tr>" & _
							"<td align='right' width='33%'>" & NumFormat(ct(i)) & "</td>" & _
							"<td align='right' width='33%'>" & GetCTR(ct(i)) & "</td>" & _
							"<td align='right' width='33%'>" & GetCTR(ctu(i)) & "</td>" & _
							"</tr></table>"
			End If
		Next
	End If

	If tmp = "" Then
		tmp = "<br>"
	End If

	Writeln "<tr>"
	Writeln "<td class='np' style='border-left: navy 2px solid;border-right: navy 2px solid'>" & BlankToBR(s) & "</td>"
	Writeln "<td width=10></td>"
	Writeln "<td>" & tmp & "</td>"
	Writeln "</tr>"

	line = line + 1
Next
Writeln "<tr>"
Writeln "<td class='np' style='border-top: navy 2px solid'><br></td>"
Writeln "</tr>"
%>
</table>
	<p>　</p>
	<table align=right width="25%" border="0" cellspacing="5" cellpadding="0">
		<tr>
			<td nowrap class="info">※CTR：クリック数（重複あり）／配信数</td>
		</tr>
		<tr>
			<td nowrap class="info">※CTR(U)：クリックした人数（重複なし）／配信数</td>
		</tr>
	</table>
	<p>　</p>
	<p>
		<input type="button" value=" 戻る " onclick="history.back()">
	</p>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
