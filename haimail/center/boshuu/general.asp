<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:一般募集管理
'******************************************************

'=== 媒体選択肢 ===
Private Sub SelectBaitai(baitai_id)
	Dim sql, ds

	sql = "SELECT * FROM T_BAITAI ORDER BY BT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(baitai_id, "") & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("BT_baitai_id").Value & "'" & Selected(baitai_id, ds("BT_baitai_id").Value) & ">" & ds("BT_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 広告タイプ選択肢 ===
Private Sub SelectKoukokuType(koukoku_id)
	Dim sql, ds

	sql = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(koukoku_id, "") & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("KT_koukoku_id").Value & "'" & Selected(koukoku_id, ds("KT_koukoku_id").Value) & ">" & ds("KT_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim sub1, sub2, sql, ds, line, baitai_id, koukoku_id, nline, where

SetHeaderTitle "一般募集管理"

baitai_id = Request("baitai_id")
If IsEmpty(baitai_id) Then
	baitai_id = Session("pr_baitai_id")
Else
	Session("pr_baitai_id") = baitai_id
End If

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

If Len(baitai_id) > 0 Then
	where = "PR_baitai_id='" & baitai_id & "' AND "
Else
	where = "PR_baitai_id<>'03' AND "
End If
If Len(koukoku_id) > 0 Then
	where = where & "PR_koukoku_id='" & koukoku_id & "' AND "
End If

sql = "SELECT PR_pr_id,PR_pr_name,PR_haishin_suu,PR_click_suu,PR_haishin_start,PR_haishin_end,BT_name,KT_name,PR_click_count,KT_count,MM_count" & vbCrLf & _
			"FROM (" & sub1 & "),(" & sub2 & "),T_KOUKOKU_TYPE,T_BAITAI,T_PR" & vbCrLf & _
			"WHERE " & where & "PR_koukoku_id=KT_koukoku_id(+) AND PR_baitai_id=BT_baitai_id(+) AND PR_pr_id=MM_pr_id(+) AND PR_pr_id=KT_pr_id(+)" & vbCrLf & _
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
			<nobr><font class="hc">媒体</font><select name="baitai_id" onchange="submit()"><%SelectBaitai(baitai_id)%></select></nobr>
			<nobr><font class="hc">広告タイプ</font><select name="koukoku_id" onchange="submit()"><%SelectKoukokuType(koukoku_id)%></select></nobr>
			<nobr><font class="hc">表示行数</font><select name="nline" onchange="submit()"><%SelectDispLine(nline)%></select></nobr>
		</td>
		<td align="right">
			<input type="button" value="集計出力" onclick="location.href='g_total.asp'">
			<input type="button" value="新規登録" onclick="location.href='g_regist.asp'">
			<input type="button" value="戻る" onclick="location.href='index.asp'">
		</td>
	</tr>
	</form>
</table>

<table <%=LIST_TABLE%> width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>PRID</th>
		<th>媒体名</th>
		<th>PR名称</th>
		<th>広告タイプ</th>
		<th>配信数</th>
		<th>保証ｸﾘｯｸ数</th>
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
	Writeln "<td align='center'><a href='g_regist.asp?id=" & ds("PR_pr_id").Value & "' title='ＰＲ情報を表示・変更します'>" & ds("PR_pr_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("BT_name").Value) & "</td>"
	Writeln "<td>" & ReplaceTag(ds("PR_pr_name").Value) & "</td>"
	Writeln "<td>" & ReplaceTag(ds("KT_name").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("PR_haishin_suu").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("PR_click_suu").Value) & "</td>"
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
