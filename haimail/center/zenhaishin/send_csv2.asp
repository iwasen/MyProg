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
Dim job_id, sql, ds, csv, seq, age
Dim csv_seq, csv_member_id, csv_seibetsu, csv_nenrei, csv_nendai, csv_shokugyou, csv_mikikon, csv_adr_kyojuu, csv_adr_kinmu, csv_zip_kyojuu, csv_zip_kinmu

job_id = Request.Form("job_id")
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

sql = "SELECT MM_member_id,MM_seibetsu,MM_seinengappi,SG_shokugyou_name,MM_mikikon,Z1.ZC_todoufuken Z1_todoufuken,Z2.ZC_todoufuken Z2_todoufuken,MM_zip_kyojuu,MM_zip_kinmu" & vbCrLf & _
			"FROM T_ZENHAISHIN,T_SENDMAIL_RR,T_MEMBER,T_SHOKUGYOU,T_ZIPCODE Z1,T_ZIPCODE Z2" & vbCrLf & _
			"WHERE ZH_job_id=" & job_id & " AND ZH_mail_id=SR_mail_id AND SR_status='1' AND SR_member_id=MM_member_id AND MM_shokugyou=SG_shokugyou_code(+) AND MM_zip_kyojuu=Z1.ZC_zip_uniq(+) AND MM_zip_kinmu=Z2.ZC_zip_uniq(+)" & vbCrLf & _
			"ORDER BY SR_date"
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
	Writeln csv

	seq = seq + 1
	ds.MoveNext
Loop
%>