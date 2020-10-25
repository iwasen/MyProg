<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:プロジェクト作成修正画面処理
'******************************************************

'=== プロジェクト情報取得 ===
Private Sub ReadProject(project_id)
	Dim sql, ds

	sql = "SELECT PJ_project_name,PJ_tantousha_id,PJ_summary FROM T_PROJECT WHERE PJ_project_id=" & project_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_name = ds("PJ_project_name").Value
		tantousha_id = ds("PJ_tantousha_id").Value
		summary = ds("PJ_summary").Value
	End If
End Sub

'=== メイン処理 ===
Dim project_id, title, project_name, summary, tantousha_id

project_id = Request.QueryString("project_id")
If IsEmpty(project_id) Then
	title = "プロジェクト新規作成"
	tantousha_id = Request.QueryString("tantousha_id")
Else
	title = "プロジェクト情報変更"
	ReadProject project_id
End If

SetHeaderTitle title
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
	document.form1.project_name.focus();
}
function OnSubmit_form1() {
	if (document.form1.action.value == "")
		return false;
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("プロジェクトを登録します。よろしいですか？")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("プロジェクトを更新します。よろしいですか？")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (confirm("プロジェクトを削除します。よろしいですか？")) {
		document.form1.action.value = "delete";
		document.form1.submit();
	}
}
function InputCheck() {
	if (document.form1.project_name.value == "") {
		alert("プロジェクト名を入力してください。");
		document.form1.project_name.focus();
		return false;
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="pjt_name2.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="project_id" value="<%=project_id%>">
	<input type="hidden" name="tantousha_id" value="<%=tantousha_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■プロジェクト名・プロジェクト概要を入力して下さい。</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%If Not IsEmpty(project_id) Then%>
					<tr>
						<td class="m3" width="25%">プロジェクトID</td>
						<td class="n3"><%=project_id%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3" width="25%">プロジェクト名</td>
						<td class="n3"><input type="text" name="project_name" size=80 maxlength=50 value="<%=HTMLEncode(project_name)%>"></td>
					</tr>
					<tr>
						<td class="m3" width="25%">プロジェクト概要</td>
						<td class="n3"><textarea name="summary" cols=70 rows=10><%=summary%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<%If IsEmpty(project_id) Then%>
	<input type="button" name="regist" value=" 登録 " onclick="OnClick_regist()">
<%Else%>
	<%If CheckPermission(PM_PROJECT_UPDATE) Then%>
	<input type="button" name="update" value=" 更新 " onclick="OnClick_update()">
	<%End If%>
	<%If CheckPermission(PM_PROJECT_DELETE) Then%>
	<input type="button" name="delete" value=" 削除 " onclick="OnClick_delete()">
	<%End If%>
<%End If%>
<%If CheckPermission(PM_PROJECT_UPDATE) Then%>
	<input type="reset" value="リセット">
<%End If%>
	<input type="button" value=" 戻る " onclick="history.back()">
	<input type="hidden" name="action">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
