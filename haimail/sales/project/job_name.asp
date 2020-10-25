<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:ジョブ作成修正画面処理
'******************************************************

'=== プロジェクト情報取得 ===
Private Sub ReadProject(project_id)
	Dim sql, ds

	sql = "SELECT PJ_project_name,PJ_tantousha_id FROM T_PROJECT WHERE PJ_project_id=" & project_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		project_name = ds("PJ_project_name").Value
		tantousha_id = ds("PJ_tantousha_id").Value
	End If
End Sub

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_job_name,JB_mail_type,JB_parent_job_id,JB_mail_id,JB_job_type,SM_mail_format" & vbCrLf & _
				"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		job_name = ds("JB_job_name").Value
		parent_job_id = ds("JB_parent_job_id").Value
		mail_type = ds("JB_mail_type").Value
		mail_format = NVL(ds("SM_mail_format").Value, "0")
		job_type = ds("JB_job_type").Value
		ReadSendNotice ds("JB_mail_id").Value
	End If
End Sub

'=== 発信通知先アドレス取得 ===
Private Sub ReadSendNotice(mail_id)
	Dim sql, ds, i

	sql = "SELECT SN_mail_adr FROM T_SEND_NOTICE WHERE SN_mail_id='" & mail_id & "' ORDER BY SN_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	For i = 0 To 3
		If ds.EOF Then
			Exit For
		End If
		send_notice(i) = ds(0).Value
		ds.MoveNext
	Next
End Sub

'=== 通知先メールアドレス取得 ===
Private Sub SetNoticeAdr()
	Dim sql, ds

	Select Case g_login_type
	Case LOGIN_AGENT
		sql = "SELECT OP_mail_adr FROM T_OPERATOR WHERE OP_operator_id='" & g_login_id & "'"
	Case LOGIN_CLIENT
		sql = "SELECT TS_mail_adr FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	End Select

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		send_notice(0) = ds(0).Value
	End If
End Sub

'=== メール形式選択 ===
Private Sub SelectMailFormat(mail_format)
	Writeln "<option value=''" & Selected("", mail_format) & ">- 選択してください -</option>"
	Writeln "<option value='0'" & Selected("0", mail_format) & ">テキスト</option>"
	Writeln "<option value='1'" & Selected("1", mail_format) & ">テキストHTML</option>"
	Writeln "<option value='2'" & Selected("2", mail_format) & ">HTML</option>"
End Sub

'=== メール種類選択 ===
Private Sub SelectMailType(mail_type)
	Writeln "<option value=''" & Selected("", mail_type) & ">- 選択してください -</option>"
	Writeln "<option value='LT'" & Selected("LT", mail_type) & ">手紙</option>"
	Writeln "<option value='HG'" & Selected("HG", mail_type) & ">葉書</option>"
	Writeln "<option value='CR'" & Selected("CR", mail_type) & ">チラシ</option>"
	Writeln "<option value='OH'" & Selected("OH", mail_type) & ">往復葉書</option>"
	Writeln "<option value='TG'" & Selected("TG", mail_type) & ">電報</option>"
End Sub

'=== 親ジョブ選択 ===
Private Sub SelectJob(project_id, job_id, parent_job_id)
	Dim sql, ds

	sql = "SELECT JB_job_id,JB_job_name FROM T_JOB WHERE JB_project_id=" & project_id
	If Not IsEmpty(job_id) Then
		sql = sql & " AND JB_job_id<>" & job_id
	End If
	sql = sql & " ORDER BY JB_job_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(parent_job_id, "") & ">- 選択してください -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("JB_job_id").Value & "'" & Selected(parent_job_id, ds("JB_job_id").Value) & ">" & ds("JB_job_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim title, tantousha_id, project_id, project_name, job_id, job_name, parent_job_id, job_type, mail_type, copy_job, mail_format
Dim disp_parent1, disp_parent2
Dim send_notice(3)

project_id = Request.QueryString("project_id")
If Not IsEmpty(project_id) Then
	ReadProject project_id
End If

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	title = "ジョブ新規作成"
	tantousha_id = Request.QueryString("tantousha_id")
	copy_job = Request.QueryString("cp")
	if IsEmpty(copy_job) Then
		SetNoticeAdr
		job_type = "0"
	Else
		ReadJob copy_job
	End If
Else
	title = "ジョブ情報変更"
	ReadJob job_id
End If

If job_type = "1" Then
	disp_parent1 = "1"
ElseIf job_type = "2" Then
	disp_parent2 = "1"
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
	document.form1.job_name.focus();
}
function OnSubmit_form1() {
	if (document.form1.action.value == "")
		return false;
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("ジョブを登録します。よろしいですか？")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("ジョブを更新します。よろしいですか？")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (confirm("ジョブを削除します。よろしいですか？")) {
		document.form1.action.value = "delete";
		document.form1.submit();
	}
}
function InputCheck() {
	if (document.form1.job_name.value == "") {
		alert("ジョブ名を入力してください。");
		document.form1.job_name.focus();
		return false;
	}
	if (document.form1.mail_format.value == "") {
		alert("メール形式を選択してください。");
		document.form1.mail_format.focus();
		return false;
	}
	if (document.form1.mail_type.value == "") {
		alert("メール種類を選択してください。");
		document.form1.mail_type.focus();
		return false;
	}
<%If CheckPermission(PM_REGIST_PROJECT) Then%>
	if (document.form1.job_type[1].checked) {
		if (document.form1.parent_job_id1.value == "") {
			alert("親ジョブを指定してください。");
			document.form1.parent_job_id1.focus();
			return false;
		}
	} else if (document.form1.job_type[2].checked) {
		if (document.form1.parent_job_id2.value == "") {
			alert("親ジョブを指定してください。");
			document.form1.parent_job_id2.focus();
			return false;
		}
	}
<%End If%>
	return true;
}
function OnChange_job_type(type) {
	document.all("parent_job1").style.display = "none";
	document.all("parent_job2").style.display = "none";
	if (type == 1)
		document.all("parent_job1").style.display = "";
	else if (type == 2)
		document.all("parent_job2").style.display = "";
}
function OnClick_copy() {
	window.open("job_copy.asp", "job_copy", "resizable=yes,scrollbars=yes,width=600");
}
function CopyJob(job_id) {
	location.replace("job_name.asp?<%If Not IsEmpty(project_id) Then Response.Write "project_id=" & project_id & "&"%>tantousha_id=<%=tantousha_id%>&cp=" + job_id);
}
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="job_name2.asp" onsubmit="return OnSubmit_form1()">
	<input type="hidden" name="tantousha_id" value="<%=tantousha_id%>">
	<input type="hidden" name="copy_job" value="<%=copy_job%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
<%If CheckPermission(PM_REGIST_PROJECT) Then%>
		<tr>
			<td class="m0">■プロジェクト</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="25%">プロジェクトID</td>
						<td class="n3"><input type="hidden" name="project_id" value="<%=project_id%>"><%=project_id%></td>
					</tr>
					<tr>
						<td class="m3" width="25%">プロジェクト名</td>
						<td class="n3"><%=ReplaceTag(project_name)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
<%End If%>
		<tr>
			<td class="m0">■ジョブ情報を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
<%If Not IsEmpty(job_id) Then%>
					<tr>
						<td class="m3" width="25%">ジョブID</td>
						<td class="n3"><input type="hidden" name="job_id" value="<%=job_id%>"><%=job_id%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3" width="25%">ジョブ名</td>
						<td class="n3">
							<input type="text" name="job_name" size=70 maxlength=50 value="<%=HTMLEncode(job_name)%>">
							<font class="note">（全角５０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">メール形式</td>
						<td class="n3"><select name="mail_format"><%SelectMailFormat mail_format%></select></td>
					</tr>
					<tr>
						<td class="m3" width="25%">メール種類</td>
						<td class="n3"><select name="mail_type"><%SelectMailType mail_type%></select></td>
					</tr>
<%If CheckPermission(PM_REGIST_PROJECT) Then%>
					<tr>
						<td class="m3" width="25%">ジョブ種別</td>
						<td class="n3">
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
									<td><input type="radio" name="job_type"<%ValueChecked "0", job_type%> onclick="OnChange_job_type(0)">発信JOB</td>
									<td></td>
								</tr>
								<tr>
									<td><input type="radio" name="job_type"<%ValueChecked "1", job_type%> onclick="OnChange_job_type(1)">アンケートレスポンスJOB</td>
									<td id="parent_job1"<%=Display(disp_parent1)%>>
										&nbsp;&nbsp;親JOB指定
										<select name="parent_job_id1"><%SelectJob project_id, job_id, parent_job_id%></select>
									</td>
								</tr>
								<tr>
									<td><input type="radio" name="job_type"<%ValueChecked "2", job_type%> onclick="OnChange_job_type(2)">クリックレスポンスJOB</td>
									<td id="parent_job2"<%=Display(disp_parent2)%>>
										&nbsp;&nbsp;親JOB指定
										<select name="parent_job_id2"><%SelectJob project_id, job_id, parent_job_id%></select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
<%End If%>
					<tr>
						<td class="m3" width="25%">発信通知先アドレス</td>
						<td class="n3">
							<input type="text" name="send_notice" size=50 maxlength=50 value="<%=HTMLEncode(send_notice(0))%>"><br>
							<input type="text" name="send_notice" size=50 maxlength=50 value="<%=HTMLEncode(send_notice(1))%>"><br>
							<input type="text" name="send_notice" size=50 maxlength=50 value="<%=HTMLEncode(send_notice(2))%>"><br>
							<input type="text" name="send_notice" size=50 maxlength=50 value="<%=HTMLEncode(send_notice(3))%>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If IsEmpty(job_id) Then%>
	<input type="button" name="regist" value=" 登録 " onclick="OnClick_regist()">
	<input type="button" name="copy" value="コピー" onclick="OnClick_copy()">
<%Else%>
	<%If CheckPermission(PM_JOB_UPDATE) Then%>
	<input type="button" name="update" value=" 更新 " onclick="OnClick_update()">
	<%End If%>
	<%If CheckPermission(PM_JOB_DELETE) Then%>
	<input type="button" name="delete" value=" 削除 " onclick="OnClick_delete()">
	<%End If%>
<%End If%>
<%If CheckPermission(PM_JOB_UPDATE) Then%>
	<input type="reset" value="リセット">
<%End If%>
	<input type="button" value=" 戻る " onclick="history.back()">
	<input type="hidden" name="action">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
