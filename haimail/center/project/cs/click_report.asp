<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�N���b�N���|�[�g�쐬����
'******************************************************

'=== �N���b�N���v�Z ===
Private Function GetClickRate(haishin, click)
	If haishin = 0 Then
		GetClickRate = "-"
	Else
		GetClickRate = FormatPercent(Number(click) / haishin, 2)
	End If
End Function

'=== �N���b�N�񐔁^�l�v�Z ===
Private Function GetClickSuu(click, click_uniq)
	If NVL(click_uniq, 0) = 0 Then
		GetClickSuu = "-"
	Else
		GetClickSuu = FormatNumber(Number(click) / click_uniq, 2, -1, 0, 0)
	End If
End Function

'=== �W�v���ʃZ�b�g ===
Private Sub SetData(objWorkSheet, row, haishi, click, click_uniq)
	With objWorkSheet
		.Cells(row, 5).Value = Number(haishi)
		.Cells(row, 6).Value = Number(click)
		.Cells(row, 7).Value = GetClickRate(haishi, click)
		.Cells(row, 8).Value = Number(click_uniq)
		.Cells(row, 9).Value = GetClickRate(haishi, click_uniq)
		.Cells(row,10).Value = GetClickSuu(click, click_uniq)
	End With
End Sub

'=== �E�Ɩ��擾 ===
Private Function GetShoukugyouName(shokugyou)
	Dim sql, ds, ary, shokugyou_code

	sql = ""
	If Not IsNull(shokugyou) Then
		ary = Split(shokugyou, ",")
		For Each shokugyou_code In ary
			If sql <> "" Then
				sql = sql & ","
			End If
			sql = sql & "'" & shokugyou_code & "'"
		Next
	End If

	If sql <> "" Then
		sql = "SELECT SG_shokugyou_name FROM T_SHOKUGYOU WHERE SG_shokugyou_code IN (" & sql & ") ORDER BY SG_shokugyou_code"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		GetShoukugyouName = ""
		Do Until ds.EOF
			If GetShoukugyouName <> "" Then
				GetShoukugyouName = GetShoukugyouName & ","
			End If
			GetShoukugyouName = GetShoukugyouName & ds(0).Value
			ds.MoveNext
		Loop
	Else
		GetShoukugyouName = "����Ȃ�"
	End If
End Function

'=== �G�N�Z���Ƀf�[�^�������� ===
Private Sub WriteExcel(job_id, tempfile)
	Const xlEdgeLeft = 7
	Const xlEdgeTop = 8
	Const xlEdgeBottom = 9
	Const xlEdgeRight = 10
	COnst xlLineStyleNone = -4142
	Dim sql, sub1, sub2, sub3, ds, ds2
	Dim objExcelApp, objWorkBook, objWorkSheets, objWorkSheet, objName, objChart
	Dim path, mail_id, st_row, nendai, count, row, total_click, i, n, click, temp
	Dim kyoumi_genre1, kyoumi_genre2

	path = Server.MapPath("excel/click_report.xlt")

	Set objExcelApp = Server.CreateObject("Excel.Application")
	objExcelApp.DisplayAlerts = False
	objExcelApp.Visible = False

	objExcelApp.Workbooks.Open(path)

	Set objWorkBook = objExcelApp.ActiveWorkbook
	Set objWorkSheets = objWorkBook.Worksheets
	Set objWorkSheet = objWorkBook.Sheets(1)

	With objWorkSheet

		'�z�M���`����
		sub1 = "SELECT CC_job_id,MIN(CC_start_date) CC_start_date,MAX(CC_end_date) CC_end_date" & vbCrLf & _
					"FROM T_CLICK_COUNT" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & vbCrLf & _
					"GROUP BY CC_job_id"
		sql = "SELECT SM_start_date,CC_start_date,CC_end_date,SM_real_send,SM_subject,SE_seibetsu_m,SE_seibetsu_f,SE_mikikon_mi,SE_mikikon_ki,SE_nenrei_from,SE_nenrei_to,SE_kyojuu_shitei,SE_kyojuu,SE_shokugyou,SE_kyoumi_genre1,SE_kyoumi_genre2,JB_mail_id" & vbCrLf & _
					"FROM T_JOB,T_SEND_MAIL,T_SEARCH,(" & sub1 & ")" & vbCrLf & _
					"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id AND JB_search_id=SE_search_id AND JB_job_id=CC_job_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			Set objName = objWorkBook.Names("�z�M��")
			st_row = objName.RefersToRange.Row
			.Cells(st_row + 0, 3).Value = DateFormat(ds("SM_start_date").Value)
			.Cells(st_row + 1, 3).Value = DateFormat3(ds("CC_start_date").Value, ds("CC_end_date").Value)
			.Cells(st_row + 2, 3).Value = ds("SM_real_send").Value
			.Cells(st_row + 3, 3).Value = ds("SM_subject").Value

			'����
			If ds("SE_seibetsu_m").Value = "1" Then
				If ds("SE_seibetsu_f").Value = "1" Then
					temp = "�j��,����"
				Else
					temp = "�j��"
				End If
			Else
				If ds("SE_seibetsu_f").Value = "1" Then
					temp = "����"
				Else
					temp = "����Ȃ�"
				End If
			End If
			.Cells(st_row + 5, 3).Value = temp

			'������
			If ds("SE_mikikon_mi").Value = "1" Then
				If ds("SE_mikikon_ki").Value = "1" Then
					temp = "�Ɛg,����"
				Else
					temp = "�Ɛg"
				End If
			Else
				If ds("SE_mikikon_ki").Value = "1" Then
					temp = "����"
				Else
					temp = "����Ȃ�"
				End If
			End If
			.Cells(st_row + 6, 3).Value = temp

			'�N��
			If ds("SE_nenrei_from").Value <> "" Or ds("SE_nenrei_to").Value <> "" Then
				temp = ""
				If ds("SE_nenrei_from").Value <> "" Then
					temp = ds("SE_nenrei_from").Value & "��"
				End If
				temp = temp & "�`"
				If ds("SE_nenrei_to").Value <> "" Then
					temp = temp & ds("SE_nenrei_to").Value & "��"
				End If
			Else
				temp = "����Ȃ�"
			End If
			.Cells(st_row + 7, 3).Value = temp

			'�Z��
			If ds("SE_kyojuu_shitei").Value = "1" Then
				temp = Replace(NVL(ds("SE_kyojuu").Value, ""), "/", "")
			Else
				temp = "����Ȃ�"
			End If
			.Cells(st_row + 8, 3).Value = temp

			'�E��
			.Cells(st_row + 9, 3).Value = GetShoukugyouName(ds("SE_shokugyou").Value)

			'�����W������
			temp = ""
			kyoumi_genre1 = NVL(ds("SE_kyoumi_genre1").Value, "")
			kyoumi_genre2 = NVL(ds("SE_kyoumi_genre2").Value, "")
			n = Len(kyoumi_genre1)
			If Len(kyoumi_genre2) > n Then
				n = Len(kyoumi_genre2)
			End If
			For i = 1 To n
				If Mid(kyoumi_genre1, i, 1) = "1" Or Mid(kyoumi_genre2, i, 1) = "1" Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & i
				End If
			Next
			If temp <> "" Then
				sql = "SELECT KG_item_text FROM T_KYOUMI_GENRE WHERE KG_index IN (" & temp & ") ORDER BY KG_item_no"
				temp = ""
				Set ds2 = CreateDynaset(sql, ORADYN_READONLY)
				Do Until ds2.EOF
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "�u" & ds2("KG_item_text").Value & "�v"
					ds2.MoveNext
				Loop
			Else
				temp = "����Ȃ�"
			End If
			.Cells(st_row + 10, 3).Value = temp

			mail_id = ds("JB_mail_id").Value
		End If

		'�W�v���ʃT�u�N�G��
		sub1 = "SELECT MC_member_id,COUNT(*) AS MC_count" & vbCrLf & _
					"FROM T_CLICK_COUNT,T_MAIL_CLICK" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id" & vbCrLf & _
					"GROUP BY MC_member_id"
		sub2 = "SELECT /*+ INDEX(T_SENDMAIL_RR) */ SR_member_id,MC_count" & vbCrLf & _
					"FROM T_SENDMAIL_RR,(" & sub1 & ")" & vbCrLf & _
					"WHERE SR_mail_id='" & mail_id & "' AND SR_status='1' AND SR_member_id=MC_member_id(+)"

		'�v
		Set objName = objWorkBook.Names("�v")
		st_row = objName.RefersToRange.Row
		sql = "SELECT COUNT(*),SUM(MC_count),COUNT(MC_count)" & vbCrLf & _
					"FROM (" & sub2 & ")"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			SetData objWorkSheet, st_row, ds(0).Value, ds(1).Value, ds(2).Value
			total_click = Number(ds(1).Value)
		End If

		'����
		Set objName = objWorkBook.Names("����")
		st_row = objName.RefersToRange.Row
		sql = "SELECT /*+ INDEX(T_MEMBER) */ COUNT(*),SUM(MC_count),COUNT(MC_count),MM_seibetsu" & vbCrLf & _
					"FROM (" & sub2 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_seibetsu"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			Select Case ds("MM_seibetsu")
			Case "0"
				SetData objWorkSheet, st_row, ds(0).Value, ds(1).Value, ds(2).Value
			Case "1"
				SetData objWorkSheet, st_row + 1, ds(0).Value, ds(1).Value, ds(2).Value
			End Select
			ds.MoveNext
		Loop

		'�N��
		Set objName = objWorkBook.Names("�N��")
		st_row = objName.RefersToRange.Row
		sql = "SELECT /*+ INDEX(T_MEMBER) */ COUNT(*),SUM(MC_count),COUNT(MC_count),TO_NENDAI(MM_seinengappi) nendai" & vbCrLf & _
					"FROM (" & sub2 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY TO_NENDAI(MM_seinengappi)"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			nendai = ds("nendai").Value
			If nendai >=1 Then
				SetData objWorkSheet, st_row + nendai -1, ds(0).Value, ds(1).Value, ds(2).Value
			End If
			ds.MoveNext
		Loop

		'������
		Set objName = objWorkBook.Names("������")
		st_row = objName.RefersToRange.Row
		sql = "SELECT /*+ INDEX(T_MEMBER) */ COUNT(*),SUM(MC_count),COUNT(MC_count),MM_mikikon" & vbCrLf & _
					"FROM (" & sub2 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_mikikon"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			Select Case ds("MM_mikikon")
			Case "0"
				SetData objWorkSheet, st_row, ds(0).Value, ds(1).Value, ds(2).Value
			Case "1"
				SetData objWorkSheet, st_row + 1, ds(0).Value, ds(1).Value, ds(2).Value
			End Select
			ds.MoveNext
		Loop

		'�Z��
		Set objName = objWorkBook.Names("�Z��")
		st_row = objName.RefersToRange.Row
		sub3 = "SELECT /*+ INDEX(T_MEMBER) */ COUNT(*),SUM(MC_count),COUNT(MC_count),ZC_todoufuken" & vbCrLf & _
					"FROM (" & sub2 & "),T_MEMBER,T_ZIPCODE" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id AND MM_zip_kyojuu=ZC_zip_uniq" & vbCrLf & _
					"GROUP BY ZC_todoufuken"
		sql = "SELECT *" & vbCrLf & _
					"FROM (" & sub3 & "),T_TODOUFUKEN" & vbCrLf & _
					"WHERE TD_todoufuken=ZC_todoufuken" & vbCrLf & _
					"ORDER BY TD_seq_no"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		count = 0
		Do Until ds.EOF
			row = st_row + count
			If count > 0 Then
				.Rows(row).Insert
				.Range(.Rows(row - 1), .Rows(row)).FillDown
				.Cells(row, 2).Value = Null
				.Cells(row, 2).Borders(xlEdgeTop).LineStyle = xlLineStyleNone
			End If
			.Cells(row, 3).Value = ds("TD_todoufuken").Value
			SetData objWorkSheet, row, ds(0).Value, ds(1).Value, ds(2).Value
			count = count + 1
			ds.MoveNext
		Loop

		'�E��
		Set objName = objWorkBook.Names("�E��")
		st_row = objName.RefersToRange.Row
		sub3 = "SELECT /*+ INDEX(T_MEMBER) */ COUNT(*),SUM(MC_count),COUNT(MC_count),MM_shokugyou" & vbCrLf & _
					"FROM (" & sub2 & "),T_MEMBER" & vbCrLf & _
					"WHERE SR_member_id=MM_member_id" & vbCrLf & _
					"GROUP BY MM_shokugyou"
		sql = "SELECT *" & vbCrLf & _
					"FROM (" & sub3 & "),T_SHOKUGYOU" & vbCrLf & _
					"WHERE MM_shokugyou=SG_shokugyou_code" & vbCrLf & _
					"ORDER BY SG_shokugyou_code"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		count = 0
		Do Until ds.EOF
			row = st_row + count
			If count > 0 Then
				.Rows(row).Insert
				.Range(.Rows(row - 1), .Rows(row)).FillDown
				.Cells(row, 2).Value = Null
				.Cells(row, 2).Borders(xlEdgeTop).LineStyle = xlLineStyleNone
			End If
			.Cells(row, 3).Value = ds("SG_shokugyou_name").Value
			SetData objWorkSheet, row, ds(0).Value, ds(1).Value, ds(2).Value
			count = count + 1
			ds.MoveNext
		Loop

		'���ԑ�
		Set objName = objWorkBook.Names("���ԑ�")
		st_row = objName.RefersToRange.Row
		sql = "SELECT COUNT(*),TO_CHAR(MC_date,'HH24') MC_time" & vbCrLf & _
					"FROM T_CLICK_COUNT,T_MAIL_CLICK" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id" & vbCrLf & _
					"GROUP BY TO_CHAR(MC_date,'HH24')" & vbCrLf & _
					"ORDER BY MC_time"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		For i = 0 To 23
			row = st_row + i
			n = 0
			If Not ds.EOF Then
				If CInt(ds("MC_time").Value) = i Then
					n = Number(ds(0).Value)
					ds.MoveNext
				End If
			End If
			.Cells(row, 4).Value = n
			.Cells(row, 5).Value = GetClickRate(total_click, n)
		Next

		'����
		Set objName = objWorkBook.Names("����")
		st_row = objName.RefersToRange.Row
		sql = "SELECT COUNT(*),TRUNC(MC_date,'DD') MC_date" & vbCrLf & _
					"FROM T_CLICK_COUNT,T_MAIL_CLICK" & vbCrLf & _
					"WHERE CC_job_id=" & job_id & " AND CC_ct_id=MC_ct_id" & vbCrLf & _
					"GROUP BY TRUNC(MC_date,'DD')" & vbCrLf & _
					"ORDER BY MC_date"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		count = 0
		total_click = 0
		Do Until ds.EOF
			row = st_row + count + 1
			If count > 0 Then
				.Rows(row).Insert
				.Range(.Rows(row - 1), .Rows(row)).FillDown
				.Cells(row, 2).Value = Null
				.Cells(row, 2).Borders(xlEdgeTop).LineStyle = xlLineStyleNone
			End If
			click = Number(ds(0).Value)
			total_click = total_click + click
			.Cells(row, 3).Value = DateFormat(ds("MC_date").Value)
			.Cells(row, 4).Value = click
			.Cells(row, 5).Value = total_click
			count = count + 1
			ds.MoveNext
		Loop

	End With

	objWorkBook.SaveAs tempfile

	objExcelApp.Quit
End Sub

'=== �G�N�Z���t�@�C�����o�� ===
Private Sub OutputExcel(tempfile)
	Dim basp, barray, fso

	Set basp= Server.CreateObject("basp21")
	barray = basp.BinaryRead(tempfile)

	Set fso = CreateObject("Scripting.FileSystemObject")
	fso.DeleteFile tempfile

	Response.Expires = 0
	Response.Clear

	Response.ContentType = "application/vnd.ms-excel"
	Response.AddHeader "Content-Disposition", "inline; filename=""�N���b�N���|�[�g.xls"""
	Response.BinaryWrite barray
End Sub

'=== ���C������ ===
Dim job_id, tempfile

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

tempfile = "C:\TEMP\" & session.SessionID & ".xls"

WriteExcel job_id, tempfile
OutputExcel tempfile
%>