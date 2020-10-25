<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバープロフィール
'******************************************************
'=== 情報選択肢 ===
Private Sub WriteInfo(infoid)
	Dim i
	
	For i = 1 To 20
		Writeln "<option value='" & Cstr(i) & "'" & Selected(infoid, i) & ">" & infonm(i) & "</option>"
	Next
End Sub

'=== ■メンバー数表示 ===
Private Sub GetProfile1()
	Dim sql, ds, getcnt

	sql = "SELECT MM_seibetsu,COUNT(*) AS MM_count,GROUPING(MM_seibetsu) AS ALL_seibetsu"
	sql = sql & " FROM T_MEMBER WHERE MM_status<>'9' GROUP BY ROLLUP(MM_seibetsu)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If ds("ALL_seibetsu").Value = "0" Then
			If ds("MM_seibetsu").Value = "0" Then
				mencount = Cdbl(ds("MM_count").Value)
			Else	
				womencount = Cdbl(ds("MM_count").Value)
			End If
		Else
			allcount = Cdbl(ds("MM_count").Value)
		End If
		ds.MoveNext
	Loop
	ds.Close
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

'=== ■性別と年齢表示 ===
Private Sub GetProfile2()
	Dim sql, ds, line, tlname, kmpercent
	Dim MMsql, i, j, tblcnt, cnttbl(2,2), getcnt
	
	For i = 0 To 2: cnttbl(i,2) = 0: Next
	cnttbl(0,0) = "男性": cnttbl(0,1) = mencount
	cnttbl(1,0) = "女性": cnttbl(1,1) = womencount
	cnttbl(2,0) = "全体": cnttbl(2,1) = 0
	sql = "SELECT MM_seibetsu,AVG(TO_CHAR(SYSDATE,'YYYY') - TO_CHAR(MM_seinengappi,'YYYY')) AS MM_heikin,GROUPING(MM_seibetsu) AS ALL_seibetsu"
	sql = sql & " FROM T_MEMBER WHERE MM_status<>'9' GROUP BY ROLLUP(MM_seibetsu)"
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
	ds.Close
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
		If line Mod 2 = 0 Then Writeln "<tr bgcolor='#FFFFFF'>" Else Writeln "<tr bgcolor='#FFEEE0'>"
		Writeln "<td width='24%' align='left'>" & cnttbl(i,0) & "</td>"
		If i <> 2 Then
			kmpercent = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1)
			Writeln "<td width='30%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
			Writeln "<td width='16%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
		Else
			Writeln "<td width='30%' align='center'>-</td>"
			Writeln "<td width='16%' align='center'>-</td>"
		End If	
		Writeln "<td width='30%' align='center'>" & FormatNumber(cnttbl(i,2), 1, -1, 0, -1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■未既婚表示 ===
Private Sub GetProfile3()
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim MMsql, i, j, tblcnt, cnttbl(2,3), getcnt
	
	For i = 0 To 2
		For j = 1 To 3: cnttbl(i,j) = 0: Next	
	Next
	cnttbl(0,0) = "独身"
	cnttbl(1,0) = "既婚"
	cnttbl(2,0) = "計": cnttbl(2,1) = allcount: cnttbl(2,2) = mencount: cnttbl(2,3) = womencount
	sql = "SELECT MM_mikikon,MM_seibetsu,COUNT(*) AS MM_count"
	sql = sql & " FROM T_MEMBER WHERE MM_status<>'9'"
	sql = sql & " GROUP BY MM_mikikon,MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		i = Cint(ds("MM_mikikon").Value): j = Cint(ds("MM_seibetsu").Value) + 2
		cnttbl(i,j) = Cdbl(ds("MM_count").Value)
		ds.MoveNext
	Loop
	ds.Close
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
		'-//kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1)
		'-//kmpercent2 = FormatPercent(cnttbl(i,2) / allcount, 1, -1, 0, -1)
		'-//kmpercent3 = FormatPercent(cnttbl(i,3) / allcount, 1, -1, 0, -1)
		kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1)
		If line Mod 2 = 0 Then
			Writeln "<tr bgcolor='#FFFFFF'>"
		Else
			Writeln "<tr bgcolor='#FFEEE0'>"
		End If
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
	Dim MMsql, i, j, tblcnt, cnttbl(13,3), getcnt
	
	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■年齢別</td></tr>"
	Writeln "</table>"
	For i = 0 To 13
		For j = 1 To 3: cnttbl(i,j) = 0: Next
	Next
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
	sql = "SELECT TO_CHAR(SYSDATE,'YYYY') - TO_CHAR(MM_seinengappi,'YYYY') AS MM_nenrei,MM_seibetsu,COUNT(*) AS MM_count"
	sql = sql & " FROM T_MEMBER WHERE MM_status<>'9' AND TO_CHAR(SYSDATE,'YYYY') - TO_CHAR(MM_seinengappi,'YYYY')>=10"
	sql = sql & " GROUP BY TO_CHAR(SYSDATE,'YYYY') - TO_CHAR(MM_seinengappi,'YYYY'),MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
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
		If ds("MM_seibetsu").Value = "0" Then
			cnttbl(tblcnt,2) = cnttbl(tblcnt,2) + Cdbl(ds("MM_count").Value)
		Else
			cnttbl(tblcnt,3) = cnttbl(tblcnt,3) + Cdbl(ds("MM_count").Value)
		End If	
		ds.MoveNext
	Loop
	ds.Close
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
		'-//If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		'-//If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / allcount, 1, -1, 0, -1) Else kmpercent2="- "
		'-//If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / allcount, 1, -1, 0, -1) Else kmpercent3="- "
		If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1) Else kmpercent2="- "
		If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1) Else kmpercent3="- "
		If line Mod 2 = 0 Then 
			Writeln "<tr bgcolor='#FFFFFF'>"
		Else
			Writeln "<tr bgcolor='#FFEEE0'>"
		End If	
		Writeln "<td width='16%' align='left'>" & cnttbl(i,0) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,1)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='17%' align='right'>" & NumFormat2(cnttbl(i,3)) & "</td>"
		Writeln "<td width='11%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		ds.Close
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■職業表示 ===
Private Sub GetProfile5()
	Dim sql, ds, line, tlname, kmpercent
	Dim MMsql, i, j, tblcnt, tblput, selcount, maxline, getcnt

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■職業</td></tr>"
	Writeln "</table>"
	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "計"
			MMsql = ""
			selcount = allcount
		Case 1
			tlname = "男性"
			MMsql = " AND MM_seibetsu='0'"
			selcount = mencount
		Case 2
			tlname = "女性"
			MMsql = " AND MM_seibetsu='1'"
			selcount = womencount
		End Select
		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>" & tlname & "</th>"
		Writeln "<th>職業</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"
	
		sql = "SELECT SG_shokugyou_code,COUNT(*) AS SG_count,SG_shokugyou_name"
		sql = sql & " FROM T_MEMBER,T_SHOKUGYOU"
		sql = sql & " WHERE MM_status<>'9'" & MMsql
		sql = sql & " AND MM_shokugyou=SG_shokugyou_code"
		sql = sql & " GROUP BY SG_shokugyou_code,SG_shokugyou_name"
		sql = sql & " ORDER BY SG_count DESC"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		line = 1: j = 0
		Do Until ds.EOF
			j = j + 1
			'-//kmpercent = FormatPercent(ds("SG_count").Value / allcount, 1, -1, 0, -1)
			kmpercent = FormatPercent(ds("SG_count").Value / selcount, 1, -1, 0, -1)
			If line Mod 2 = 0 Then
				Writeln "<tr bgcolor='#FFFFFF'>"
			Else
				Writeln "<tr bgcolor='#FFEEE0'>"
			End If	
			Writeln "<td width='14%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='50%' align='left'>" & ds("SG_shokugyou_name").Value & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(ds("SG_count").Value) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			ds.MoveNext
			line = line + 1
		Loop
		ds.Close
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"		
		End If
	Next
	Writeln "<br clear='left'>"
End Sub

'=== ■住所/勤務地表示 ===
Private Sub GetProfile6(infoid)
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim MMsql, i, j, tblcnt, cnttbl(47,3), getcnt

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
	For i = 0 To 47
		For j = 1 To 3: cnttbl(i,j) = 0: Next
	Next
	cnttbl(0,0) = "北海道"
	cnttbl(1,0) = "青森県"
	cnttbl(2,0) = "岩手県"
	cnttbl(3,0) = "宮城県"
	cnttbl(4,0) = "秋田県"
	cnttbl(5,0) = "山形県"
	cnttbl(6,0) = "福島県"
	cnttbl(7,0) = "茨城県"
	cnttbl(8,0) = "栃木県"
	cnttbl(9,0) = "群馬県"
	cnttbl(10,0) = "埼玉県"
	cnttbl(11,0) = "千葉県"
	cnttbl(12,0) = "東京都"
	cnttbl(13,0) = "神奈川県"
	cnttbl(14,0) = "新潟県"
	cnttbl(15,0) = "富山県"
	cnttbl(16,0) = "石川県"
	cnttbl(17,0) = "福井県"
	cnttbl(18,0) = "山梨県"
	cnttbl(19,0) = "長野県"
	cnttbl(20,0) = "岐阜県"
	cnttbl(21,0) = "静岡県"
	cnttbl(22,0) = "愛知県"
	cnttbl(23,0) = "三重県"
	cnttbl(24,0) = "滋賀県"
	cnttbl(25,0) = "京都府"
	cnttbl(26,0) = "大阪府"
	cnttbl(27,0) = "兵庫県"
	cnttbl(28,0) = "奈良県"
	cnttbl(29,0) = "和歌山県"
	cnttbl(30,0) = "鳥取県"
	cnttbl(31,0) = "島根県"
	cnttbl(32,0) = "岡山県"
	cnttbl(33,0) = "広島県"
	cnttbl(34,0) = "山口県"
	cnttbl(35,0) = "徳島県"
	cnttbl(36,0) = "香川県"
	cnttbl(37,0) = "愛媛県"
	cnttbl(38,0) = "高知県"
	cnttbl(39,0) = "福岡県"
	cnttbl(40,0) = "佐賀県"
	cnttbl(41,0) = "長崎県"
	cnttbl(42,0) = "熊本県"
	cnttbl(43,0) = "大分県"
	cnttbl(44,0) = "宮崎県"
	cnttbl(45,0) = "鹿児島県"
	cnttbl(46,0) = "沖縄県"
	cnttbl(47,0) = "登録無し"

	sql = "SELECT ZC_todoufuken,MM_seibetsu,COUNT(*) AS MM_count"
	sql = sql & " FROM T_MEMBER,T_ZIPCODE"
	sql = sql & " WHERE MM_status<>'9'" & MMsql
	sql = sql & " GROUP BY ZC_todoufuken,MM_seibetsu"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		For i = 0 To 46
			If cnttbl(i,0) = ds("ZC_todoufuken").Value Then
				If ds("MM_seibetsu").Value = "0" Then
					cnttbl(i,2) = Cdbl(ds("MM_count").Value)
				Else
					cnttbl(i,3) = Cdbl(ds("MM_count").Value)
				End If	
				Exit For
			End If
		Next		
		ds.MoveNext
	Loop
	ds.Close
	Select Case infoid
	Case 6
		tblcnt = 46
	Case 7
		sql = "SELECT MM_seibetsu,COUNT(*) AS MM_count"
		sql = sql & " FROM T_MEMBER"
		sql = sql & " WHERE MM_status<>'9' AND MM_zip_kinmu IS NULL"
		sql = sql & " GROUP BY MM_seibetsu"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		Do Until ds.EOF
			If ds("MM_seibetsu").Value = "0" Then
				cnttbl(47,2) = Cdbl(ds("MM_count").Value)
			Else
				cnttbl(47,3) = Cdbl(ds("MM_count").Value)
			End If
			ds.MoveNext
		Loop
		ds.Close
		tblcnt = 47
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
		'-//If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		'-//If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / allcount, 1, -1, 0, -1) Else kmpercent2="- "
		'-//If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / allcount, 1, -1, 0, -1) Else kmpercent3="- "
		If cnttbl(i,1) <> 0 Then kmpercent1 = FormatPercent(cnttbl(i,1) / allcount, 1, -1, 0, -1) Else kmpercent1="- "
		If cnttbl(i,2) <> 0 Then kmpercent2 = FormatPercent(cnttbl(i,2) / mencount, 1, -1, 0, -1) Else kmpercent2="- "
		If cnttbl(i,3) <> 0 Then kmpercent3 = FormatPercent(cnttbl(i,3) / womencount, 1, -1, 0, -1) Else kmpercent3="- "
		If line Mod 2 = 0 Then
			Writeln "<tr bgcolor='#FFFFFF'>"
		Else
			Writeln "<tr bgcolor='#FFEEE0'>"
		End If	
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
Private Sub GetProfile8()
	Dim sql, ds, line, tlname, kmpercent
	Dim MMsql, i, j, tblcnt, tblput, selcount

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■カテゴリ</td></tr>"
	Writeln "</table>"
	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "ＴＯＴＡＬ"
			MMsql = ""
			selcount = allcount
		Case 1
			tlname = "男性"
			MMsql = " AND MM_seibetsu='0'"
			selcount = mencount
		Case 2
			tlname = "女性"
			MMsql = " AND MM_seibetsu='1'"
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
		
		sql = "SELECT KG_category,COUNT(*) AS KG_count"
		sql = sql & " FROM (SELECT DISTINCT MM_member_id,KG_category FROM T_MEMBER,T_KYOUMI_DATA,T_KYOUMI_GENRE"
		sql = sql & " WHERE MM_status<>'9'" & MMsql
		sql = sql & " AND MM_member_id=KD_member_id AND KD_index=KG_index"
		sql = sql & " AND KG_valid_flag='1')"
		sql = sql & " GROUP BY KG_category"
		sql = sql & " ORDER BY KG_count DESC"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		line = 1: j = 0
		Do Until ds.EOF
			j = j + 1
			kmpercent = FormatPercent(ds("KG_count").Value / selcount, 1, -1, 0, -1)
			'kmpercent = "- "
			If line Mod 2 = 0 Then
				Writeln "<tr bgcolor='#FFFFFF'>"
			Else
				Writeln "<tr bgcolor='#FFEEE0'>"
			End If
			Writeln "<td width='14%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='50%' align='left'>" & ds("KG_category").Value & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(ds("KG_count").Value) & "</td>"
			'-//Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "<td width='13%' align='center'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			ds.MoveNext
			line = line + 1
		Loop
		ds.Close
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"		
		End If
	Next
	Writeln "<br clear='left'>"
End Sub

'=== ■興味ジャンル表示 ===
Private Sub GetProfile9()
	Dim sql, ds, line, tlname, kmpercent
	Dim MMsql, i, j, tblcnt, tblput, selcount

	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■興味ジャンル</td></tr>"
	Writeln "</table>"
	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "ＴＯＴＡＬ"
			MMsql = ""
			selcount = allcount
		Case 1
			tlname = "男性"
			'MMsql = " WHERE KD_seibetsu='0'"
			MMsql = " AND MM_seibetsu='0'"
			selcount = mencount
		Case 2
			tlname = "女性"
			'MMsql = " WHERE KD_seibetsu='1'"
			MMsql = " AND MM_seibetsu='1'"
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

		'sql = "SELECT KD_index,KD_count,KG_item_text"
		'sql = sql & " FROM (SELECT KD_index,COUNT(*) AS KD_count"
		'sql = sql & "  FROM T_MEMBER,T_KYOUMI_DATA"
		'sql = sql & "  WHERE MM_status<>'9'" & MMsql
		'sql = sql & "  AND MM_member_id=KD_member_id"
		'sql = sql & "  GROUP BY KD_index"
		'sql = sql & " ),T_KYOUMI_GENRE"
		'sql = sql & " WHERE KD_index=KG_index AND KG_valid_flag='1'"
		'sql = sql & " ORDER BY KD_count DESC"

		'sql = "SELECT KD_index,KD_count,KG_item_text"
		'sql = sql & " FROM (SELECT KD_index,COUNT(*) AS KD_count"
		'sql = sql & "  FROM T_KYOUMI_DATA"
		'sql = sql & MMsql
		'sql = sql & "  GROUP BY KD_index"
		'sql = sql & " ),T_KYOUMI_GENRE"
		'sql = sql & " WHERE KD_index=KG_index AND KG_valid_flag='1'"
		'sql = sql & " ORDER BY KD_count DESC"

		'sql = "SELECT KD_index,COUNT(*) AS KD_count,KG_item_text"
		'sql = sql & " FROM T_KYOUMI_DATA,T_KYOUMI_GENRE"
		'sql = sql & " WHERE KG_valid_flag='1'" & MMsql & " AND KD_index=KG_index"
		'sql = sql & " GROUP BY KD_index,KG_item_text"
		'sql = sql & " ORDER BY KD_count DESC"

		sql = "SELECT KD_index,COUNT(*) AS KD_count,KG_item_text"
		sql = sql & " FROM T_MEMBER,T_KYOUMI_DATA,T_KYOUMI_GENRE"
		sql = sql & " WHERE MM_status<>'9'" & MMsql
		sql = sql & " AND MM_member_id=KD_member_id AND KD_index=KG_index"
		sql = sql & " AND KG_valid_flag='1'"
		sql = sql & " GROUP BY KD_index,KG_item_text"
		sql = sql & " ORDER BY KD_count DESC"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		line = 1: j = 0
		Do Until ds.EOF
			j = j + 1
			'-//kmpercent = FormatPercent(ds("KD_count").Value / allcount, 1, -1, 0, -1)
			kmpercent = FormatPercent(ds("KD_count").Value / selcount, 1, -1, 0, -1)
			If line Mod 2 = 0 Then
				Writeln "<tr bgcolor='#FFFFFF'>"
			Else
				Writeln "<tr bgcolor='#FFEEE0'>"
			End If	
			Writeln "<td width='8%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='56%' align='left'>" & ds("KG_item_text").Value & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(ds("KD_count").Value) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			ds.MoveNext
			line = line + 1
		Loop
		ds.Close
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"		
		End If
	Next
	Writeln "<br clear='left'>"
End Sub

'=== ■興味ジャンル（興味度別計/興味度別男性/興味度別女性）表示 ===
Private Sub GetProfile10(infoid)
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim MMsql, i, tblcnt, tblput, selcount, getcnt

	Select Case infoid
	Case 10
		tlname = "興味度別計"
		MMsql = ""
		selcount = allcount
	Case 11
		tlname = "興味度別男性"
		MMsql = " AND MM_seibetsu='0'"
		selcount = mencount
	Case 12
		tlname = "興味度別女性"
		MMsql = " AND MM_seibetsu='1'"
		selcount = womencount
	End Select
	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■興味ジャンル（" & tlname & "）</td></tr>"
	Writeln "</table>"

	sql = "SELECT KD_index,KD_data,COUNT(*) AS KD_count,KG_item_no,KG_item_text"
	sql = sql & " FROM T_MEMBER,T_KYOUMI_DATA,T_KYOUMI_GENRE"
	sql = sql & " WHERE MM_status<>'9'" & MMsql
	sql = sql & " AND MM_member_id=KD_member_id AND KD_index=KG_index"
	sql = sql & " AND KG_valid_flag='1'"
	sql = sql & " GROUP BY KD_index,KD_data,KG_item_no,KG_item_text"
	sql = sql & " ORDER BY KG_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Dim cnttbl(999,4): tblcnt = 0
	Do Until ds.EOF
		tblput = True
		For i = 1 To tblcnt
			If cnttbl(i,0) = ds("KD_index").Value Then
				If ds("KD_data").Value = "1" Then
					cnttbl(i,2) = Cdbl(ds("KD_count").Value)
				Else
					cnttbl(i,3) = Cdbl(ds("KD_count").Value)
				End If
				tblput = False			
				Exit For
			End If
		Next		
		If tblput = True Then
			tblcnt = tblcnt + 1
			cnttbl(tblcnt,0) = ds("KD_index").Value 
			cnttbl(tblcnt,1) = ds("KG_item_text").Value
			If ds("KD_data").Value = "1" Then
				cnttbl(i,2) = Cdbl(ds("KD_count").Value)
			Else
				cnttbl(i,3) = Cdbl(ds("KD_count").Value)
			End If
		End If
		ds.MoveNext
	Loop
	ds.Close
	'■Sort_Start(｢かなりある｣+｢普通にある｣の降順)
    Dim PartSize, NumPart, Low, High 
    Dim si, sj, stbl(4)

    PartSize = tblcnt / 2
    Do
        NumPart = tblcnt / PartSize
        Low = 1
        For si = 1 To NumPart
            High = Low + PartSize
            If High > tblcnt - PartSize Then
                High = tblcnt - PartSize
            End If
            For sj = Low To High
                If cnttbl(sj,2)+cnttbl(sj,3) < cnttbl(sj+PartSize,2)+cnttbl(sj+PartSize,3) Then
                    stbl(0) = cnttbl(sj,0): stbl(1) = cnttbl(sj,1): stbl(2) = cnttbl(sj,2): stbl(3) = cnttbl(sj,3): stbl(4) = cnttbl(sj,4)
                    cnttbl(sj,0) = cnttbl(sj+PartSize,0): cnttbl(sj,1) = cnttbl(sj+PartSize,1): cnttbl(sj,2) = cnttbl(sj+PartSize,2): cnttbl(sj,3) = cnttbl(sj+PartSize,3): cnttbl(sj,4) = cnttbl(sj+PartSize,4)
                    cnttbl(sj+PartSize,0) = stbl(0): cnttbl(sj+PartSize,1) = stbl(1): cnttbl(sj+PartSize,2) = stbl(2): cnttbl(sj+PartSize,3) = stbl(3): cnttbl(sj+PartSize,4) = stbl(4)
                End If
            Next
            Low = Low + PartSize
        Next
        PartSize = PartSize - 1
    Loop While PartSize > 0
	'■Sort_End(｢かなりある｣+｢普通にある｣の降順)
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
	For i = 1 To tblcnt
		cnttbl(i,4) = selcount - (cnttbl(i,2) + cnttbl(i,3))
		'-//kmpercent1 = FormatPercent(cnttbl(i,2) / allcount, 1, -1, 0, -1)
		'-//kmpercent2 = FormatPercent(cnttbl(i,3) / allcount, 1, -1, 0, -1)
		'-//kmpercent3 = FormatPercent(cnttbl(i,4) / allcount, 1, -1, 0, -1)
		kmpercent1 = FormatPercent(cnttbl(i,2) / selcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(cnttbl(i,3) / selcount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(cnttbl(i,4) / selcount, 1, -1, 0, -1)
		If line Mod 2 = 0 Then
			Writeln "<tr bgcolor='#FFFFFF'>"
		Else
			Writeln "<tr bgcolor='#FFEEE0'>"
		End If	
		Writeln "<td width='5%' align='right'>" & Cstr(i) & "</td>"
		Writeln "<td width='38%' colspan=2 align='left'>" & cnttbl(i,1) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(cnttbl(i,3)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='12%' align='right'>" & NumFormat2(cnttbl(i,4)) & "</td>"
		Writeln "<td width='7%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■よく行くコンビニ/よく行くスーパー/よく利用する業態やサービス/よく利用するお店やサービス表示 ===
Private Sub GetProfile13(infoid)
	Dim sql, ds, line, tlname, kmpercent
	Dim YMsql, MMsql, i, j, tblcnt, tblput, selcount, maxline, getcnt

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
	
	For i = 0 To 2
		Select Case i
		Case 0
			tlname = "計"
			MMsql = ""
			selcount = allcount
		Case 1
			tlname = "男性"
			MMsql = " AND MM_seibetsu='0'"
			selcount = mencount
		Case 2
			tlname = "女性"
			MMsql = " AND MM_seibetsu='1'"
			selcount = womencount
		End Select
		Writeln "<table align='left' border=1 cellpadding=1 cellspacing=0 width='32%' class='small'>"
		Writeln "<tr bgcolor='#E0FFE0'>"
		Writeln "<th>" & tlname & "</th>"
		Writeln "<th>　</th>"
		Writeln "<th>人数</th>"
		Writeln "<th>％</th>"
		Writeln "</tr>"

		sql = "SELECT YD_index,COUNT(*) AS YD_count,YM_item_text"
		sql = sql & " FROM T_MEMBER,T_YOKUIKU_DATA,T_YOKUIKU_MISE"
		sql = sql & " WHERE MM_status<>'9'" & MMsql
		sql = sql & " AND MM_member_id=YD_member_id AND YD_index=YM_index"
		sql = sql & " AND YM_valid_flag='1'" & YMsql
		sql = sql & " GROUP BY YD_index,YM_item_text"
		sql = sql & " ORDER BY YD_count DESC"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		line = 1: j = 0
		Do Until ds.EOF
			j = j + 1
			'-//kmpercent = FormatPercent(ds("YD_count").Value / allcount, 1, -1, 0, -1)
			kmpercent = FormatPercent(ds("YD_count").Value / selcount, 1, -1, 0, -1)
			If line Mod 2 = 0 Then
				Writeln "<tr bgcolor='#FFFFFF'>"
			Else
				Writeln "<tr bgcolor='#FFEEE0'>"
			End If	
			Writeln "<td width='13%' align='right'>" & Cstr(j) & "</td>"
			Writeln "<td width='51%' align='left'>" & ds("YM_item_text").Value & "</td>"
			Writeln "<td width='23%' align='right'>" & NumFormat2(ds("YD_count").Value) & "</td>"
			Writeln "<td width='13%' align='right'>" & Left(kmpercent,Len(kmpercent) - 1) & "</td>"
			Writeln "</tr>"
			ds.MoveNext
			line = line + 1
		Loop
		ds.Close
		Writeln "</table>"
		If i <> 2 Then
			Writeln "<table align='left' border=0 cellpadding=1 cellspacing=0 width='2%' class='small'>"
			Writeln "<tr><td>　</td></tr>"
			Writeln "</table>"		
		End If
	Next
	Writeln "<br clear='left'>"
End Sub

'=== ■どっち派（計/男性/女性）表示 ===
Private Sub GetProfile17(infoid)
	Dim sql, ds, line, tlname, kmpercent1, kmpercent2, kmpercent3
	Dim MMsql, i, tblcnt, tblput, selcount, getcnt
	
	Select Case infoid
	Case 17
		tlname = "計"
		MMsql = ""
		selcount = allcount
	Case 18
		tlname = "男性"
		MMsql = " AND MM_seibetsu='0'"
		selcount = mencount
	Case 19
		tlname = "女性"
		MMsql = " AND MM_seibetsu='1'"
		selcount = womencount
	End Select
	Writeln "<table border=0 cellpadding=0 cellspacing=1 width='100%' class='small'>"
    Writeln "<tr><td>■どっち派（" & tlname & "）</td></tr>"
	Writeln "</table>"
	
	sql = "SELECT DD_index,DD_data,COUNT(*) AS DD_count,DH_item_no,DH_item1_text,DH_item2_text"
	sql = sql & " FROM T_MEMBER,T_DOCCHI_DATA,T_DOCCHI_HA"
	sql = sql & " WHERE MM_status<>'9'" & MMsql
	sql = sql & " AND MM_member_id=DD_member_id AND DD_index=DH_index"
	sql = sql & " AND DH_valid_flag='1'"
	sql = sql & " GROUP BY DD_index,DD_data,DH_item_no,DH_item1_text,DH_item2_text"
	sql = sql & " ORDER BY DH_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Dim cnttbl(999,5): tblcnt = 0
	Do Until ds.EOF
		tblput = True
		For i = 1 To tblcnt
			If cnttbl(i,0) = ds("DD_index").Value Then
				If ds("DD_data").Value = "1" Then
					cnttbl(i,2) = Cdbl(ds("DD_count").Value)
				Else
					cnttbl(i,4) = Cdbl(ds("DD_count").Value)
				End If	
				tblput = False			
				Exit For
			End If
		Next		
		If tblput = True Then
			tblcnt = tblcnt + 1
			cnttbl(tblcnt,0) = ds("DD_index").Value 
			cnttbl(tblcnt,1) = ds("DH_item1_text").Value
			cnttbl(tblcnt,3) = ds("DH_item2_text").Value
			If ds("DD_data").Value = "1" Then
				cnttbl(i,2) = Cdbl(ds("DD_count").Value)
			Else
				cnttbl(i,4) = Cdbl(ds("DD_count").Value)
			End If	
		End If
		ds.MoveNext
	Loop
	ds.Close
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
	For i = 1 To tblcnt
		cnttbl(i,5) = selcount - (cnttbl(i,2) + cnttbl(i,4))
		'-//kmpercent1 = FormatPercent(cnttbl(i,2) / allcount, 1, -1, 0, -1)
		'-//kmpercent2 = FormatPercent(cnttbl(i,4) / allcount, 1, -1, 0, -1)
		'-//kmpercent3 = FormatPercent(cnttbl(i,5) / allcount, 1, -1, 0, -1)
		kmpercent1 = FormatPercent(cnttbl(i,2) / selcount, 1, -1, 0, -1)
		kmpercent2 = FormatPercent(cnttbl(i,4) / selcount, 1, -1, 0, -1)
		kmpercent3 = FormatPercent(cnttbl(i,5) / selcount, 1, -1, 0, -1)
		If line Mod 2 = 0 Then
			Writeln "<tr bgcolor='#FFFFFF'>"
		Else
			Writeln "<tr bgcolor='#FFEEE0'>"
		End If	
		Writeln "<td width='3%' align='right'>" & Cstr(i) & "</td>"
		Writeln "<td width='15%' align='left'>" & cnttbl(i,1) & "</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(cnttbl(i,2)) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent1,Len(kmpercent1) - 1) & "</td>"
		Writeln "<td width='15%' align='left'>" & cnttbl(i,3) & "</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(cnttbl(i,4)) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent2,Len(kmpercent2) - 1) & "</td>"
		Writeln "<td width='15%' align='left'>どちらでもない</td>"
		Writeln "<td width='7%' align='right'>" & NumFormat2(cnttbl(i,5)) & "</td>"
		Writeln "<td width='4%' align='right'>" & Left(kmpercent3,Len(kmpercent3) - 1) & "</td>"
		Writeln "</tr>"
		line = line + 1
	Next
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== メイン処理 ===
Dim infoid, infonm(20), allcount, mencount, womencount
Dim s_time

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
infonm(20) = "全項目"

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
	<form method="post">
	<tr>
		<!-- <td><font class="hc">情報選択</font><select name="infoid"><%WriteInfo(infoid)%></select> -->
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
'-//s_time = Time()	
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
	If infoid = 10 Or infoid = 20 Then GetProfile10(10)
	If infoid = 11 Or infoid = 20 Then GetProfile10(11)
	If infoid = 12 Or infoid = 20 Then GetProfile10(12)
	If infoid = 13 Or infoid = 20 Then GetProfile13(13)
	If infoid = 14 Or infoid = 20 Then GetProfile13(14)
	If infoid = 15 Or infoid = 20 Then GetProfile13(15)
	If infoid = 16 Or infoid = 20 Then GetProfile13(16)
	If infoid = 17 Or infoid = 20 Then GetProfile17(17)
	If infoid = 18 Or infoid = 20 Then GetProfile17(18)
	If infoid = 19 Or infoid = 20 Then GetProfile17(19)
End If
'-//Response.Write "Process_Time=" & Minute(Time()-s_time) & ":" & Second(Time()-s_time) & "::<br>"
%>
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<form id=form1 name=form1>
	<tr><br></tr>
	<tr>
	<td align="center"><input type="button" value=" 戻る " onclick="location.href='../index.asp'"></td>
	</tr>
	</form>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
