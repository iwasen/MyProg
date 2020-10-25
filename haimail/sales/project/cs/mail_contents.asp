<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_header.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:メールコンテンツ画面処理
'******************************************************

'=== JOB情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_get_jushin,JB_get_kansou,JB_get_profile,JB_get_sa,JB_get_fa,JB_status,JB_mail_type,SM_subject,SM_body,SM_header,SM_footer,SM_impression,SM_limit_date,SM_start_date,SM_imp_limit,SM_mail_format,SM_status,SM_html_pict" & vbCrLf & _
				"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		subject = ds("SM_subject").Value
		body = ReadCLOB(ds("SM_body").Value)
		get_jushin = ds("JB_get_jushin").Value
		get_kansou = ds("JB_get_kansou").Value
		get_profile = ds("JB_get_profile").Value
		get_sa = ds("JB_get_sa").Value
		get_fa = ds("JB_get_fa").Value
		status = ds("JB_status").Value
		mail_type = ds("JB_mail_type").Value
		header = ds("SM_header").Value
		footer = ds("SM_footer").Value
		impression = ds("SM_impression").Value
		limit_date = ds("SM_limit_date").Value
		start_date = ds("SM_start_date").Value
		imp_limit = ds("SM_imp_limit").Value
		mail_format = NVL(ds("SM_mail_format").Value, "0")
		sm_status = NVL(ds("SM_status").Value, "0")
		html_pict = NVL(ds("SM_html_pict").Value, "0")
	End If
End Sub

'=== ヘッダ，フッタ取得 ===
Private Sub ReadHeaderFooter()
	Dim sql, ds, id

	If mail_format = "0" Then
		id = 1	'TEXT
	Else
		id = 2	'HTML
	End If

	If IsNull(subject) And IsNull(header) Then
		sql = "SELECT MH_text FROM T_MAIL_HEADER WHERE MH_header_id=" & id
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			header = ds(0).Value
			SetHeaderData
		End If
	End If

	If mail_type <> "TG" Then
		If IsNull(subject) And IsNull(impression) Then
			sql = "SELECT MI_text FROM T_MAIL_IMP WHERE MI_impression_id=" & id
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds.EOF Then
				impression = ds(0).Value
				SetImpressionData
			End If
		End If
	End If

	If IsNull(subject) And IsNull(footer) Then
		sql = "SELECT MF_text FROM T_MAIL_FOOTER WHERE MF_footer_id=" & id
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			footer = ds(0).Value
			SetFooterData
		End If
	End If

	If IsNull(subject) Then
		subject = "（＾＾）！"
	End If
End Sub

'=== ヘッダデータセット ===
Private Sub SetHeaderData()
	Dim sql, ds, name

	If InStr(header, HEADER_COMPANY) > 0 Or InStr(header, HEADER_TANTOUSHA) > 0 Then
		sql = "SELECT TS_name1,TS_name2,CM_name" & vbCrLf & _
					"FROM T_JOB,T_PROJECT,T_TANTOUSHA,T_COMPANY" & vbCrLf & _
					"WHERE JB_job_id=" & job_id & " AND JB_project_id=PJ_project_id AND PJ_tantousha_id=TS_tantousha_id AND TS_company_id=CM_company_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			header = Replace(header, HEADER_COMPANY, ds("CM_name").Value)

			name = ""
			If Not IsNull(ds("TS_name1").Value) Then
				name = name & ds("TS_name1").Value
			End If
			If Not IsNull(ds("TS_name2").Value) Then
				name = name & " " & ds("TS_name2").Value
			End If
			header = Replace(header, HEADER_TANTOUSHA, name)
		End If
	End If

	If InStr(header, HEADER_LIMIT) > 0 Then
		If Not IsNull(start_date) Then
			header = Replace(header, HEADER_LIMIT, LimitDateFormat(limit_date))
		End If
	End If
End Sub

'=== 感想文データセット ===
Private Sub SetImpressionData()
	If InStr(impression, IMPRESSION_LIMIT) > 0 Then
		If Not IsNull(start_date) Then
			impression = Replace(impression, IMPRESSION_LIMIT, ImpDateFormat(imp_limit))
		End If
	End If
End Sub

'=== フッタデータセット ===
Private Sub SetFooterData()
	Dim sql, ds

	If InStr(footer, FOOTER_KYOUMI) > 0 Then
		sql = "SELECT SE_kyoumi_genre1,SE_kyoumi_genre2" & vbCrLf & _
					"FROM T_JOB,T_SEARCH" & vbCrLf & _
					"WHERE JB_job_id=" & job_id & " AND JB_search_id=SE_search_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			footer = Replace(footer, FOOTER_KYOUMI, KyoumiGenreFormat(ds(0).Value, ds(1).Value))
		End If
	End If
End Sub

'=== 添付ファイル名取得 ===
Private Sub GetAttachment(job_id)
	Dim sql, ds, seq_no

	sql = "SELECT AT_mail_id,AT_seq_no,AT_file_name,AT_file_body" & vbCrLf & _
				"FROM T_ATTACHMENT,T_JOB" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND AT_content_type IS NULL AND JB_mail_id=AT_mail_id" & vbCrLf & _
				"ORDER BY AT_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	file_exist = "0"
	Do Until ds.EOF
		seq_no = CInt(ds("AT_seq_no").Value)
		file_name(seq_no) = ds("AT_file_name").Value
		file_size(seq_no) = ds("AT_file_body").Value.Size
		file_exist = "1"
		mail_id = ds("AT_mail_id").Value
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim job_id, subject, body, get_jushin, get_kansou, get_profile, get_sa, get_fa, status, header, footer, sm_status
Dim impression, limit_date, imp_limit, start_date, mail_type, a_type, mail_format, i
Dim file_name(5), file_size(5), file_exist, mail_id, html_pict

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	a_type = True
ElseIf CheckPermission(PM_REGIST_JOB_B) Then
	a_type = False
Else
	NoPermission
End If

ReadJob job_id
ReadHeaderFooter

GetAttachment job_id

SetHeaderTitle "メールコンテンツ登録"
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
	ShowHide('attachment_btn',document.form1.attachment.checked);
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("メールコンテンツを登録します。よろしいですか？")) {
			document.form1.action = "mail_contents2.asp";
			document.form1.target = "";
			document.form1.encoding = "multipart/form-data";
			document.form1.submit();
		}
	}
}
function OnClick_check() {
	if (InputCheck()) {
		document.form1.action = "mail_contents5.asp";
		document.form1.target = "mail_contents5";
		document.form1.submit();
	}
}
function InputCheck() {
	if (document.form1.subject.value == "") {
		alert("件名を入力してください。");
		document.form1.subject.focus();
		return false;
	}
	if (document.form1.body.value == "") {
		alert("本文を入力してください。");
		document.form1.body.focus();
		return false;
	}
	return true;
}
function OnClick_click() {
	document.form1.action = "mail_contents3.asp";
	document.form1.target = "mail_contents3";
	document.form1.submit();
}
function OnClick_contents() {
	document.form1.action = "mail_contents4.asp";
	document.form1.target = "mail_contents4";
	document.form1.submit();
}
function ShowHide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1" action="mail_contents2.asp">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<input type="hidden" name="mail_format" value="<%=mail_format%>">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">■メール内容を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="20%">件名</td>
						<td class="n3" width="80%">
							<input type="text" name="subject" size=80 maxlength=50 value="<%=HTMLEncode(subject)%>">
							<font class="note">（全角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">ヘッダ</td>
						<td class="n3"><textarea name="header" cols=78 rows=4 class="np"><%=HTMLEncode(header)%></textarea></td>
					</tr>
					<tr>
						<td class="m3">本文</td>
						<td class="n3">
							<textarea name="body" cols=78 rows=30 class="np"><%=HTMLEncode(NVL(body, ""))%></textarea>
<%If mail_format = "0" Then%>
							<br><font class="note">（整理番号は必ず ★12345678/12345678☆ と入力してください）</font>
<%End If%>
						</td>
					</tr>
<%If mail_type <> "TG" Then%>
					<tr>
						<td class="m3">感想文</td>
						<td class="n3"><textarea name="impression" cols=78 rows=10 class="np"><%=HTMLEncode(NVL(impression, ""))%></textarea></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3">フッタ</td>
						<td class="n3"><textarea name="footer" cols=78 rows=10 class="np"><%=HTMLEncode(footer)%></textarea></td>
					</tr>
<%If mail_format <> "0" Then%>
					<tr>
						<td class="m3">画像ファイル</td>
						<td class="n3">
							<input type="radio" name="html_pict"<%ValueChecked "0", html_pict%>>埋め込み方式
							<input type="radio" name="html_pict"<%ValueChecked "1", html_pict%>>リンク方式
							</td>
					</tr>
<%End If%>
					<tr>
						<td class="m3">添付ファイル</td>
						<td class="n3">
							<input type="checkbox" name="attachment" onclick="ShowHide('attachment_btn',checked)" <%ValueChecked "1", file_exist%>>指定する
							<span id="attachment_btn">&nbsp;
<%
For i = 1 To 5
	Writeln "<br><input type='file' name='attachment" & i & "' size=50>"
	If Not IsEmpty(file_name(i)) Then
		Writeln "<a href='attachment.asp?id=" & mail_id & "&seq=" & i & "' target='_blank'>" & file_name(i) & "</a> (" & NumFormat(file_size(i)) & " byte)"
	End If
Next
%>
							</span>
						</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">受信ポイント</td>
						<td class="n3"><input type="checkbox" name="get_jushin" <%ValueChecked "1", get_jushin%>>あり</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">感想文</td>
						<td class="n3"><input type="checkbox" name="get_kansou" <%ValueChecked "1", get_kansou%>>あり</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">個人情報取得</td>
						<td class="n3"><input type="checkbox" name="get_profile" <%ValueChecked "1", get_profile%>>あり</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">ＳＡ</td>
						<td class="n3"><input type="text" name="get_sa" size=3 value="<%=get_sa%>"> 問</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">ＦＡ</td>
						<td class="n3"><input type="text" name="get_fa" size=3 value="<%=get_fa%>"> 問</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If status = "0" Or (system_id = "center" And sm_status = "0") Then%>
	<input type="hidden" name="start_date">
	<input type="hidden" name="end_date">
	<input type="hidden" name="ct_id">
	<input type="hidden" name="url_name">
	<input type="hidden" name="jump_url">

	<input type="button" name="regist" value="　登録　" onclick="OnClick_regist()">
	<input type="button" name="check" value="ｺﾝﾃﾝﾂﾁｪｯｸ" onclick="OnClick_check()">
	<input type="reset" value="リセット">
	<input type="button" name="click" value="ｸﾘｯｸｶｳﾝﾀ設定" onclick="OnClick_click()">
<%End If%>
	<input type="button" value="発信ｺﾝﾃﾝﾂ表示" onclick="OnClick_contents()">
	<input type="button" value=" 戻る " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
