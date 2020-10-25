<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー募集管理
'******************************************************

'=== 媒体名取得 ===
Private Function GetBaitaiName(baitai_id)
	Dim sql, ds

	If baitai_id <> "" Then
		sql = "SELECT BT_name FROM T_BAITAI WHERE BT_baitai_id='" & baitai_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			GetBaitaiName = ds(0).Value
		End If
	Else
		GetBaitaiName = "全て"
	End If
End Function

'=== 広告タイプ選択肢 ===
Private Function GetKoukokuTypeName(koukoku_id)
	Dim sql, ds

	If koukoku_id <> "" Then
		sql = "SELECT KT_name FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id='" & koukoku_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			GetKoukokuTypeName = ds(0).Value
		End If
	Else
		GetKoukokuTypeName = "全て"
	End If
End Function

'=== メイン処理 ===
Dim sub1, sub2, sub3, sql, ds, line, baitai_id, koukoku_id, where, click_count

SetHeaderTitle "ＰＲ登録一覧"
Response.ContentType = "application/vnd.ms-excel"

baitai_id = Request.Form("baitai_id")
If IsEmpty(baitai_id) Then
	baitai_id = Session("pr_baitai_id")
End If

koukoku_id = Request.Form("koukoku_id")
If IsEmpty(koukoku_id) Then
	koukoku_id = Session("pr_koukoku_id")
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
			"ORDER BY PR_pr_id"
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

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td>１．ＰＲ登録一覧</td>
	</tr>
</table>
<br>

<table border=1 cellpadding=0 cellspacing=1 width="100%">
	広告タイプ＝<%=GetKoukokuTypeName(koukoku_id)%>　媒体＝<%=GetBaitaiName(baitai_id)%>
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
		<th>CVR仮</th>
		<th>CVR本</th>
	</tr>
<%
Do Until ds.EOF
	Writeln "<tr>"
	Writeln "<td align='center'>" & ds("PR_pr_id").Value & "</td>"
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

	click_count = Number(ds("PR_click_count").Value)
	If click_count > 0 Then
		Writeln "<td align='right'>" & FormatPercent(Number(ds("KT_count").Value) / click_count, 2) & "</td>"
		Writeln "<td align='right'>" & FormatPercent(Number(ds("MM_count").Value) / click_count, 2) & "</td>"
	Else
		Writeln "<td align='right'>-</td>"
		Writeln "<td align='right'>-</td>"
	End If
	Writeln "</tr>"
	ds.MoveNext
Loop
%>
</table>

</body>
</html>
