<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<!--#INCLUDE FILE="../../inc/mail_header.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:発信条件登録画面
'******************************************************

'=== 複数チェックボックス一括取得納 ===
Private Function GetMultiFlag(name, n)
	Redim a(n)
	Dim i, c, data

	For i = 0 To n - 1
		a(i) = "0"
	Next

	Set data = Request.Form(name)
	If Not IsEmpty(data.Item) Then
		For Each c In data
			a(c - 1) = "1"
		Next
	End If

	GetMultiFlag = Join(a, "")
End Function

'=== 日付の変換 ===
Private Function ConvertDate(y, m, d, h)
	Dim ymd

	If y = "" Then
		ConvertDate = Null
	Else
		ymd = y & "/" & m & "/" & d
		If h = "-1" Then
			ConvertDate = CDate(ymd & " 23:59:59")	'終日
		Else
			ConvertDate = CDate(ymd & " " & h & ":0:0")
		End If
	End If
End Function

'=== 発信条件をDBに格納 ===
Private Sub RegistSendCondition(job_id)
	Dim start_date, end_date, limit_date, imp_limit, mail_id, search_id, teikei_qno, teikei_andor, teikei_ans, ct_id, status
	Dim sql, ds

	start_date = ConvertDate(Request.Form("start_date_y"), Request.Form("start_date_m"), Request.Form("start_date_d"), Request.Form("start_date_h"))
	If Request.Form("end_date_y") <> "" Then
		end_date = ConvertDate(Request.Form("end_date_y"), Request.Form("end_date_m"), Request.Form("end_date_d"), Request.Form("end_date_h"))
	Else
		end_date = DateAdd("d", 1, start_date)
	End If

	limit_date = ConvertDate(Request.Form("limit_date_y"), Request.Form("limit_date_m"), Request.Form("limit_date_d"), Request.Form("limit_date_h"))
	imp_limit = ConvertDate(Request.Form("imp_limit_y"), Request.Form("imp_limit_m"), Request.Form("imp_limit_d"), Request.Form("imp_limit_h"))

	ct_id = JoinItem(Request.Form("ct_id"))

	BeginTrans

	sql = "SELECT * FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If Not ds.EOF Then
		teikei_qno = ToHankaku(Request.Form("teikei_qno"))
		teikei_andor = Request.Form("teikei_andor")
		teikei_ans = GetMultiFlag("teikei_ans", 15)

		ds.Edit
		ds("JB_send_timing").Value = Request.Form("send_timing")
		ds("JB_send_timing_h").Value = Request.Form("send_timing_h")
		ds("JB_send_timing_d").Value = Request.Form("send_timing_d")
		ds("JB_teikei_qno").Value = teikei_qno
		ds("JB_teikei_andor").Value = teikei_andor
		ds("JB_teikei_ans").Value = teikei_ans
		ds("JB_ct_id").Value = ct_id

		If IsNull(ds("JB_mail_id").Value) Then
			mail_id = MakeMailID
			ds("JB_mail_id").Value = mail_id
		Else
			mail_id = ds("JB_mail_id").Value
		End If

		If IsNull(ds("JB_search_id").Value) Then
			search_id = GetNextVal("SE_search_id")
			ds("JB_search_id").Value = search_id
		Else
			search_id = ds("JB_search_id").Value
		End If

		status = ds("JB_status").Value
		ds.Update

		sql = "SELECT * FROM T_SEND_MAIL WHERE SM_mail_id='" & mail_id & "'"
		Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
		If ds.EOF Then
			ds.AddNew
			ds("SM_mail_id").Value = mail_id
			ds("SM_from").Value = GetFromAddress("S")
			ds("SM_body").Value = Empty
			ds("SM_permit").Value = "0"
			ds("SM_status").Value = "0"
			ds("SM_real_send").Value = 0
		Else
			ds.Edit
			If status = "0" And CLng(ToHankaku(Request.Form("send_num"))) > CLng(NVL(ds("SM_real_send").Value, "0")) Then
				ds("SM_status").Value = "0"
			End If
		End If
		ds("SM_mail_kind").Value = "S"
		ds("SM_send_num").Value = ToHankaku(Request.Form("send_num"))
		ds("SM_start_date").Value = start_date
		ds("SM_end_date").Value = end_date
		ds("SM_limit_date").Value = limit_date
		ds("SM_imp_limit").Value = imp_limit
		ds("SM_sql").Value = Empty
		ds("SM_bunkatsu_kankaku").Value = ToHankaku(Request.Form("bunkatsu_kankaku"))
		ds("SM_bunkatsu_kaisuu").Value = ToHankaku(Request.Form("bunkatsu_kaisuu"))
		ds("SM_point_jushin").Value = ToHankaku(Request.Form("point_jushin"))
		ds("SM_point_kansou").Value = ToHankaku(Request.Form("point_kansou"))
		ds("SM_point_enquete").Value = ToHankaku(Request.Form("point_enquete"))
		ds("SM_virgin_mail").Value = Request.Form("virgin_mail")
		ds("SM_virgin_day").Value = Request.Form("virgin_day")
		ds("SM_virgin_hour").Value = Request.Form("virgin_hour")
		SetHeaderFooter ds, limit_date, imp_limit
		ds.Update

		ds.MoveLast
		ds.Edit
		ds("SM_sql").Value.Write search.MakeSQL2(job_id, teikei_qno, teikei_andor, teikei_ans, ct_id)
		ds.Update

		search.WriteDB search_id
	End If

	CommitTrans
End Sub

'=== ヘッダ，フッタデータ設定 ===
Private Sub SetHeaderFooter(ds, limit_date, imp_limit)
	Dim str

	str = ds("SM_header").Value
	If Not IsNull(str) Then
		If InStr(str, HEADER_LIMIT) > 0 Then
			ds("SM_header").Value = Replace(str, HEADER_LIMIT, LimitDateFormat(limit_date))
		End If
	End If

	str = ds("SM_impression").Value
	If Not IsNull(str) Then
		If InStr(str, HEADER_LIMIT) > 0 Then
			ds("SM_impression").Value = Replace(str, IMPRESSION_LIMIT, ImpDateFormat(imp_limit))
		End If
	End If

	str = ds("SM_footer").Value
	If Not IsNull(str) Then
		If InStr(str, FOOTER_KYOUMI) > 0 Then
			ds("SM_footer").Value = Replace(str, FOOTER_KYOUMI, KyoumiGenreFormat(search.kyoumi_genre1, search.kyoumi_genre2))
		End If
	End If
End Sub

'=== Fromアドレス取得 ===
Private Function GetFromAddress(mail_kind)
	Dim sql,ds

	sql = "SELECT MA_mail_adr FROM T_MAIL_TYPE WHERE MA_mail_kind='" & mail_kind & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		SystemError "not found mail_kind"
	End If

	GetFromAddress = ds(0).Value
End Function

'=== メイン処理 ===
Dim job_id, search, msg

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

Set search = New CSearch
search.GetData

'インプレスを発信対象外とする（２月末までの限定処置）====
'If system_id = "sales" Then
'	If search.jogai_baitai <> "" Then
'		search.jogai_baitai = search.jogai_baitai & ","
'	End If
'	search.jogai_baitai = search.jogai_baitai & "30"
'End If
'====================================================

RegistSendCondition job_id

SetHeaderTitle "発信条件登録"
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
<p>発信条件を登録しました。</p>
<input type="button" value=" OK " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
