<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:プロジェクト管理
'******************************************************

'=== 出力年の選択肢出力 ===
Private Sub SelectYear(y)
	Dim i, cy

	cy = Year(Date)
	For i = 2000 To cy
		Writeln "<option" & Selected(Trim(y), CStr(i)) & ">" & i & "</option>"
	Next
End Sub

'=== メイン処理 ===
Dim dairiten_id, company_id, tantousha_id

SetHeaderTitle "プロジェクト集計出力"
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
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<form method="post" name="form1" action="month_total.asp">
						<td class="m3" width="30%">１．月別トータル</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
							<td class="n3" align="center" width="10%"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form2" action="kind_total.asp">
						<td class="m3" width="30%">２．種別月別トータル</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form3" action="dairitenA_total.asp">
						<td class="m3" width="30%">３．代理店Ａ別月別トータル</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form4" action="dairitenB_total.asp"">
						<td class="m3" width="30%">４．代理店Ｂ別月別トータル</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form5" action="kigyou_total.asp">
						<td class="m3" width="30%">５．企業別月別トータル</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>　
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form6" action="pjt_response.asp" target="_blank">
						<td class="m3" width="30%">６．種別月別レスポンス推移</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
							<td class="n3" width="10%" align="center"><input type="submit" value="出力"></td>
						</td>
						</form>
					</tr>
					<tr>
						<form method="post" name="form7" action="send_recv.asp" target="_blank">
						<td class="m3" width="30%">７．日別送信／受信推移</td>
						<td class="n3" width="60%">
							<select name="s_year"><%SelectYear Year(Date)%>年</select>
							<select name="s_month"><%SelectMonth Month(Date)%>月</select>
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
<input type="button" value=" 戻る " onclick="location.href='../index.asp'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
