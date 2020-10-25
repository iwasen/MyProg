<%
'=== 承認期限チェック ===
Private Function LimitCheck(job_id)
	Dim sql, ds

	LimitCheck = False

	sql = "SELECT SM_start_date FROM T_JOB,T_SEND_MAIL WHERE JB_job_id=" & job_id & " AND SM_mail_id=JB_mail_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		If ds("SM_start_date").Value > DateAdd("h", 48, now) Then
			LimitCheck = True
		End If
	End If
End Function
%>