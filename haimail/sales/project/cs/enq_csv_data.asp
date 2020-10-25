<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:アンケートデータ取得CSV処理
'******************************************************

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
	Dim sql, ds

	sql = "SELECT JB_mail_id" & vbCrLf & _
				"FROM T_JOB" & vbCrLf & _
				"WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		mail_id = ds("JB_mail_id").Value
	End If
End Sub

'=== 質問数取得 ===
Private Sub GetQuestion(mail_id)
	Dim sql, ds, qno

	sql = "SELECT QU_question_no,QU_question_sel,QU_question_fa FROM T_QUESTION" & vbCrLf & _
				"WHERE QU_mail_id='" & mail_id & "' AND QU_question_type='E'" & vbCrLf & _
				"ORDER BY QU_question_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	question = ds.RecordCount
	ReDim q_sel(question)
	ReDim q_fa(question)
	Do Until ds.EOF
		qno = CInt(ds("QU_question_no").Value)
		q_sel(qno) = CInt(ds("QU_question_sel").Value)
		q_fa(qno) = CInt(ds("QU_question_fa").Value)
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim job_id, mail_id, question, csv, i, j, sql, ds, age, seq_no, q_sel(), q_fa(), tmp, ans, p1, p2

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id
GetQuestion mail_id

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

csv = "日時,ID,性別,年齢,年代,未既婚,職業,住所1,住所2"
For i = 1 To question
	csv = csv & ",質問" & i & "回答"
	If q_fa(i) > 0 Then
		If q_sel(i) > 0 Then
			csv = csv & ","
		End If
		For j = 2 To q_fa(i)
			csv = csv & ","
		Next
	End If
Next
Writeln csv

sql = "SELECT RM_seq_no,RM_recv_date,MM_member_id,MM_seibetsu,MM_seinengappi,MM_mikikon,SG_shokugyou_name,ZC_todoufuken,ZC_shikuchouson,AN_question_no,AN_answer_text,AS_answer_no" & vbCrLf & _
			"FROM T_RECV_MAIL,T_ANSWER,T_ANSWER_NO,T_MEMBER,T_ZIPCODE,T_SHOKUGYOU" & vbCrLf & _
			"WHERE RM_mail_id='" & mail_id & "' AND RM_enq_valid='1' AND RM_seq_no=AN_seq_no AND AN_question_type='E' AND AN_seq_no=AS_seq_no(+) AND AN_question_type=AS_question_type(+) AND AN_question_no=AS_question_no(+) AND RM_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq(+) AND MM_shokugyou=SG_shokugyou_code(+)" & vbCrLf & _
			"ORDER BY RM_seq_no,AN_question_no,AS_answer_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Do Until ds.EOF
	age = GetAge(ds("MM_seinengappi").Value)

	csv = ""
	If IsNull(ds("RM_recv_date").Value) Then
		SetCSV csv, " "
	Else
		SetCSV csv, TimeFormat(ds("RM_recv_date").Value)
	End If
	SetCSV csv, ds("MM_member_id").Value
	SetCSV csv, SeibetsuText(ds("MM_seibetsu").Value)
	SetCSV csv, age
	SetCSV csv, (age \ 10) * 10
	SetCSV csv, MikikonText(ds("MM_mikikon").Value)
	SetCSV csv, ds("SG_shokugyou_name").Value
	SetCSV csv, ds("ZC_todoufuken").Value
	SetCSV csv, ds("ZC_shikuchouson").Value

	seq_no = ds("RM_seq_no").Value
	For i = 1 To question
		tmp = ""
		If Not ds.EOF Then
			If ds("RM_seq_no").Value = seq_no And CLng(ds("AN_question_no").Value) = i Then
				ans = ds("AN_answer_text").Value
				If q_sel(i) > 0 Then
					Do While ds("RM_seq_no").Value = seq_no And CLng(ds("AN_question_no").Value) = i
						If tmp <> "" Then
							tmp = tmp & ","
						End If
						tmp= tmp & ds("AS_answer_no").Value
						ds.MoveNext
						If ds.EOF Then
							Exit Do
						End If
					Loop
					SetCSV csv, tmp
				Else
					ds.MoveNext
				End If

				If q_fa(i) > 0 Then
					ans = Replace(ans, vbCrLf, "")
					p1 = 1
					For j = 1 To q_fa(i)
						tmp = ""
						If p1 > 0 Then
							p1 = InStr(p1, ans, "｛")
							If p1 > 0 Then
								p2 = InStr(p1, ans, "｝")
								If p2 > 0 Then
									tmp = Trim(Mid(ans, p1 + 1, p2 - p1 - 1))
									p1 = p2
								End If
							End If
						End If
						SetCSV csv, tmp
					Next
				End If

				If q_sel(i) = 0 And q_fa(i) = 0 Then
					SetCSV csv, DeleteQuote(ans)
				End If
			Else
				SetCSV csv, tmp
			End If
		Else
			SetCSV csv, tmp
		End If
	Next
	Writeln csv
Loop
%>