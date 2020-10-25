<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�NCSV�擾����
'******************************************************

'=== ���C������ ===
Dim job_id, csv, sql, ds, age

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

csv = "����,ID,����,�N��,�N��,������,�E��,�Z��1,�Z��2,CTID"
Writeln csv

sql = "SELECT CC_ct_id,MC_date,MM_member_id,MM_seibetsu,MM_seinengappi,MM_mikikon,SG_shokugyou_name,ZC_todoufuken,ZC_shikuchouson" & vbCrLf & _
			"FROM T_CLICK_COUNT,T_MAIL_CLICK,T_MEMBER,T_ZIPCODE,T_SHOKUGYOU" & vbCrLf & _
			"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id AND MC_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq(+) AND MM_shokugyou=SG_shokugyou_code(+)" & vbCrLf & _
			"ORDER BY MC_seq_no"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Do Until ds.EOF
	age = GetAge(ds("MM_seinengappi").Value)

	csv = ""
	SetCSV csv, TimeFormat(ds("MC_date").Value)
	SetCSV csv, ds("MM_member_id").Value
	SetCSV csv, SeibetsuText(ds("MM_seibetsu").Value)
	SetCSV csv, age
	SetCSV csv, (age \ 10) * 10
	SetCSV csv, MikikonText(ds("MM_mikikon").Value)
	SetCSV csv, ds("SG_shokugyou_name").Value
	SetCSV csv, ds("ZC_todoufuken").Value
	SetCSV csv, ds("ZC_shikuchouson").Value
	SetCSV csv, ds("CC_ct_id").Value

	Writeln csv

	ds.MoveNext
Loop
%>