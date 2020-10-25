<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:感想文時間別推移CSV処理
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
Dim job_id, mail_id, sql, ds_s, ds_r, i, csv

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

ReadJob job_id

Response.ContentType = "text/csv"
Response.AddHeader "Content-Disposition" , " inline; filename=""" & "output.csv" & """"

Writeln "時間,発信数,返信数"

sql = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ TO_CHAR(SR_date, 'HH24') AS SR_ymdh,COUNT(*) AS SR_count" & vbCrLf & _
			"FROM T_SENDMAIL_RR" & vbCrLf & _
			"WHERE SR_mail_id='" & mail_id & "' AND SR_status='1'" & vbCrLf & _
			"GROUP BY TO_CHAR(SR_date, 'HH24')" & vbCrLf & _
			"ORDER BY SR_ymdh"
Set ds_s = CreateDynaset(sql, ORADYN_READONLY)

sql = "SELECT TO_CHAR(RM_recv_date, 'HH24') AS RM_ymdh,COUNT(*) AS RM_count" & vbCrLf & _
			"FROM T_RECV_MAIL" & vbCrLf & _
			"WHERE RM_mail_id='" & mail_id & "' AND RM_imp_valid='1'" & vbCrLf & _
			"GROUP BY TO_CHAR(RM_recv_date, 'HH24')" & vbCrLf & _
			"ORDER BY RM_ymdh"
Set ds_r = CreateDynaset(sql, ORADYN_READONLY)

For i = 0 To 23
	csv = ""
	SetCSV csv, i

	If Not ds_s.EOF Then
		If CInt(ds_s("SR_ymdh")) = i Then
			SetCSV csv, NVL(ds_s("SR_count").Value, 0)
			ds_s.MoveNext
		Else
			SetCSV csv, 0
		End If
	Else
		SetCSV csv, 0
	End If

	If Not ds_r.EOF Then
		If CInt(ds_r("RM_ymdh")) = i Then
			SetCSV csv, NVL(ds_r("RM_count").Value, 0)
			ds_r.MoveNext
		Else
			SetCSV csv, 0
		End If
	Else
		SetCSV csv, 0
	End If

	Writeln csv
Next
%>