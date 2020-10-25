<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:登録者数推移Excel表示
'******************************************************

Dim koukoku_id, baitai_id

'=== 出力年の選択肢出力 ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)
	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== 広告タイプ選択肢 ===
Private Sub SelectKoukokuType(koukoku_id)
	Dim sql, ds

	sql = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(koukoku_id, "") & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("KT_koukoku_id").Value & "'" & Selected(koukoku_id, ds("KT_koukoku_id").Value) & ">" & ds("KT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 媒体選択肢 ===
Private Sub SelectBaitai(baitai_id)
	Dim sql, ds

	sql = "SELECT * FROM T_BAITAI WHERE BT_baitai_id<>'03' ORDER BY BT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(baitai_id, "") & ">- 全て -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("BT_baitai_id").Value & "'" & Selected(baitai_id, ds("BT_baitai_id").Value) & ">" & ds("BT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
SetHeaderTitle "一般募集 登録者数集計出力"
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
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<form method="post" name="form1" action="g_total1.asp" target="_blank">
						<td class="m3" width="25%">１．ＰＲ登録一覧</td>
						<td class="n3" width="65%">
							<font class="hc">広告タイプ</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>　
							<font class="hc">媒体</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3"width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="g_total2.asp" target="_blank">
						<td class="m3" width="25%">２．週別トータル</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　　　　　　　　　　　　　　　　　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="g_total3.asp" target="_blank">
						<td class="m3" width="25%">３．月別トータル</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　　　　　　　　　　　　　　　　　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="g_total4.asp" target="_blank">
						<td class="m3" width="25%">４．週別広告タイプ別</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<font class="hc">広告タイプ</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="g_total5.asp" target="_blank">
						<td class="m3" width="25%">５．月別広告タイプ別</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<font class="hc">広告タイプ</font>
							<select name="koukoku_id"><%SelectKoukokuType(koukoku_id)%></select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form6" action="g_total6.asp" target="_blank">
						<td class="m3" width="25%">６．週別媒体別</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<font class="hc">媒体</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form7" action="g_total7.asp" target="_blank">
						<td class="m3" width="25%">７．月別媒体別</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<font class="hc">媒体</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form8" action="g_total8.asp" target="_blank">
						<td class="m3" width="25%">８．広告タイプ別累計</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form9" action="g_total9.asp" target="_blank">
						<td class="m3" width="25%">９．媒体別累計</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form10" action="g_total10.asp" target="_blank">
						<td class="m3" width="25%">10．週別媒体×ＰＲ名別</td>
						<td class="n3" width="65%">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<font class="hc">媒体</font>
							<select name="baitai_id"><%SelectBaitai(baitai_id)%></select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<br>
<input type="button" value=" 戻る " onclick="history.back()">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
