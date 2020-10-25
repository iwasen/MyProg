<%
'=== 合計ポイント取得 ===
Private Function GetTotalPoint(member_id)
	Dim sql, ds

	sql = "SELECT SUM(PT_total_point) FROM T_POINT_TOTAL WHERE PT_member_id='" & member_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		GetTotalPoint = 0
	Else
		GetTotalPoint = NVL(ds.Fields(0).Value, 0)
	End If
	Set ds = Nothing
End Function

'=== ポイント追加処理 ===
Private Sub AddPoint(member_id, point_type, point, mail_id)
	AddPoint2 member_id, point_type, point, mail_id, Now
End Sub

Private Sub AddPoint2(member_id, point_type, point, mail_id, dt)
	Dim sql, ds, seq_no

	BeginTrans

	Do While True
		sql = "SELECT MAX(PL_seq_no) FROM T_POINT_LOG" & vbCrLf & _
					"WHERE PL_member_id='" & member_id & "' AND PL_point_type='" & point_type & "'"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If ds.EOF Then
			seq_no = 1
		Else
			seq_no = NVL(ds(0).Value, 0) + 1
		End If

		sql = "SELECT * FROM T_POINT_LOG" & vbCrLf & _
					"WHERE PL_member_id='" & member_id & "' AND PL_point_type='" & point_type & "' AND PL_seq_no=" & seq_no
		Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
		If ds.EOF Then
			ds.AddNew
			ds("PL_member_id").Value = member_id
			ds("PL_point_type").Value = point_type
			ds("PL_seq_no").Value = seq_no
			ds("PL_point").Value = point
			ds("PL_date").Value = dt
			ds("PL_recv_mail").Value = mail_id
			ds.Update
			Exit Do
		End If
	Loop

	sql = "SELECT * FROM T_POINT_TOTAL" & vbCrLf & _
				"WHERE PT_member_id='" & member_id & "' AND PT_point_type='" & point_type & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.AddNew
		ds("PT_member_id").Value = member_id
		ds("PT_point_type").Value = point_type
		ds("PT_total_point").Value = point
	Else
		ds.Edit
		ds("PT_total_point").Value = CDbl(ds("PT_total_point").Value) + point
	End If
	ds.Update
	Set ds = Nothing

	CommitTrans
End Sub
%>