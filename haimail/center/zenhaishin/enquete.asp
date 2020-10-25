<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信アンケートレスポンスリスト表示画面
'******************************************************

'=== メイン処理 ===
Dim job_id

SetHeaderTitle "全配信 アンケートレスポンスリスト表示"

job_id = Request.QueryString("job_id")
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
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_text() {
	document.form1.action = "enq_text.asp";
	document.form1.submit();
}
function OnClick_list() {
	document.form1.action = "enq_list.asp";
	document.form1.submit();
}
function OnClick_csv() {
	document.form1.action = "enq_csv.asp";
	document.form1.submit();
}
function OnClick_report() {
	document.form1.action = "enq_report.asp";
	document.form1.submit();
}
function OnClick_clear() {
	if (confirm("既読のメールを全て未読に設定します。よろしいですか？"))
		location.href = "enquete2.asp?job_id=<%=job_id%>"
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■アンケートレスポンスリスト表示条件</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">SEQ</td>
						<td class="n3">
							<input type="text" name="seq_from" size=5>から
							<input type="text" name="seq_to" size=5>まで
						</td>
					</tr>
					<tr>
						<td class="m3">受信期間（開始）</td>
						<td class="n3">
							<select name="start_date_y"><%SelectYear3 ""%></select>年
							<select name="start_date_m"><%SelectMonth 1%></select>月
							<select name="start_date_d"><%SelectDay 1%></select>日から
						</td>
					</tr>
					<tr>
						<td class="m3">受信期間(終了）</td>
						<td class="n3">
							<select name="end_date_y"><%SelectYear3 ""%></select>年
							<select name="end_date_m"><%SelectMonth 1%></select>月
							<select name="end_date_d"><%SelectDay 1%></select>日から
						</td>
					</tr>
					<tr>
						<td class="m3">未読</td>
						<td class="n3">
							<input type="checkbox" name="midoku" value="1">未読のレスポンスリストのみ表示　
							<input type="button" name="clear" value="クリア" onclick="OnClick_clear()">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="text" value="テキスト表示" Onclick="OnClick_text()">
	<input type="button" name="list" value="リスト一覧" Onclick="OnClick_list()">
	<input type="button" name="csv" value="CSV取得" onclick="OnClick_csv()">
	<input type="button" name="report" value="レポート生成" onclick="window.open('report.asp?job_id=<%=job_id%>&file=enq.dat')">
	<input type="reset" name="reset" value="リセット">
	<input type="button" name="back" value=" 戻る " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
