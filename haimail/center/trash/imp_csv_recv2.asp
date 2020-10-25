<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信感想文データ解析画面
'******************************************************

'=== ジョブ情報取得 ===
Private Sub ReadJob(job_id)
  Dim sql, ds

  sql = "SELECT ZH_job_name,ZH_mail_id" & vbCrLf & _
				"FROM T_ZENHAISHIN" & vbCrLf & _
				"WHERE ZH_job_id=" & job_id
  Set ds = CreateDynaset(sql, ORADYN_READONLY)
  If Not ds.EOF Then
    job_name = ds("ZH_job_name").Value
		mail_id = ds("ZH_mail_id").Value
  End If
End Sub

'=== 質問数取得 ===
Private Sub GetQuestion(mail_id)
  Dim sql, ds

	sql = "SELECT QU_question_no,QU_question_sel FROM T_QUESTION" & vbCrLf & _
				"WHERE QU_mail_id='" & mail_id & "' AND QU_question_type='I'" & vbCrLf & _
				"ORDER BY QU_question_no"
  Set ds = CreateDynaset(sql, ORADYN_READONLY)
	question = ds.RecordCount
	ReDim q_sel(question)
	Do Until ds.EOF
		q_sel(CInt(ds("QU_question_no").Value)) = CInt(ds("QU_question_sel").Value)
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim job_id, job_name, mail_id, sql, ds, csv, seq, age, question, q_sel(), i, seq_no, tmp, valid
Dim csv_seq, csv_member_id, csv_seibetsu, csv_nenrei, csv_nendai, csv_shokugyou, csv_mikikon, csv_adr_kyojuu, csv_adr_kinmu, csv_zip_kyojuu, csv_zip_kinmu, csv_ans_date, csv_ans_data, csv_kaiseki

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
  SystemError "job_id is empty"
End If

ReadJob job_id
GetQuestion mail_id

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

csv_seq = Checkbox(Request.Form("csv_seq"))
csv_member_id = Checkbox(Request.Form("csv_member_id"))
csv_seibetsu = Checkbox(Request.Form("csv_seibetsu"))
csv_nenrei = Checkbox(Request.Form("csv_nenrei"))
csv_nendai = Checkbox(Request.Form("csv_nendai"))
csv_shokugyou = Checkbox(Request.Form("csv_shokugyou"))
csv_mikikon = Checkbox(Request.Form("csv_mikikon"))
csv_adr_kyojuu = Checkbox(Request.Form("csv_adr_kyojuu"))
csv_adr_kinmu = Checkbox(Request.Form("csv_adr_kinmu"))
csv_zip_kyojuu = Checkbox(Request.Form("csv_zip_kyojuu"))
csv_zip_kinmu = Checkbox(Request.Form("csv_zip_kinmu"))
csv_ans_date = Checkbox(Request.Form("csv_ans_date"))
csv_ans_data = Checkbox(Request.Form("csv_ans_data"))
csv_kaiseki = Checkbox(Request.Form("csv_kaiseki"))

csv = ""
If csv_seq Then
	SetCSV csv, "SEQ"
End If
If csv_member_id Then
	SetCSV csv, "はいめーるID"
End If
If csv_seibetsu Then
	SetCSV csv, "性別"
End If
If csv_nenrei Then
	SetCSV csv, "年齢"
End If
If csv_nendai Then
	SetCSV csv, "年代"
End If
If csv_shokugyou Then
	SetCSV csv, "職業"
End If
If csv_mikikon Then
	SetCSV csv, "未既婚"
End If
If csv_adr_kyojuu Then
	SetCSV csv, "都道府県（住所）"
End If
If csv_adr_kinmu Then
	SetCSV csv, "都道府県（勤務地）"
End If
If csv_zip_kyojuu Then
	SetCSV csv, "郵便番号（住所）"
End If
If csv_zip_kinmu Then
	SetCSV csv, "郵便番号（勤務地）"
End If
If csv_ans_date Then
	SetCSV csv, "回答日"
End If
If csv_ans_data Then
	For i = 1 To question
		SetCSV csv, "回答内容" & i
	Next
End If
If csv_kaiseki Then
	SetCSV csv, "解析結果"
End If
Writeln csv

sql = "SELECT RM_seq_no,MM_member_id,MM_seibetsu,MM_seinengappi,SG_shokugyou_name,MM_mikikon,Z1.ZC_todoufuken Z1_todoufuken,Z2.ZC_todoufuken Z2_todoufuken,MM_zip_kyojuu,MM_zip_kinmu,RM_recv_date,AN_question_no,AN_answer_text,AS_answer_no,AN_answer_valid,AN_answer_invalid" & vbCrLf & _
			"FROM T_RECV_MAIL,T_ANSWER,T_ANSWER_NO,T_MEMBER,T_SHOKUGYOU,T_ZIPCODE Z1,T_ZIPCODE Z2" & vbCrLf & _
			"WHERE RM_mail_id='" & mail_id & "' AND RM_imp_valid='1' AND RM_seq_no=AN_seq_no AND AN_question_type='I' AND AN_seq_no=AS_seq_no(+) AND AN_question_type=AS_question_type(+) AND AN_question_no=AS_question_no(+) AND RM_member_id=MM_member_id AND MM_shokugyou=SG_shokugyou_code(+) AND MM_zip_kyojuu=Z1.ZC_zip_uniq(+) AND MM_zip_kinmu=Z2.ZC_zip_uniq(+)" & vbCrLf & _
			"ORDER BY RM_seq_no,AN_question_no,AS_answer_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

seq = 1
Do Until ds.EOF
	age = GetAge(ds("MM_seinengappi").Value)

	csv = ""
	If csv_seq Then
		SetCSV csv, seq
	End If
	If csv_member_id Then
		SetCSV csv, ds("MM_member_id").Value
	End If
	If csv_seibetsu Then
		SetCSV csv, SeibetsuText(ds("MM_seibetsu").Value)
	End If
	If csv_nenrei Then
		SetCSV csv, age
	End If
	If csv_nendai Then
		SetCSV csv, (age \ 10) * 10
	End If
	If csv_shokugyou Then
		SetCSV csv, ds("SG_shokugyou_name").Value
	End If
	If csv_mikikon Then
		SetCSV csv, MikikonText(ds("MM_mikikon").Value)
	End If
	If csv_adr_kyojuu Then
		SetCSV csv, ds("Z1_todoufuken").Value
	End If
	If csv_adr_kinmu Then
		SetCSV csv, ds("Z2_todoufuken").Value
	End If
	If csv_zip_kyojuu Then
		SetCSV csv, ds("MM_zip_kyojuu").Value
	End If
	If csv_zip_kinmu Then
		SetCSV csv, ds("MM_zip_kinmu").Value
	End If
	If csv_ans_date Then
		SetCSV csv, DateFormat(ds("RM_recv_date").Value)
	End If

	seq_no = ds("RM_seq_no").Value
	valid = "○"
	For i = 1 To question
		tmp = ""
		If Not ds.EOF Then
			If ds("RM_seq_no").Value = seq_no And CLng(ds("AN_question_no").Value) = i Then
				If ds("AN_answer_valid").Value = "1" Then
				Else
					If valid = "○" Then
						valid = "△"
					End If
				End If

				If ds("AN_answer_invalid").Value = "1" Then
					valid = "×"
				End If

				If q_sel(i) = 0 Then
					tmp = DeleteQuote(ds("AN_answer_text").Value)
					ds.MoveNext
				Else
					Do Until ds.EOF
						If ds("RM_seq_no").Value <> seq_no Or CLng(ds("AN_question_no").Value) <> i Then
							Exit Do
						End If
						If tmp <> "" Then
							tmp = tmp & ","
						End If
						tmp= tmp & ds("AS_answer_no").Value
						ds.MoveNext
					Loop
				End If
			Else
				If valid = "○" Then
					valid = "△"
				End If
			End If
		End If
		If csv_ans_data Then
			SetCSV csv, tmp
		End If
	Next

	If csv_kaiseki Then
		SetCSV csv, valid
	End If

	Writeln csv

	seq = seq + 1
Loop
%>