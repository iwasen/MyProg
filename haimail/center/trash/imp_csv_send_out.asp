<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信感想文データ解析画面
'******************************************************

'=== メイン処理 ===
Dim job_id, csv
Dim csv_seq, csv_member_id, csv_seibetsu, csv_nenrei, csv_nendai, csv_shokugyou, csv_mikikon, csv_adr_kyojuu, csv_adr_kinmu, csv_zip_kyojuu, csv_zip_kinmu

job_id = Request.Cookies("job_id")
If IsEmpty(job_id) Then
  SystemError "job_id is empty"
End If

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
Writeln csv

sql = "SELECT MM_member_id,MM_seibetsu,MM_seinengappi,MM_shokugyou,MM_mikikon,MM_
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Do Until ds.EOF
	age = GetAge(ds("MM_seinengappi").Value)

	SetCSV csv, TimeFormat(ds("RM_recv_date").Value)
	SetCSV csv, ds("MM_member_id").Value
	SetCSV csv, SeibetsuText(ds("MM_seibetsu").Value)
	SetCSV csv, age
	SetCSV csv, (age \ 10) * 10
	SetCSV csv, MikikonText(ds("MM_mikikon").Value)
	SetCSV csv, ds("ZC_todoufuken").Value & ds("ZC_shikuchouson").Value

	seq_no = ds("RM_seq_no").Value
	For i = 1 To question
		If Not ds.EOF Then
			If ds("RM_seq_no").Value = seq_no And CLng(ds("AN_question_no").Value) = i Then
				If q_sel(i) = 0 Then
					OutputCSV True, ds("AN_answer_text").Value
					ds.MoveNext
				Else
					csv = ""
					Do While ds("RM_seq_no").Value = seq_no And CLng(ds("AN_question_no").Value) = i
						If csv <> "" Then
							csv = csv & ","
						End If
						csv = csv & ds("AS_answer_no").Value
						ds.MoveNext
					Loop
					OutputCSV True, csv
				End If
			Else
				OutputCSV True, ""
			End If
		Else
			OutputCSV True, ""
		End If
	Next
	Response.Write vbCrLf
	'ds.MoveNext
Loop
%>