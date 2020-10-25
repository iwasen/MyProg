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

'=== メイン処理 ===
SetHeaderTitle "個人アフィリエイト募集 登録者数集計出力"
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
						<form method="post" name="form2" action="p_total1.asp" target="_blank">
						<td class="m3" width="30%">１．週別トータル</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　　　　　　　　　　　　　　　　　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="p_total2.asp" target="_blank">
						<td class="m3" width="30%">２．月別トータル</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　　　　　　　　　　　　　　　　　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="p_total3.asp" target="_blank">
						<td class="m3" width="30%">３．メンバー別週別トータル</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="p_total4.asp" target="_blank">
						<td class="m3" width="30%">４．メンバー別月別トータル</td>
						<td class="n3">
							<select name="s_year"><%SelectYear 2000%>年</select>
							<select name="s_month"><%SelectMonth 12%>月</select>～
							<select name="e_year"><%SelectYear Year(Date)%>年</select>
							<select name="e_month"><%SelectMonth Month(Date)%>月</select>　
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
