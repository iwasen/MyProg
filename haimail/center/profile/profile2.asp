<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:
'******************************************************
'=== 情報選択肢 ===
Private Sub WriteInfo(infoid)
	Dim i
	For i = 1 To 20
		Writeln "<option value='" & Cstr(i) & "'" & Selected(infoid, i) & ">" & infonm(i) & "</option>"
	Next
End Sub

'=== メンバー数表示 ===
Private	Sub GetProfile1
	Dim sql, ds
	sql = "SELECT MM_seibetsu,COUNT(*)" & vbCrLf & _
				"FROM T_MEMBER WHERE MM_status<>'9'" & vbCrLf & _
				"GROUP BY MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If ds(0).Value = "0" Then
			mencount = Cdbl(ds(1).Value)
		Else
			womencount = Cdbl(ds(1).Value)
		End If
		ds.MoveNext
	Loop
	allcount = mencount + womencount

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
	Writeln "<tr><br></tr>"
		Writeln "<tr><td>■メンバー数</td></tr>"
	Writeln "</table>"
	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='30%' class='small'>"
		Writeln "<tr bgcolor='#FFEEE0'>"
		Writeln "<td align='left'><font size='+1'>" & "　" & NumFormat2(allcount) & "人（" & DateFormat2(Date) & "現在）" & "</font></td>"
		Writeln "</tr>"
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== 性別と年齢表示 ===
Private Sub GetProfile2
	Dim sql, ds, i, line, kmpercent
	Dim cnttbl(2,2)

	cnttbl(0,0) = "男性": cnttbl(0,1) = mencount
	cnttbl(1,0) = "女性": cnttbl(1,1) = womencount
	cnttbl(2,0) = "全体": cnttbl(2,1) = allcount

	sql = "SELECT MM_seibetsu, AVG(Trunc(Months_Between(sysdate, MM_seinengappi) / 12)) AS MM_heikin, GROUPING(MM_seibetsu) AS ALL_seibetsu" & vbCrLf & _
				"FROM T_MEMBER WHERE MM_status <> '9'" & vbCrLf & _
				"GROUP BY ROLLUP(MM_seibetsu)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If ds("ALL_seibetsu").Value = "0" Then
			i = Cint(ds("MM_seibetsu").Value)
		Else
			i = 2
		End If
		cnttbl(i,2) = Cdbl(ds("MM_heikin").Value)
		ds.MoveNext
	Loop

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■性別と年齢</td></tr>"
	Writeln "</table>"
	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='28%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th colspan=2>メンバー</th>"
	Writeln "<th>平均年齢</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>年齢</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To 2
		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='24%' align='left'>" & cnttbl(i,0) & "</td>"
			kmpercent = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1)
			Writeln "<td width='30%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
			Writeln "<td width='16%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
		Writeln "<td width='30%' align='center'>" & FormatNumber(cnttbl(i,2), 1, -1, 0, -1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"

End Sub

'=== 未既婚表示 ===
Private Sub GetProfile3
	Dim sql, ds, line, i, j, cnttbl(2,3)
	Dim kmpercent1, kmpercent2, kmpercent3
	cnttbl(0,0) = "独身"
	cnttbl(1,0) = "既婚"
	cnttbl(2,0) = "計": cnttbl(2,1) = allcount: cnttbl(2,2) = mencount: cnttbl(2,3) = womencount

	sql = "SELECT MM_mikikon,MM_seibetsu,COUNT(*) AS MM_count" & vbCrLf & _
				"FROM T_MEMBER WHERE MM_status<>'9'" & vbCrLf & _
				"GROUP BY MM_mikikon,MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	For i = 0 To 1: cnttbl(i,2) = 0: cnttbl(i,3) = 0: Next
	Do Until ds.EOF
		i = Cint(ds("MM_mikikon").Value): j = Cint(ds("MM_seibetsu").Value) + 2
		cnttbl(i,j) = Cdbl(ds("MM_count").Value)
		ds.MoveNext
	Loop
	cnttbl(0,1) = cnttbl(0,2) + cnttbl(0,3)
	cnttbl(1,1) = cnttbl(1,2) + cnttbl(1,3)

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■未既婚</td></tr>"
	Writeln "</table>"
	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='41%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th colspan=2>計</th>"
	Writeln "<th colspan=2>男性</th>"
	Writeln "<th colspan=2>女性</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To 2
		kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1)

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='16%' align='left'>" & cnttbl(i,0) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,3)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■年齢別表示 ===
Private Sub GetProfile4()
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim i, j, tblcnt, cnttbl(13,3)

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■年齢別</td></tr>"
	Writeln "</table>"

	cnttbl(0,0) = "１０～１２"
	cnttbl(1,0) = "１３～１５"
	cnttbl(2,0) = "１６～１８"
	cnttbl(3,0) = "１９～２４"
	cnttbl(4,0) = "２５～２９"
	cnttbl(5,0) = "３０～３４"
	cnttbl(6,0) = "３５～３９"
	cnttbl(7,0) = "４０～４４"
	cnttbl(8,0) = "４５～４９"
	cnttbl(9,0) = "５０～５４"
	cnttbl(10,0) = "５５～５９"
	cnttbl(11,0) = "６０～６４"
	cnttbl(12,0) = "６５～６９"
	cnttbl(13,0) = "７０～"

	sql = "SELECT Trunc(Months_Between(sysdate, MM_seinengappi) / 12) AS MM_nenrei,MM_seibetsu,COUNT(*) AS MM_count"
	sql = sql & " FROM T_MEMBER WHERE MM_status<>'9' AND Trunc(Months_Between(sysdate, MM_seinengappi) / 12)>=10"
	sql = sql & " GROUP BY Trunc(Months_Between(sysdate, MM_seinengappi) / 12),MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	For i = 0 To 13: cnttbl(i,2) = 0: cnttbl(i,3) = 0: Next
	Do Until ds.EOF
		If Cint(ds("MM_nenrei").Value) => 10 And Cint(ds("MM_nenrei").Value) <= 12 Then
			tblcnt = 0
		ElseIf Cint(ds("MM_nenrei").Value) => 13 And Cint(ds("MM_nenrei").Value) <= 15 Then
			tblcnt = 1
		ElseIf Cint(ds("MM_nenrei").Value) => 16 And Cint(ds("MM_nenrei").Value) <= 18 Then
			tblcnt = 2
		ElseIf Cint(ds("MM_nenrei").Value) => 19 And Cint(ds("MM_nenrei").Value) <= 24 Then
			tblcnt = 3
		ElseIf Cint(ds("MM_nenrei").Value) => 25 And Cint(ds("MM_nenrei").Value) <= 29 Then
			tblcnt = 4
		ElseIf Cint(ds("MM_nenrei").Value) => 30 And Cint(ds("MM_nenrei").Value) <= 34 Then
			tblcnt = 5
		ElseIf Cint(ds("MM_nenrei").Value) => 35 And Cint(ds("MM_nenrei").Value) <= 39 Then
			tblcnt = 6
		ElseIf Cint(ds("MM_nenrei").Value) => 40 And Cint(ds("MM_nenrei").Value) <= 44 Then
			tblcnt = 7
		ElseIf Cint(ds("MM_nenrei").Value) => 45 And Cint(ds("MM_nenrei").Value) <= 49 Then
			tblcnt = 8
		ElseIf Cint(ds("MM_nenrei").Value) => 50 And Cint(ds("MM_nenrei").Value) <= 54 Then
			tblcnt = 9
		ElseIf Cint(ds("MM_nenrei").Value) => 55 And Cint(ds("MM_nenrei").Value) <= 59 Then
			tblcnt = 10
		ElseIf Cint(ds("MM_nenrei").Value) => 60 And Cint(ds("MM_nenrei").Value) <= 64 Then
			tblcnt = 11
		ElseIf Cint(ds("MM_nenrei").Value) => 65 And Cint(ds("MM_nenrei").Value) <= 69 Then
			tblcnt = 12
		Else
			tblcnt = 13
		End If
		cnttbl(tblcnt,Cdbl(ds("MM_seibetsu").Value) + 2) = cnttbl(tblcnt,Cdbl(ds("MM_seibetsu").Value) + 2) + Cdbl(ds("MM_count").Value)
		ds.MoveNext
	Loop

	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='41%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th colspan=2>計</th>"
	Writeln "<th colspan=2>男性</th>"
	Writeln "<th colspan=2>女性</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To 13
		cnttbl(i,1) = cnttbl(i,2) + cnttbl(i,3)
		If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1) Else kmpercent2="- "
		If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1) Else kmpercent3="- "

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='16%' align='left'>" & cnttbl(i,0) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,3)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"

		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■職業表示 ===
Private Sub GetProfile5()
	Dim sql, ds, sub1, line, tlname, kmpercent
	Dim i, j, n, selcount, rcount
	Dim total(99), male(99), female(99), ctext(99)
	Dim stotal, smale, sfemale, shokugyou_name, shokugyou_count

	sub1 = "SELECT MM_shokugyou, COUNT(*) AS SG_total, COUNT(DECODE(MM_seibetsu,'0',1)) AS SG_male, COUNT(DECODE(MM_seibetsu,'1',1)) AS SG_female" & vbCrLf & _
				"FROM T_MEMBER" & vbCrLf & _
				"WHERE MM_status<>'9'" & vbCrLf & _
				"GROUP BY MM_shokugyou"
	sql = "SELECT SG_shokugyou_code, SG_shokugyou_name, SG_total, SG_male, SG_female" & vbCrLf & _
				"FROM T_SHOKUGYOU, (" & sub1 & ")" & vbCrLf & _
				"WHERE SG_shokugyou_code = MM_shokugyou(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0
	Do Until ds.EOF
		total(i) = MakeID(ds("SG_total").value, 7) + MakeID(ds("SG_shokugyou_code").value, 2)
		male(i) = MakeID(ds("SG_male").value, 7) + MakeID(ds("SG_shokugyou_code").value, 2)
		female(i) = MakeID(ds("SG_female").value, 7) + MakeID(ds("SG_shokugyou_code").value, 2)
		ctext(Number(ds("SG_shokugyou_code").value)) = ds("SG_shokugyou_name").value
		ds.Movenext
		i = i + 1
	Loop
	rcount = i - 1

	stotal = total
	stime = basp.Sort(stotal, 1, 1, 7)
	smale = male
	stime = basp.Sort(smale, 1, 1, 7)
	sfemale = female
	stime = basp.Sort(sfemale, 1, 1, 7)

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■職業</td></tr>"
	Writeln "</table>"

	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "計"
			selcount = allcount
		Case 1
			tlname = "男性"
			selcount = mencount
		Case 2
			tlname = "女性"
			selcount = womencount
		End Select

		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>" & tlname & "</th>"
		Writeln "<th>職業</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1: j = 0
		For n = 0 To rcount
			j = j + 1

			Select Case i
			Case 0
				shokugyou_name = ctext(Number(Mid(stotal(n), 8, 2)))
				shokugyou_count = Number(Mid(stotal(n), 1, 7))
			Case 1
				shokugyou_name = ctext(Number(Mid(smale(n), 8, 2)))
				shokugyou_count = Number(Mid(smale(n), 1, 7))
			Case 2
				shokugyou_name = ctext(Number(Mid(sfemale(n), 8, 2)))
				shokugyou_count = Number(Mid(sfemale(n), 1, 7))
			End Select

			kmpercent = FormatPercent(shokugyou_count / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='14%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='50%' align='left'>" & shokugyou_name & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(shokugyou_count) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			line = line + 1

		Next
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"
		End If
	Next
	Writeln "<br clear='left'><br>"
End Sub

'=== ■住所/勤務地表示 ===
Private Sub GetProfile6(infoid)
	Dim sql, ds, sub1, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim MMsql, i, j, tblcnt, cnttbl(47,3)
	Dim men, women

	Select Case infoid
	Case 6
		tlname = "住所": tblcnt = 46
		MMsql = " AND MM_zip_kyojuu=ZC_zip_code"
	Case 7
		tlname = "勤務地": tblcnt = 47
		MMsql = " AND MM_zip_kinmu IS NOT NULL AND MM_zip_kinmu=ZC_zip_code"
	End Select

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■" & tlname & "</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT ZC_todoufuken,MM_seibetsu,COUNT(*) AS MM_count" & vbCrLf & _
				"FROM T_MEMBER,T_ZIPCODE" & vbCrLf & _
				"WHERE MM_status<>'9'" & MMsql + vbCrLf & _
				"GROUP BY ZC_todoufuken,MM_seibetsu"
	sql = "SELECT TD_seq_no, ZC_todoufuken,MM_seibetsu, MM_count" & vbCrLf & _
				"FROM (" & sub1 & "), T_TODOUFUKEN" & vbCrLf & _
				"WHERE ZC_todoufuken(+) = TD_todoufuken" & vbCrLf & _
				"ORDER BY TD_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	For i = 0 To 47: cnttbl(i,2) = 0: cnttbl(i,3) = 0: Next

	Do Until ds.EOF
		i = CInt(ds("TD_seq_no").Value) - 1
		cnttbl(i,0) = ds("ZC_todoufuken").value

		If ds("MM_seibetsu").Value = "0" Then
			cnttbl(i,2) = Cdbl(ds("MM_count").Value)
			men = men + Cdbl(ds("MM_count").Value)
		Else
			cnttbl(i,3) = Cdbl(ds("MM_count").Value)
			women = women + Cdbl(ds("MM_count").Value)
		End If
		ds.MoveNext
	Loop

	Select Case infoid
	Case 7
		cnttbl(47,0) = "登録無し"
		cnttbl(47,2) = mencount - men
		cnttbl(47,3) = womencount - women
	End Select

	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='41%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th colspan=2>ＴＯＴＡＬ</th>"
	Writeln "<th colspan=2>男性</th>"
	Writeln "<th colspan=2>女性</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To tblcnt
		cnttbl(i,1) = cnttbl(i,2) + cnttbl(i,3)
		If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1) Else kmpercent2="- "
		If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1) Else kmpercent3="- "

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='16%' align='left'>" & cnttbl(i,0) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,3)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■カテゴリ表示 ===
Private Sub GetProfile8
	Dim sql, ds, sub1, line, tlname, kmpercent
	Dim i, j, n, selcount, count, category
	Dim total(8), male(8), female(8), stotal, smale, sfemale

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■カテゴリ</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT DISTINCT KD_member_id, KD_seibetsu, KG_category" & vbCrLf & _
					"FROM T_KYOUMI_DATA,T_KYOUMI_GENRE" & vbCrLf & _
					"WHERE KD_index=KG_index AND KG_valid_flag='1'"
	sql = "SELECT KG_category, COUNT(*) AS KG_total, SUM(CASE WHEN KD_seibetsu='0' THEN 1 ELSE 0 END) AS KG_male, SUM(CASE WHEN KD_seibetsu='1' THEN 1 ELSE 0 END) AS KG_female" & vbCrLf & _
				"FROM (" & sub1 & ")" & vbCrLf & _
				"GROUP BY KG_category"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0
	Do Until ds.EOF
		total(i) = MakeID(ds("KG_total").value, 7) + ds("KG_category").value
		male(i) = MakeID(ds("KG_male").value, 7) + ds("KG_category").value
		female(i) = MakeID(ds("KG_female").value, 7) + ds("KG_category").value
		i = i + 1
		ds.MoveNext
	Loop

	stotal = total
	stime = basp.Sort(stotal, 1, 1, 7)
	smale = male
	stime = basp.Sort(smale, 1, 1, 7)
	sfemale = female
	stime = basp.Sort(sfemale, 1, 1, 7)

	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "ＴＯＴＡＬ"
			selcount = allcount
		Case 1
			tlname = "男性"
			selcount = mencount
		Case 2
			tlname = "女性"
			selcount = womencount
		End Select

		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>" & tlname & "</th>"
		Writeln "<th>　</th>"
		Writeln "<th>　</th>"
		Writeln "</tr>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>カテゴリー</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1: j = 0
		For n = 0 To 8
			j = j + 1

			Select Case i
			Case 0
				count = CLng(Mid(stotal(n), 1, 7))
				category = Mid(stotal(n), 8, 1)
			Case 1
				count = CLng(Mid(smale(n), 1, 7))
				category = Mid(smale(n), 8, 1)
			Case 2
				count = CLng(Mid(sfemale(n), 1, 7))
				category = Mid(sfemale(n), 8, 1)
			End Select

			kmpercent = FormatPercent(count / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='14%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='50%' align='left'>" & category & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(count) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"

			line = line + 1
		Next
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"
		End If
	Next
	Writeln "<br clear='left'><br>"

End Sub

'=== ■興味ジャンル表示 ===
Private Sub GetProfile9()
	Dim sql, ds, sub1, line, tlname, kmpercent
	Dim i, j, n, selcount, rcount, count
	Dim stotal, smale, sfemale, item_text
	Dim total(999), male(999), female(999), ctext(999)

	For i = 0 To 999: total(i) = 0: male(i) = 0: female(i) = 0: Next

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■興味ジャンル</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT KD_index, COUNT(*) AS KG_total, SUM(CASE WHEN KD_seibetsu='0' THEN 1 ELSE 0 END) AS KG_male, SUM(CASE WHEN KD_seibetsu='1' THEN 1 ELSE 0 END) AS KG_female" & vbCrLf & _
				"FROM T_KYOUMI_DATA" & vbCrLf & _
				"GROUP BY KD_index"
	sql = "SELECT KG_index, KG_item_text, KG_total, KG_male, KG_female" & vbCrLf & _
				"FROM (" & sub1 & "), T_KYOUMI_GENRE" & vbCrLf & _
				"WHERE KD_index(+)=KG_index AND KG_valid_flag='1'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0
	Do Until ds.EOF
		total(i) = MakeID(ds("KG_total").value, 7) + MakeID(ds("KG_index").value, 3)
		male(i) = MakeID(ds("KG_male").value, 7) + MakeID(ds("KG_index").value, 3)
		female(i) = MakeID(ds("KG_female").value, 7) + MakeID(ds("KG_index").value, 3)
		ctext(Number(ds("KG_index").value)) = ds("KG_item_text").value
		i = i + 1
		ds.MoveNext
	Loop
	rcount = i - 1

	stotal = total
	stime = basp.Sort(stotal, 1, 1, 7)
	smale = male
	stime = basp.Sort(smale, 1, 1, 7)
	sfemale = female
	stime = basp.Sort(sfemale, 1, 1, 7)

	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "ＴＯＴＡＬ"
			selcount = allcount
		Case 1
			tlname = "男性"
			selcount = mencount
		Case 2
			tlname = "女性"
			selcount = womencount
		End Select

		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>" & tlname & "</th>"
		Writeln "<th>　</th>"
		Writeln "<th>　</th>"
		Writeln "</tr>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>興味ジャンル</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1: j = 0
		For n = 0 To rcount
			j = j + 1

			Select Case i
			Case 0
				count = CLng(Mid(stotal(n), 1, 7))
				item_text = ctext(Number(Mid(stotal(n), 8, 3)))
			Case 1
				count = CLng(Mid(smale(n), 1, 7))
				item_text = ctext(Number(Mid(smale(n), 8, 3)))
			Case 2
				count = CLng(Mid(sfemale(n), 1, 7))
				item_text = ctext(Number(Mid(sfemale(n), 8, 3)))
			End Select

			kmpercent = FormatPercent(count / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='8%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='56%' align='left'>" & item_text & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(count) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			line = line + 1
		Next

		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"
		End If
	Next
	Writeln "<br clear='left'><br>"
End Sub

'=== ■興味ジャンル（興味度別計/興味度別男性/興味度別女性）表示 ===
Private Sub GetProfile10a(infoid)
	Dim sql, ds, KDsql, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim sub1, i, n,selcount, rcount, index, index2
	Dim kanari(999), futsuu(999), total(999), stotal, text(999), nashi(999)

	Select Case infoid
	Case 10
		tlname = "興味度別計"
		KDsql = ""
		selcount = allcount
	Case 11
		tlname = "興味度別男性"
		KDsql = " WHERE KD_seibetsu='0'"
		selcount = mencount
	Case 12
		tlname = "興味度別女性"
		KDsql = " WHERE KD_seibetsu='1'"
		selcount = womencount
	End Select

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■興味ジャンル（" & tlname & "）</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT KD_index,KD_data,COUNT(*) AS KD_count" & vbCrLf & _
				"FROM T_KYOUMI_DATA"& KDsql + vbCrLf & _
				"GROUP BY KD_index,KD_data"
	sql = "SELECT KG_index,KD_data,KD_count,KG_item_text" & vbCrLf & _
				"FROM (" & sub1 & "), T_KYOUMI_GENRE" & vbCrLf & _
				"WHERE KD_index(+)=KG_index AND KG_valid_flag='1'" & vbCrLf & _
				"ORDER BY KG_index,KD_data"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0: index = ds("KG_index").value: index2 = ds("KG_index").value

	Do Until ds.EOF
		If Not index = index2 Then
			i = i + 1
		End If
		index = ds("KG_index").value

		If ds("KD_data").value = "2" Then
			kanari(i) = Number(ds("KD_count").value)
		Else
			futsuu(i) = Number(ds("KD_count").value)
		End If
		text(i) = ds("KG_item_text").value
		total(i) = MakeID(kanari(i) + futsuu(i),7)+MakeID(i, 3)

		ds.MoveNext
		index2 = ds("KG_index").value
	Loop
	rcount = i

	stotal = total
	stime = basp.Sort(stotal, 1, 1, 7)

	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='65%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th align='left'>ＴＯＴＡＬ</th>"
	Writeln "<th>　</th>"
	Writeln "<th colspan=2>かなりある</th>"
	Writeln "<th colspan=2>ふつうにある</th>"
	Writeln "<th colspan=2>ぜんぜんない</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th align='left'>興味ジャンル</th>"
	Writeln "<th>計</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To rcount
		n = Number(Mid(stotal(i), 8, 3))

		nashi(i) = selcount - kanari(n) - futsuu(n)
		kmpercent1 = FormatPercent(kanari(n) / selcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(futsuu(n) / selcount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(nashi(i) / selcount, 1, -1, 0, -1)

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='5%' align='right'>" & i+1 & "</td>"
		Writeln "<td width='38%' colspan=2 align='left'>" & text(n) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(kanari(n)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(futsuu(n)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(nashi(i)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■興味ジャンル（興味度別計/興味度別男性/興味度別女性）表示 ===
Private Sub GetProfile10b(infoid)
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3, flag
	Dim sub1, i, n,selcount, rcount, index1, index2
	Dim male(999), female(999), total(999), text(999), ctext(999), total_ID(999)
	Dim kanari_total(999), kanari_male(999), kanari_female(999), futsuu_total(999), futsuu_male(999), futsuu_female(999)
	Dim stotal, smale, sfemale, kanari(999), futsuu(999), nashi(999)
	flag = True

	For infoid = 10 To 12
		Select Case infoid
		Case 10
			tlname = "興味度別計"
			selcount = allcount
		Case 11
			tlname = "興味度別男性"
			selcount = mencount
		Case 12
			tlname = "興味度別女性"
			selcount = womencount
		End Select

		Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
			Writeln "<tr><td>■興味ジャンル（" & tlname & "）</td></tr>"
		Writeln "</table>"

		For i = 0 To 999: kanari_total(i) = 0: kanari_male(i) = 0: kanari_female(i) = 0:futsuu_total(i) = 0: futsuu_male(i) = 0: futsuu_female(i) = 0: nashi(i) = 0: Next

		If flag = True Then
			sub1 = "SELECT KD_index,KD_data,COUNT(*) AS KD_total, SUM(CASE WHEN KD_seibetsu='0' THEN 1 ELSE 0 END) AS KD_male, SUM(CASE WHEN KD_seibetsu='1' THEN 1 ELSE 0 END) AS KD_female" & vbCrLf & _
						"FROM T_KYOUMI_DATA" & vbCrLf & _
						"GROUP BY KD_index,KD_data"
			sql = "SELECT KG_index,KD_data,KD_total, KD_male, KD_female, KG_item_text" & vbCrLf & _
						"FROM (" & sub1 & "), T_KYOUMI_GENRE" & vbCrLf & _
						"WHERE KD_index(+)=KG_index AND KG_valid_flag='1'" & vbCrLf & _
						"ORDER BY KG_index,KD_data"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)

			i = 0: index1 = ds("KG_index").value: index2 = ds("KG_index").value

			Do Until ds.EOF
				If Not index1 = index2 Then
					i = i + 1
				End If
				index1 = ds("KG_index").value

				If ds("KD_data").value = "2" Then
					kanari_total(i) = Number(ds("KD_total").value)
					kanari_male(i) = Number(ds("KD_male").value)
					kanari_female(i) = Number(ds("KD_female").value)
				Else
					futsuu_total(i) = Number(ds("KD_total").value)
					futsuu_male(i) = Number(ds("KD_male").value)
					futsuu_female(i) = Number(ds("KD_female").value)
				End If
				total(i) = MakeID(kanari_total(i) + futsuu_total(i), 7) + MakeID(kanari_total(i), 7) + MakeID(futsuu_total(i), 7) + MakeID(ds("KG_index").value, 3)
				male(i) = MakeID(kanari_male(i) + futsuu_male(i), 7) + MakeID(kanari_male(i), 7) + MakeID(futsuu_male(i), 7) + MakeID(ds("KG_index").value, 3)
				female(i) = MakeID(kanari_female(i) + futsuu_female(i), 7) + MakeID(kanari_female(i), 7) + MakeID(futsuu_female(i), 7) + MakeID(ds("KG_index").value, 3)
				text(Number(ds("KG_index").value)) = ds("KG_item_text").value
				ds.MoveNext
				index2 = ds("KG_index").value
			Loop
			rcount = i

			stotal = total
			stime = basp.Sort(stotal, 1, 1, 7)
			smale = male
			stime = basp.Sort(smale, 1, 1, 7)
			sfemale = female
			stime = basp.Sort(sfemale, 1, 1, 7)
		End If

		Writeln "<table border=1 cellpadding=1 cellspacing=0 width='65%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>ＴＯＴＡＬ</th>"
		Writeln "<th>　</th>"
		Writeln "<th colspan=2>かなりある</th>"
		Writeln "<th colspan=2>ふつうにある</th>"
		Writeln "<th colspan=2>ぜんぜんない</th>"
		Writeln "</tr>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th align='left'>興味ジャンル</th>"
		Writeln "<th>計</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1
		For i = 0 To rcount
			Select Case infoid
			Case 10
				ctext(i) = text(Number(Mid(stotal(i), 22, 3)))
				kanari(i) = CLng(Mid(stotal(i), 8, 7))
				futsuu(i) = CLng(Mid(stotal(i), 15, 7))
			Case 11
				ctext(i) = text(Number(Mid(smale(i), 22, 3)))
				kanari(i) = CLng(Mid(smale(i), 8, 7))
				futsuu(i) = CLng(Mid(smale(i), 15, 7))
			Case 12
				ctext(i) = text(Number(Mid(sfemale(i), 22, 3)))
				kanari(i) = CLng(Mid(sfemale(i), 8, 7))
				futsuu(i) = CLng(Mid(sfemale(i), 15, 7))
			End Select

			nashi(i) = selcount - kanari(i) - futsuu(i)
			kmpercent1 = FormatPercent(kanari(i) / selcount, 1, -1, 0, -1)
			kmpercent2 = FormatPercent(futsuu(i) / selcount, 1, -1, 0, -1)
			kmpercent3 = FormatPercent(nashi(i) / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='5%' align='right'>" & i+1 & "</td>"
			Writeln "<td width='38%' colspan=2 align='left'>" & ctext(i) & "</td>"
			Writeln "<td width='12%' align='right'>" & NumFormat2(kanari(i)) & "</td>"
			Writeln "<td width='7%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
			Writeln "<td width='12%' align='right'>" & NumFormat2(futsuu(i)) & "</td>"
			Writeln "<td width='7%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
			Writeln "<td width='12%' align='right'>" & NumFormat2(nashi(i)) & "</td>"
			Writeln "<td width='7%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
			Writeln "</tr>"
			line = line + 1
		Next
		Writeln "</table>"
		Writeln "<br clear>"

		flag = False
	Next
End Sub

'=== ■よく行くコンビニ/よく行くスーパー/よく利用する業態やサービス/よく利用するお店やサービス表示 ===
Private Sub GetProfile13(infoid)
	Dim sql, ds, sub1, line, tlname, kmpercent
	Dim YMsql, i, j, n, selcount, rcount, count
	Dim total(999), male(999), female(999), ctext(999)
	Dim stotal, smale, sfemale, item_text

	Select Case infoid
	Case 13
		tlname = "よく行くコンビニ"
		YMsql = " AND YM_category='01'"
	Case 14
		tlname = "よく行くスーパー"
		YMsql = " AND YM_category='02'"
	Case 15
		tlname = "よく利用する業態やサービス"
		YMsql = " AND YM_category='03'"
	Case 16
		tlname = "よく利用するお店やサービス"
		YMsql = " AND YM_category='04'"
	End Select
	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■" & tlname & "</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT YD_index, COUNT(*) AS YD_total, SUM(CASE WHEN YD_seibetsu='0' THEN 1 ELSE 0 END) AS YD_male, SUM(CASE WHEN YD_seibetsu='1' THEN 1 ELSE 0 END) AS YD_female" & vbCrLf & _
				"FROM T_YOKUIKU_DATA" & vbCrLf & _
				"GROUP BY YD_index"
	sql = "SELECT YM_index, YM_item_text, YD_total, YD_male, YD_female" & vbCrLf & _
				"FROM (" & sub1 & "), T_YOKUIKU_MISE" & vbCrLf & _
				"WHERE YD_index(+)=YM_index AND YM_valid_flag='1'" & YMsql
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0
	Do Until ds.EOF
		total(i) = MakeID(ds("YD_total").value, 7) + MakeID(ds("YM_index").value, 3)
		male(i) = MakeID(ds("YD_male").value, 7) + MakeID(ds("YM_index").value, 3)
		female(i) = MakeID(ds("YD_female").value, 7) + MakeID(ds("YM_index").value, 3)
		ctext(Number(ds("YM_index").value)) = ds("YM_item_text").value
		i = i + 1
		ds.MoveNext
	Loop

	rcount = ds.RecordCount

	stotal = total
	stime = basp.Sort(stotal, 1, 1, 7)
	smale = male
	stime = basp.Sort(smale, 1, 1, 7)
	sfemale = female
	stime = basp.Sort(sfemale, 1, 1, 7)

	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "計"
			selcount = allcount
		Case 1
			tlname = "男性"
			selcount = mencount
		Case 2
			tlname = "女性"
			selcount = womencount
		End Select

		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>" & tlname & "</th>"
		Writeln "<th>　</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1: j = 0
		For n = 0 To rcount - 1
			j = j + 1

		Select Case i
			Case 0
				count = CLng(Mid(stotal(n), 1, 7))
				item_text = ctext(Number(Mid(stotal(n), 8, 3)))
			Case 1
				count = CLng(Mid(smale(n), 1, 7))
				item_text = ctext(Number(Mid(smale(n), 8, 3)))
			Case 2
				count = CLng(Mid(sfemale(n), 1, 7))
				item_text = ctext(Number(Mid(sfemale(n), 8, 3)))
			End Select

			kmpercent = FormatPercent(count / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='13%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='51%' align='left'>" & item_text & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(count) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			line = line + 1
		Next
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"
		End If
	Next
	Writeln "<br clear='left'><br>"
End Sub

'=== ■どっち派（計/男性/女性）表示 ===
Private Sub GetProfile17a(infoid)
	Dim sql, ds, sub1, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim i, n, tblcnt, selcount, DDsql, nashi
	Dim total(999), ctext1(999), ctext2(999), total1, total2
	Dim stotal,rcount, count, index1, index2

	Select Case infoid
	Case 17
		tlname = "計"
		DDsql = ""
		selcount = allcount
	Case 18
		tlname = "男性"
		DDsql = " WHERE DD_seibetsu='0'"
		selcount = mencount
	Case 19
		tlname = "女性"
		DDsql = " WHERE DD_seibetsu='1'"
		selcount = womencount
	End Select
	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
	Writeln "<tr><td>■どっち派（" & tlname & "）</td></tr>"
	Writeln "</table>"

	sub1 = "SELECT DD_index,DD_data,COUNT(*) AS DD_count" & vbCrLf & _
				"FROM T_DOCCHI_DATA" & DDsql + vbCrLf & _
				"GROUP BY DD_index,DD_data"
	sql = "SELECT DH_item_no,DH_index,DD_data,DD_count,DH_item1_text,DH_item2_text" & vbCrLf & _
				"FROM (" & sub1 & "),T_DOCCHI_HA" & vbCrLf & _
				"WHERE DD_index(+)=DH_index AND DH_valid_flag='1'" & vbCrLf & _
				"ORDER BY DH_item_no,DH_index,DD_data"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	i = 0: index1 = ds("DH_index").value: index2 = ds("DH_index").value
	Do Until ds.EOF
		If Not index1 = index2 Then
			i = i + 1
			total1 = 0
			total2 = 0
		End If
		index1 = ds("DH_index").value

		If ds("DD_data").value = "1" Then
			total1 = ds("DD_count").value
		Else
			total2 = ds("DD_count").value
		End If

		total(i) = MakeID(total1, 7) + MakeID(total2, 7) + MakeID(ds("DH_index").value, 3) + MakeID(ds("DH_item_no").value, 3)

		ctext1(Number(i)) = ds("DH_item1_text").value
		ctext2(Number(i)) = ds("DH_item2_text").value
		ds.MoveNext
		index2 = ds("DH_index").value
	Loop
	rcount = i

	Writeln "<table border=1 cellpadding=1 cellspacing=0 width='95%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>　</th>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "<th>　</th>"
	Writeln "<th>人数</th>"
	Writeln "<th>％</th>"
	Writeln "</tr>"

	line = 1
	For i = 0 To rcount

		nashi = selcount - Clng(Mid(total(i), 1, 7)) - CLng(Mid(total(i), 8, 7))
		kmpercent1 = FormatPercent(Clng(Mid(total(i), 1, 7)) / selcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(CLng(Mid(total(i), 8, 7)) / selcount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(nashi / selcount, 1, -1, 0, -1)

		Writeln "<tr class='tc" & line Mod 2 & "'>"
		Writeln "<td width='3%' align='right'>" & Cstr(i+1) & "</td>"
		Writeln "<td width='15%' align='left'>" & ctext1(i) & "</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(Clng(Mid(total(i), 1, 7))) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='15%' align='left'>" & ctext2(i) & "</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(CLng(Mid(total(i), 8, 7))) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='15%' align='left'>どちらでもない</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(nashi) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■どっち派（計/男性/女性）表示 ===
Private Sub GetProfile17b(infoid)
	Dim sql, ds, sub1, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim i, n, tblcnt, selcount, flag
	Dim total(999), male(999), female(999), total1, male1, female1, total2, male2, female2, ctext1(999), ctext2(999)
	Dim stotal, smale, sfemale, item_text
	Dim rcount, count, index1, index2, docchi1, docchi2, docchi3, id(999), sortid
	flag = True

	For infoid = 17 TO 19
		Select Case infoid
		Case 17
			tlname = "計"
			selcount = allcount
		Case 18
			tlname = "男性"
			selcount = mencount
		Case 19
			tlname = "女性"
			selcount = womencount
		End Select
		Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
		Writeln "<tr><td>■どっち派（" & tlname & "）</td></tr>"
		Writeln "</table>"

		If flag = True Then
			sub1 = "SELECT DD_index, DD_data, COUNT(*) AS DD_total, SUM(CASE WHEN DD_seibetsu='0' THEN 1 ELSE 0 END) AS DD_male, SUM(CASE WHEN DD_seibetsu='1' THEN 1 ELSE 0 END) AS DD_female" & vbCrLf & _
						"FROM T_DOCCHI_DATA" & vbCrLf & _
						"GROUP BY DD_index,DD_data"
			sql = "SELECT DH_item_no, DH_index, DD_data, DD_total, DD_male, DD_female, DH_item1_text,DH_item2_text" & vbCrLf & _
						"FROM (" & sub1 & "),T_DOCCHI_HA" & vbCrLf & _
						"WHERE DD_index=DH_index AND DH_valid_flag='1'" & vbCrLf & _
						"ORDER BY DH_item_no"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)

			n = 0: index1 = ds("DH_index").value: index2 = ds("DH_index").value
			Do Until ds.EOF
				If Not index1 = index2 Then
					n = n + 1
					total1 = 0: total2 = 0
					male1 = 0: male2 = 0
					female1 = 0: female2 = 0
				End If
				index1 = ds("DH_index").value

				If ds("DD_data").value = "1" Then
					total1 = ds("DD_total").value
					male1 = ds("DD_male").value
					female1 = ds("DD_female").value
				Else
					total2 = ds("DD_total").value
					male2 = ds("DD_male").value
					female2 = ds("DD_female").value
				End If

				total(n) = MakeID(total1, 7) + MakeID(total2, 7) + MakeID(ds("DH_index").value, 3) + MakeID(ds("DH_item_no").value, 3)
				male(n) = MakeID(male1, 7) + MakeID(male2, 7) + MakeID(ds("DH_index").value, 3) + + MakeID(ds("DH_item_no").value, 3)
				female(n) = MakeID(female1, 7) + MakeID(female2, 7) + MakeID(ds("DH_index").value, 3) + MakeID(ds("DH_item_no").value, 3)

				ctext1(Number(n)) = ds("DH_item1_text").value
				ctext2(Number(n)) = ds("DH_item2_text").value
				i = i + 1
				ds.MoveNext
				index2 = ds("DH_index").value
			Loop

			flag = False
		End If

		Writeln "<table border=1 cellpadding=1 cellspacing=0 width='95%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>　</th>"
		Writeln "<th>　</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "<th>　</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "<th>　</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		line = 1
		For i = 0 To n

			Select Case infoid
			Case 17
				docchi1 = CLng(Mid(total(i), 1, 7))
				docchi2 = CLng(Mid(total(i), 8, 7))
			Case 18
				docchi1 = CLng(Mid(male(i), 1, 7))
				docchi2 = CLng(Mid(male(i), 8, 7))
			Case 19
				docchi1 = CLng(Mid(female(i), 1, 7))
				docchi2 = CLng(Mid(female(i), 8, 7))
			End Select

			docchi3 = selcount - (docchi1 + docchi2)
			kmpercent1 = FormatPercent(docchi1 / selcount, 1, -1, 0, -1)
			kmpercent2 = FormatPercent(docchi2 / selcount, 1, -1, 0, -1)
			kmpercent3 = FormatPercent(docchi3 / selcount, 1, -1, 0, -1)

			Writeln "<tr class='tc" & line Mod 2 & "'>"
			Writeln "<td width='3%' align='right'>" & Cstr(i+1) & "</td>"
			Writeln "<td width='15%' align='left'>" & ctext1(i) & "</td>"
			Writeln "<td width='7%' align='right'>" & NumFormat2(docchi1) & "</td>"
			Writeln "<td width='4%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
			Writeln "<td width='15%' align='left'>" & ctext2(i) & "</td>"
			Writeln "<td width='7%' align='right'>" & NumFormat2(docchi2) & "</td>"
			Writeln "<td width='4%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
			Writeln "<td width='15%' align='left'>どちらでもない</td>"
			Writeln "<td width='7%' align='right'>" & NumFormat2(docchi3) & "</td>"
			Writeln "<td width='4%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
			Writeln "</tr>"
			line = line + 1
		Next
		Writeln "</table>"
		Writeln "<br clear>"
	Next
End Sub

'=== メイン処理 ===
Dim infoid, infonm(20), allcount, mencount, womencount
Dim stime, basp

Set basp= Server.CreateObject("basp21")

'■表示情報
infonm(1) = "メンバー数"
infonm(2) = "性別と年齢"
infonm(3) = "未既婚"
infonm(4) = "年齢別"
infonm(5) = "職業"
infonm(6) = "住所"
infonm(7) = "勤務地"
infonm(8) = "カテゴリ"
infonm(9) = "興味ジャンル"
infonm(10) = "興味ジャンル（興味度別計）"
infonm(11) = "興味ジャンル（興味度男性）"
infonm(12) = "興味ジャンル（興味度女性）"
infonm(13) = "よく行くコンビニ"
infonm(14) = "よく行くスーパー"
infonm(15) = "よく利用する業態やサービス"
infonm(16) = "よく利用するお店やサービス"
infonm(17) = "どっち派（計）"
infonm(18) = "どっち派（男性）"
infonm(19) = "どっち派（女性）"
infonm(20) = "全体"

'■タイトル
SetHeaderTitle "メンバープロフィール"

'■リクエスト文字列
If IsEmpty(Request("btngo")) Then
	infoid = 99
Else
	If IsEmpty(Request("infoid")) Then infoid = 1 Else infoid = Cint(Request("infoid"))
End If

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form method="post" id=form1 name=form1>
	<tr>
		<td>
			<font size=-1 color="#000080">情報選択</font>
			<select name="infoid"><%WriteInfo(infoid)%></select>
			<input type="submit" value="表示" name="btngo" onclick="document.all('waitmsg').style.display=''">
			<span id="waitmsg" style="display:none">　<font color="brown">集計中です。しばらくお待ちください・・・</font></span>
		</td>
	</tr>
	</form>
</table>
<%
If infoid <> 99 Then
	GetProfile1
	If infoid = 2 Or infoid = 20 Then GetProfile2
	If infoid = 3 Or infoid = 20 Then GetProfile3
	If infoid = 4 Or infoid = 20 Then GetProfile4
	If infoid = 5 Or infoid = 20 Then GetProfile5
	If infoid = 6 Or infoid = 20 Then GetProfile6(6)
	If infoid = 7 Or infoid = 20 Then GetProfile6(7)
	If infoid = 8 Or infoid = 20 Then GetProfile8
	If infoid = 9 Or infoid = 20 Then GetProfile9
	If infoid = 10 Then GetProfile10a(10)
	If infoid = 11 Then GetProfile10a(11)
	If infoid = 12 Then GetProfile10a(12)
	If infoid = 20 Then GetProfile10b(10)
	If infoid = 13 Or infoid = 20 Then GetProfile13(13)
	If infoid = 14 Or infoid = 20 Then GetProfile13(14)
	If infoid = 15 Or infoid = 20 Then GetProfile13(15)
	If infoid = 16 Or infoid = 20 Then GetProfile13(16)
	If infoid = 17 Then GetProfile17a(17)
	If infoid = 18 Then GetProfile17a(18)
	If infoid = 19 Then GetProfile17a(19)
	If infoid = 20 Then GetProfile17b(17)
End If
%>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form id=form1 name=form1>
	<tr><br></tr>
	<tr>
	<td align="center"><input type="button" value=" 戻る " onclick="location.href='../menu.asp'"></td>
	</tr>
	</form>
</table>
<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
