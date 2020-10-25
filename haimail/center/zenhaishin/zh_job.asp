<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信JOB登録画面
'******************************************************

'=== ＤＢよりデータ読み込み ===
Private Sub ReadJob(id)
	Dim sql, ds

	sql = "SELECT ZH_job_name,ZH_notify_adr FROM T_ZENHAISHIN WHERE ZH_job_id=" & id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Redirect "index.asp"
	End If

	job_name = ds("ZH_job_name").Value
	notify_adr = ds("ZH_notify_adr").Value
End Sub

'=== 過去のJOBの選択 ===
Private Sub SelectJob()
	Dim sql, ds

	sql = "SELECT ZH_job_id,ZH_job_name FROM T_ZENHAISHIN ORDER BY ZH_job_id DESC"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''>- 過去のJOB名から選択 -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("ZH_job_id").Value & "'>" & ds("ZH_job_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
SetHeaderTitle "全配信JOB登録"

Dim job_id, job_name, notify_adr, cp

notify_adr = "yes@haimail.net"

job_id = Request.QueryString("id")
If Not IsEmpty(job_id) Then
	ReadJob job_id
End If

cp = Request.QueryString("cp")
If Not IsEmpty(cp) Then
	ReadJob cp
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
		if (job_name.value == "") {
			alert("ＪＯＢ名を入力してください。");
			job_name.focus();
			return false;
		}
		if (notify_adr.value == "") {
			alert("発信通知先アドレスを入力してください。");
			notify_adr.focus();
			return false;
		}
	}
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("ＪＯＢを登録します。よろしいですか？")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("ＪＯＢを更新します。よろしいですか？")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (InputCheck()) {
		if (confirm("ＪＯＢを削除します。よろしいですか？")) {
			document.form1.action.value = "delete";
			document.form1.submit();
		}
	}
}
function OnChange_copy() {
	if (document.form1.copy.value != "")
		location.href = "zh_job.asp?cp=" + document.form1.copy.value
}
//-->
</SCRIPT>
</head>
<body onload="document.form1.job_name.focus()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="zh_job2.asp" name="form1">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td class="m0">■ＪＯＢ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
<%
If Not IsEmpty(job_id) Then
%>
					<tr>
						<td class="m3" width="20%">JOBID</td>
						<td class="n3"><%=job_id%></td>
					</tr>
<%
End If
%>
					<tr>
						<td class="m3">ＪＯＢ名</td>
						<td class="n3">
							<input type="text" name="job_name" size=80 maxlength=50 value="<%=HTMLEncode(job_name)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">送信元アドレス</td>
						<td class="n3">
							<input type="text" name="notify_adr" size=80 maxlength=50 value="<%=HTMLEncode(notify_adr)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
<%
If IsEmpty(job_id) Then
%>
					<tr>
						<td class="m3">コピー</td>
						<td class="n3"><select name="copy" onchange="OnChange_copy()"><%SelectJob%></select></td>
					</tr>
<%
End If
%>
				</table>
			</td>
		</tr>
	</table>

	<br>
<%
If IsEmpty(job_id) Then
%>
	<input type="button" name="regist" value=" 登録 " Onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" 更新 " Onclick="OnClick_update()">
	<input type="button" name="delete" value=" 削除 " Onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
	<input type="hidden" name="action">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
