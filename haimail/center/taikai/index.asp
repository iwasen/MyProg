<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:退会者管理Excel表示
'******************************************************

Dim sql, ds

'=== 出力年の選択肢出力 ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)

	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== メイン処理 ===
SetHeaderTitle "退会者管理"

sql = "SELECT * FROM T_TAIKAI"
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

<center>
	<table>
		<tr>
			<table border=0 cellspacing=0 cellpadding=0 width='800'>
				<tr>
			<td align="left" class="m0">■出力期間を指定してください
			<td align="right"><%=TimeFormat(Now)%>現在　退会者数　<%=ds.RecordCount%>名</td>
				</tr>
			</table>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='800'>
					<tr>
						<form method="post" name="form1" action="taikai1_csv.asp" target="_blank">
							<td class="m3" width="30%">１．退会者ＣＳＶ出力</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear 2000%></select>年
								<select name="s_month"><%SelectMonth 12%></select>月
								<select name="s_day"><%Selectday 1%></select>日～
								<select name="e_year"><%SelectYear Year(Date)%></select>年
								<select name="e_month"><%SelectMonth Month(Date)%></select>月
								<select name="e_day"><%Selectday Day(Date)%></select>日
							<td class="n3"width="10%" align="center"><input type="submit" value="出力"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="taikai2_excel.asp" target="_blank">
							<td class="m3" width="30%">２．年別入会・退会者数推移</td>
							<td class="n3" width="60%">
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="taikai3_excel.asp" target="_blank">
							<td class="m3" width="30%">３．月別入会・退会者数推移</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear Year(Date)%></select>年
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="taikai4_excel.asp" target="_blank">
							<td class="m3" width="30%">４．日別入会・退会者数推移</td>
							<td class="n3" width="60%">
								<select name="s_year"><%SelectYear Year(Date)%></select>年
								<select name="s_month"><%SelectMonth Month(Date)%></select>月
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
							</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="taikai5_excel.asp" target="_blank">
						<td class="m3" width="30%">５．退会者の入会期間</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear 2000%></select>年
							<select name="s_month"><%SelectMonth 12%></select>月～
							<select name="e_year"><%SelectYear Year(Date)%></select>年
							<select name="e_month"><%SelectMonth Month(Date)%></select>月
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
<input type="button" value=" 戻る " onclick="location.href='../menu.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
