<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信原稿＆発信条件登録画面
'******************************************************

'=== ＤＢよりデータ読み込み ===
Private Sub ReadJob(id)
	Dim sql, ds

	sql = "SELECT * FROM T_ZENHAISHIN,T_SEND_MAIL" & vbCrLf & _
				"WHERE ZH_job_id=" & id & " AND ZH_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Redirect "index.asp"
	End If

	job_name = ds("ZH_job_name").Value
	subject = ds("SM_subject").Value
	body = ReadCLOB(ds("SM_body").Value)
	start_date_y = Year(ds("SM_start_date").Value)
	start_date_m = Month(ds("SM_start_date").Value)
	start_date_d = Day(ds("SM_start_date").Value)
	start_date_h = Hour(ds("SM_start_date").Value)
	end_date_y = Year(ds("SM_end_date").Value)
	end_date_m = Month(ds("SM_end_date").Value)
	end_date_d = Day(ds("SM_end_date").Value)
	limit_date_y = Year(ds("SM_limit_date").Value)
	limit_date_m = Month(ds("SM_limit_date").Value)
	limit_date_d = Day(ds("SM_limit_date").Value)
	limit_date_h = Hour(ds("SM_limit_date").Value)
	point_enquete = ds("SM_point_enquete").Value
	point_kansou = ds("SM_point_kansou").Value
End Sub

'=== メイン処理 ===
SetHeaderTitle "全配信 原稿＆発信条件登録"

Dim job_id, job_name, subject, body
Dim start_date_y, start_date_m, start_date_d, start_date_h, end_date_y, end_date_m, end_date_d
Dim limit_date_y, limit_date_m, limit_date_d, limit_date_h, point_enquete, point_kansou

job_id = Request.QueryString("id")
If IsEmpty(job_id) Then
	Response.Redirect "index.asp"
End If

ReadJob job_id

If IsNull(start_date_y) Then
	start_date_y = Year(Date)
	end_date_y = start_date_y
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
function InputCheck() {
	with (document.form1) {
		if (subject.value == "") {
			alert("タイトルを入力してください。");
			subject.focus();
			return false;
		}
		if (body.value == "") {
			alert("原稿を入力してください。");
			body.focus();
			return false;
		}
		if (start_date_y.value == "") {
			alert("配信期間（開始）を入力してください。");
			start_date_y.focus();
			return false;
		}
		if (end_date_y.value == "") {
			alert("配信期間（終了）を入力してください。");
			end_date_y.focus();
			return false;
		}
	}
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("原稿＆発信条件を登録します。よろしいですか？")) {
			document.form1.submit();
		}
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="zh_mail2.asp" name="form1">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■原稿＆発信条件情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">JOBID</td>
						<td class="n3"><%=job_id%></td>
					</tr>
					<tr>
						<td class="m3">ＪＯＢ名</td>
						<td class="n3"><%=ReplaceTag(job_name)%></td>
					</tr>
					<tr>
						<td class="m3">タイトル</td>
						<td class="n3">
							<input type="text" name="subject" size=80 maxlength=100 value="<%=HTMLEncode(subject)%>">
							<font class="note">（全角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">原稿</td>
						<td class="n3">
							<textarea name="body" cols=78 rows=20 class="np"><%=body%></textarea>
							<br><font class="note">（整理番号は必ず ★12345678/12345678☆ と入力してください）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">配信期間（開始）</td>
						<td class="n3">
							<select name="start_date_y"><%SelectYear2 start_date_y%></select>年&nbsp;
							<select name="start_date_m"><%SelectMonth start_date_m%></select>月&nbsp;
							<select name="start_date_d"><%SelectDay start_date_d%></select>日&nbsp;
							<select name="start_date_h"><%SelectHour start_date_h%></select>から
						</td>
					</tr>
					<tr>
						<td class="m3">配信期間（終了）</td>
						<td class="n3">
							<select name="end_date_y"><%SelectYear2 end_date_y%></select>年&nbsp;
							<select name="end_date_m"><%SelectMonth end_date_m%></select>月&nbsp;
							<select name="end_date_d"><%SelectDay end_date_d%></select>日まで
						</td>
					</tr>
					<tr>
						<td class="m3">締切り</td>
						<td class="n3">
							<select name="limit_date_y"><%SelectYear2 limit_date_y%></select>年&nbsp;
							<select name="limit_date_m"><%SelectMonth limit_date_m%></select>月&nbsp;
							<select name="limit_date_d"><%SelectDay limit_date_d%></select>日&nbsp;
							<select name="limit_date_h"><%SelectHour limit_date_h%></select>まで
						</td>
					</tr>
					<tr>
						<td class="m3">加算ポイント（ｱﾝｹｰﾄ）</td>
						<td class="n3"><input type="text" name="point_enquete" value="<%=point_enquete%>">&nbsp;ポイント</td>
					</tr>
					<tr>
						<td class="m3">加算ポイント（感想文）</td>
						<td class="n3"><input type="text" name="point_kansou" value="<%=point_kansou%>">&nbsp;ポイント</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" name="regist" value=" 登録 " Onclick="OnClick_regist()">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
