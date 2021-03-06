<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:アンケート日別推移CSV処理
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

'=== メイン処理 ===
Dim job_id, mail_id, sub1, sub2, sql, ds, csv

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

Writeln "日付,発信数,返信数"

sub1 = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ TO_CHAR(SR_date, 'YYYYMMDD') AS SR_ymd,COUNT(*) AS SR_count" & vbCrLf & _
			"FROM T_SENDMAIL_RR" & vbCrLf & _
			"WHERE SR_mail_id='" & mail_id & "' AND SR_status='1'" & vbCrLf & _
			"GROUP BY TO_CHAR(SR_date, 'YYYYMMDD')" & vbCrLf & _
			"ORDER BY SR_ymd"
sub2 = "SELECT TO_CHAR(RM_recv_date, 'YYYYMMDD') AS RM_ymd,COUNT(*) AS RM_count" & vbCrLf & _
			"FROM T_RECV_MAIL" & vbCrLf & _
			"WHERE RM_mail_id='" & mail_id & "' AND RM_enq_valid='1'" & vbCrLf & _
			"GROUP BY TO_CHAR(RM_recv_date, 'YYYYMMDD')" & vbCrLf & _
			"ORDER BY RM_ymd"
sql = "SELECT * FROM (" & sub1 & "),(" & sub2 & ")" & vbCrLf & _
			"WHERE SR_ymd(+)=RM_ymd" & vbCrLf & _
			"UNION" & vbCrLf & _
			"SELECT * FROM (" & sub1 & "),(" & sub2 & ")" & vbCrLf & _
			"WHERE SR_ymd=RM_ymd(+)"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Do Until ds.EOF
	csv = ""
	If Not IsNull(ds("SR_ymd").Value) Then
		SetCSV csv, ds("SR_ymd").Value
	ELse
		SetCSV csv, ds("RM_ymd").Value
	End If
	SetCSV csv, NVL(ds("SR_count").Value, 0)
	SetCSV csv, NVL(ds("RM_count").Value, 0)

	Writeln csv
	ds.MoveNext
Loop
%>