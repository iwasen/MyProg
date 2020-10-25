<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:クリックカウンタ登録画面処理
'******************************************************

'=== クリックカウンタ情報取得 ===
Private Sub ReadClickCount(ct_id)
	Dim sql, ds

	sql = "SELECT CC_url_name,CC_start_date,CC_end_date,CC_jump_url FROM T_CLICK_COUNT WHERE CC_ct_id=" & ct_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		url_name = ds("CC_url_name").Value
		start_date = ds("CC_start_date").Value
		end_date = ds("CC_end_date").Value
		jump_url = ds("CC_jump_url").Value
	End If
End Sub

'=== メイン処理 ===
Dim job_id, ct_id, title, url_name, jump_url, start_date, end_date
Dim start_date_y, start_date_m, start_date_d, end_date_y, end_date_m, end_date_d

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ct_id = Request.QueryString("ct_id")
If IsEmpty(ct_id) Then
	title = "クリックカウンタ新規作成"
Else
	title = "クリックカウンタ変更"
	ReadClickCount ct_id

	If Not IsNull(start_date) Then
		start_date_y = Year(start_date)
		start_date_m = Month(start_date)
		start_date_d = Day(start_date)
	End If

	If Not IsNull(end_date) Then
		end_date_y = Year(end_date)
		end_date_m = Month(end_date)
		end_date_d = Day(end_date)
	End If
End If

SetHeaderTitle title
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
	document.form1.url_name.focus();
}
function OnSubmit_form1() {
	if (document.form1.action.value == "")
		return false;
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("クリックカウンタを登録します。よろしいですか？")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("クリックカウンタを更新します。よろしいですか？")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (confirm("クリックカウンタを削除します。よろしいですか？")) {
		document.form1.action.value = "delete";
		document.form1.submit();
	}
}
function InputCheck() {
	if (document.form1.url_name.value == "") {
		alert("URL名称を入力してください。");
		document.form1.url_name.focus();
		return false;
	}
	if (document.form1.jump_url.value == "") {
		alert("飛び先URLを入力してください。");
		document.form1.jump_url.focus();
		return false;
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1" action="click_count3.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<input type="hidden" name="ct_id" value="<%=ct_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■クリックカウンタ情報を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%
If Not IsEmpty(ct_id) Then
%>
					<tr>
						<td class="m3" width="25%">CTID</td>
						<td class="n3"><%=ct_id%></td>
					</tr>
<%
End If
%>
					<tr>
						<td class="m3" width="25%">URL名称</td>
						<td class="n3"><input type="text" name="url_name" size=80 maxlength=100 value="<%=HTMLEncode(url_name)%>"></td>
					</tr>
					<tr>
						<td class="m3" width="25%">飛び先URL</td>
						<td class="n3">
							<input type="text" name="jump_url" size=80 maxlength=200 value="<%=HTMLEncode(jump_url)%>">
							<input type="button" value="表示" onclick="window.open(document.form1.jump_url.value)">
						</td>
					</tr>
					<tr>
						<td class="m3">カウンタ期間(開始)</td>
						<td class="n3">
							<select name="start_date_y"><%SelectYear2 start_date_y%></select>年&nbsp;
							<select name="start_date_m"><%SelectMonth start_date_m%></select>月&nbsp;
							<select name="start_date_d"><%SelectDay start_date_d%></select>日&nbsp;<font class="note">（開始を指定しない場合は年をブランクにしてください）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">カウンタ期間(終了)</td>
						<td class="n3">
							<select name="end_date_y"><%SelectYear2 end_date_y%></select>年&nbsp;
							<select name="end_date_m"><%SelectMonth end_date_m%></select>月&nbsp;
							<select name="end_date_d"><%SelectDay end_date_d%></select>日&nbsp;<font class="note">（終了を指定しない場合は年をブランクにしてください）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%
If IsEmpty(ct_id) Then
%>
	<input type="button" name="regist" value=" 登録 " onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" 更新 " onclick="OnClick_update()">
	<input type="button" name="delete" value=" 削除 " onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
	<input type="hidden" name="action">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
