<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:個人アフィリエイト募集管理
'******************************************************

'=== メイン処理 ===
Dim sub1, sub2, sql, ds, line, koukoku_id, nline, where

SetHeaderTitle "個人アフィリエイト募集管理"

koukoku_id = Request("koukoku_id")
If IsEmpty(koukoku_id) Then
	koukoku_id = Session("pr_koukoku_id")
Else
	Session("pr_koukoku_id") = koukoku_id
End If

nline = Request("nline")
If IsEmpty(nline) Then
	nline = Session("pr_nline")
Else
	Session("pr_nline") = nline
End If
If Len(nline) > 0 Then
	nline = CInt(nline)
Else
	nline = 50
End If

sub1 = "SELECT KT_pr_id,COUNT(*) AS KT_count FROM T_KARI_TOUROKU WHERE KT_status<>'2' AND KT_pr_id IS NOT NULL GROUP BY KT_pr_id"
sub2 = "SELECT MM_pr_id,COUNT(*) AS MM_count FROM T_MEMBER WHERE MM_status<>'9' AND MM_pr_id IS NOT NULL GROUP BY MM_pr_id"

where = "PR_baitai_id='03' AND "
If Len(koukoku_id) > 0 Then
	where = where & "PR_koukoku_id='" & koukoku_id & "' AND "
End If

sql = "SELECT PR_pr_id,PR_haishin_start,PR_haishin_end,PR_member_id,PR_regist_adr,PR_click_count,KT_count,MM_count" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),T_PR" & vbCrLf & _
			"WHERE " & where & "PR_pr_id=MM_pr_id(+) AND PR_pr_id=KT_pr_id(+)" & vbCrLf & _
			"ORDER BY PR_pr_id DESC"
If nline > 0 Then
	sql = "SELECT * FROM (" & sql & ") WHERE ROWNUM<=" & nline
End If
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="get">
	<tr>
		<td>
			<nobr><font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value="集計出力" onclick="location.href='p_total.asp'">
			<input type="button" value="新規登録" onclick="location.href='p_regist.asp'">
			<input type="button" value="戻る" onclick="location.href='index.asp'">
		</td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>PRID</th>
		<th>メンバーID</th>
		<th>登録アドレス</th>
		<th>配信開始</th>
		<th>配信終了</th>
		<th>ｸﾘｯｸ数</th>
		<th>仮登録数</th>
		<th>登録数</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='p_regist.asp?id=" & ds("PR_pr_id").Value & "' title='ＰＲ情報を表示・変更します'>" & ds("PR_pr_id").Value & "</a></td>"
	Writeln "<td align='center'>" & BlankToBR(ds("PR_member_id").Value) & "</td>"
	Writeln "<td>" & BlankToBR(ReplaceTag(ds("PR_regist_adr").Value)) & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("PR_haishin_start").Value) & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("PR_haishin_end").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("PR_click_count").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("KT_count").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("MM_count").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext

	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
