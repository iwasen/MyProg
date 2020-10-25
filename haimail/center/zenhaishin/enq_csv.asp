<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信アンケート受信データCSV出力画面
'******************************************************

'=== メイン処理 ===
Dim job_id

SetHeaderTitle "全配信 アンケート受信データCSV出力"

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
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
<form method="post" name="form1" action="enq_csv2.asp">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■出力情報選択</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">出力項目</td>
						<td class="n3">
							<input type="checkbox" name="csv_seq" value="1" checked>SEQ<br>
							<input type="checkbox" name="csv_member_id" value="1" checked>はいめーるID<br>
							<input type="checkbox" name="csv_seibetsu" value="1" checked>性別<br>
							<input type="checkbox" name="csv_nenrei" value="1" checked>年齢<br>
							<input type="checkbox" name="csv_nendai" value="1" checked>年代<br>
							<input type="checkbox" name="csv_shokugyou" value="1" checked>職業<br>
							<input type="checkbox" name="csv_mikikon" value="1" checked>未既婚<br>
							<input type="checkbox" name="csv_adr_kyojuu" value="1" checked>都道府県（住所）<br>
							<input type="checkbox" name="csv_adr_kinmu" value="1" checked>都道府県（勤務地）<br>
							<input type="checkbox" name="csv_zip_kyojuu" value="1" checked>郵便番号（住所）<br>
							<input type="checkbox" name="csv_zip_kinmu" value="1" checked>郵便番号（勤務地）<br>
							<input type="checkbox" name="csv_ans_date" value="1" checked>回答日<br>
							<input type="checkbox" name="csv_ans_data" value="1" checked>回答内容<br>
							<input type="checkbox" name="csv_kaiseki" value="1" checked>解析結果<br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="submit" value=" 出力 ">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
