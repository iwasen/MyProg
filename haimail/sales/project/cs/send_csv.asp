<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:発信者属性CSV処理
'******************************************************

'=== メイン処理 ===
Dim job_id, csv, sql, ds, age, seq

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

csv = "SEQ,ID,性別,年齢,年代,未既婚,職業,住所1,住所2,発信終了日時"
Writeln csv

sql = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ MM_member_id,MM_seibetsu,MM_seinengappi,MM_mikikon,SG_shokugyou_name,ZC_todoufuken,ZC_shikuchouson,SR_date" & vbCrLf & _
			"FROM T_JOB,T_SENDMAIL_RR,T_MEMBER,T_ZIPCODE,T_SHOKUGYOU" & vbCrLf & _
			"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SR_mail_id AND SR_status='1' AND SR_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq(+) AND MM_shokugyou=SG_shokugyou_code(+)" & vbCrLf & _
			"ORDER BY MM_member_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

seq = 1
Do Until ds.EOF
	age = GetAge(ds("MM_seinengappi").Value)

	csv = ""
	SetCSV csv, seq
	SetCSV csv, ds("MM_member_id").Value
	SetCSV csv, SeibetsuText(ds("MM_seibetsu").Value)
	SetCSV csv, age
	SetCSV csv, (age \ 10) * 10
	SetCSV csv, MikikonText(ds("MM_mikikon").Value)
	SetCSV csv, ds("SG_shokugyou_name").Value
	SetCSV csv, ds("ZC_todoufuken").Value
	SetCSV csv, ds("ZC_shikuchouson").Value
	SetCSV csv, TimeFormat(ds("SR_date").Value)

	Writeln csv

	seq = seq + 1
	ds.MoveNext
Loop
%>