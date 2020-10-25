<%
Class CSearch
	Public seibetsu_m
	Public seibetsu_f
	Public nenrei_from
	Public nenrei_to
	Public mikikon_mi
	Public mikikon_ki
	Public shokugyou
	Public kyojuu_shitei
	Public kyojuu
	Public kinmu_shitei
	Public kinmu_andor
	Public kinmu
	Public kyoumi_genre_shitei
	Public kyoumi_genre_andor
	Public kyoumi_genre1
	Public kyoumi_genre2
	Public yokuikumise_shitei
	Public yokuikumise
	Public docchiha_shitei
	Public docchiha
	Public sql_shitei
	Public sql_text
	Public zip_shitei
	Public zip
	Public jogai_hasshin_shitei
	Public jogai_hasshin
	Public jogai_response_shitei
	Public jogai_response
	Public taishou_hasshin_shitei
	Public taishou_hasshin
	Public taishou_response_shitei
	Public taishou_response
	Public member_list_shitei
	Public member_list
	Public html_mail_shitei
	Public html_mail
	Public oshirase_mail_shitei
	Public oshirase_mail
	Public jogai_baitai
	Public taishou_baitai
	Public jogai_domain
	Public taishou_domain

	'=== 初期化処理 ===
	Private Sub Class_Initialize()
		kinmu_andor = "O"
		kyoumi_genre_andor = "O"
		html_mail = "1"
		oshirase_mail = "1"
	End Sub

	'=== 複数チェックボックス一括取得 ===
	Private Function GetMultiFlag(name, n)
		Redim a(n)
		Dim i, c, data

		For i = 0 To n - 1
			a(i) = " "
		Next

		Set data = Request.Form(name)
		If Not IsEmpty(data.Item) Then
			For Each c In data
				a(c - 1) = "1"
			Next
		End If

		GetMultiFlag = Join(a, "")
	End Function

	'=== 複数データ一括取得 ===
	Private Function GetMultiData(name)
		Dim c, data

		GetMultiData = ""
		Set data = Request.Form(name)
		If Not IsEmpty(data.Item) Then
			For Each c In data
				If GetMultiData <> "" Then
					GetMultiData = GetMultiData & ","
				End If
				GetMultiData = GetMultiData & c
			Next
		End If
	End Function

	'=== 複数ラジオボタン一括取得 ===
	Private Function GetMultiRadio(name, n)
		Dim i

		GetMultiRadio = ""
		For i = 1 To n
			If IsEmpty(Request.Form(name & i)) Then
				GetMultiRadio = GetMultiRadio & " "
			Else
				GetMultiRadio = GetMultiRadio & Request.Form(name & i)
			End If
		Next
	End Function

	'=== フォームからデータ取得 ===
	Public Sub GetData()
		'性別
		seibetsu_m = Request.Form("seibetsu_m")
		seibetsu_f = Request.Form("seibetsu_f")

		'年齢
		nenrei_from = Request.Form("nenrei_from")
		nenrei_to = Request.Form("nenrei_to")

		'未既婚
		mikikon_mi = Request.Form("mikikon_mi")
		mikikon_ki = Request.Form("mikikon_ki")

		'職業
		shokugyou = GetMultiData("shokugyou")

		'居住地
		kyojuu_shitei = Request.Form("kyojuu_shitei")
		kyojuu = GetMultiData("kyojuu")

		'勤務地
		kinmu_shitei = Request.Form("kinmu_shitei")
		kinmu_andor = Request.Form("kinmu_andor")
		kinmu = GetMultiData("kinmu")

		'興味ジャンル
		kyoumi_genre_shitei = Request.Form("kyoumi_genre_shitei")
		kyoumi_genre_andor = Request.Form("kyoumi_genre_andor")
		kyoumi_genre1 = GetMultiFlag("kyoumi_genre1", 200)
		kyoumi_genre2 = GetMultiFlag("kyoumi_genre2", 200)

		'よく行く店
		yokuikumise_shitei = Request.Form("yokuikumise_shitei")
		yokuikumise = GetMultiFlag("yokuikumise", 200)

		'どっち派
		docchiha_shitei = Request.Form("docchiha_shitei")
		docchiha = GetMultiRadio("docchiha", 100)

		'SQL
		sql_shitei = Request.Form("sql_shitei")
		sql_text = Request.Form("sql_text")

		'郵便番号指定
		zip_shitei = Request.Form("zip_shitei")
		zip = Request.Form("zip")

		'対象JOB選択
		jogai_hasshin_shitei = Request.Form("jogai_hasshin_shitei")
		jogai_hasshin = Request.Form("jogai_hasshin")
		jogai_response_shitei = Request.Form("jogai_response_shitei")
		jogai_response = Request.Form("jogai_response")
		taishou_hasshin_shitei = Request.Form("taishou_hasshin_shitei")
		taishou_hasshin = Request.Form("taishou_hasshin")
		taishou_response_shitei = Request.Form("taishou_response_shitei")
		taishou_response = Request.Form("taishou_response")

		'メンバーリスト生成
		member_list_shitei =  Request.Form("member_list_shitei")
		member_list =  Request.Form("member_list")

		'HTMLメール受信
		html_mail_shitei =  Request.Form("html_mail_shitei")
		html_mail =  Request.Form("html_mail")

		'お知らせメール受信
		oshirase_mail_shitei =  Request.Form("oshirase_mail_shitei")
		oshirase_mail =  Request.Form("oshirase_mail")

		'除外媒体
		If Request.Form("jogai_baitai_shitei") = "1" Then
			jogai_baitai = GetMultiData("jogai_baitai")
		Else
			jogai_baitai = ""
		End If

		'対象媒体
		If Request.Form("taishou_baitai_shitei") = "1" Then
			taishou_baitai = GetMultiData("taishou_baitai")
		Else
			taishou_baitai = ""
		End If

		'除外ドメイン
		If Request.Form("jogai_domain_shitei") = "1" Then
			jogai_domain = Request.Form("jogai_domain")
		Else
			jogai_domain = ""
		End If

		'対象ドメイン
		If Request.Form("taishou_domain_shitei") = "1" Then
			taishou_domain = Request.Form("taishou_domain")
		Else
			taishou_domain = ""
		End If
	End Sub

	'=== 検索条件をhiddenに出力 ===
	Public Sub WriteCondition()
		If seibetsu_m <> "" Then Writeln "<input type='hidden' name='seibetsu_m' value='" & seibetsu_m & "'>"
		If seibetsu_f <> "" Then Writeln "<input type='hidden' name='seibetsu_f' value='" & seibetsu_f & "'>"
		If nenrei_from <> "" Then Writeln "<input type='hidden' name='nenrei_from' value='" & nenrei_from & "'>"
		If nenrei_to <> "" Then Writeln "<input type='hidden' name='nenrei_to' value='" & nenrei_to & "'>"
		If mikikon_mi <> "" Then Writeln "<input type='hidden' name='mikikon_mi' value='" & mikikon_mi & "'>"
		If mikikon_ki <> "" Then Writeln "<input type='hidden' name='mikikon_ki' value='" & mikikon_ki & "'>"
		If shokugyou <> "" Then Writeln "<input type='hidden' name='shokugyou' value='" & shokugyou & "'>"
		If kyojuu_shitei <> "" Then Writeln "<input type='hidden' name='kyojuu_shitei' value='" & kyojuu_shitei & "'>"
		If kyojuu <> "" Then Writeln "<input type='hidden' name='kyojuu' value='" & kyojuu & "'>"
		If kinmu_shitei <> "" Then Writeln "<input type='hidden' name='kinmu_shitei' value='" & kinmu_shitei & "'>"
		If kinmu_andor <> "" Then Writeln "<input type='hidden' name='kinmu_andor' value='" & kinmu_andor & "'>"
		If kinmu <> "" Then Writeln "<input type='hidden' name='kinmu' value='" & kinmu & "'>"
		If kyoumi_genre_shitei <> "" Then Writeln "<input type='hidden' name='kyoumi_genre_shitei' value='" & kyoumi_genre_shitei & "'>"
		If kyoumi_genre_andor <> "" Then Writeln "<input type='hidden' name='kyoumi_genre_andor' value='" & kyoumi_genre_andor & "'>"
		If kyoumi_genre1 <> "" Then Writeln "<input type='hidden' name='kyoumi_genre1' value='" & kyoumi_genre1 & "'>"
		If kyoumi_genre2 <> "" Then Writeln "<input type='hidden' name='kyoumi_genre2' value='" & kyoumi_genre2 & "'>"
		If yokuikumise_shitei <> "" Then Writeln "<input type='hidden' name='yokuikumise_shitei' value='" & yokuikumise_shitei & "'>"
		If yokuikumise <> "" Then Writeln "<input type='hidden' name='yokuikumise' value='" & yokuikumise & "'>"
		If docchiha_shitei <> "" Then Writeln "<input type='hidden' name='docchiha_shitei' value='" & docchiha_shitei & "'>"
		If docchiha <> "" Then Writeln "<input type='hidden' name='docchiha' value='" & docchiha & "'>"
		If sql_shitei <> "" Then Writeln "<input type='hidden' name='sql_shitei' value='" & sql_shitei & "'>"
		If sql_text <> "" Then Writeln "<input type='hidden' name='sql_text' value='" & sql_text & "'>"
		If zip_shitei <> "" Then Writeln "<input type='hidden' name='zip_shitei' value='" & zip_shitei & "'>"
		If zip <> "" Then Writeln "<input type='hidden' name='zip' value='" & zip & "'>"
		If jogai_hasshin_shitei <> "" Then Writeln "<input type='hidden' name='jogai_hasshin_shitei' value='" & jogai_hasshin_shitei & "'>"
		If jogai_hasshin <> "" Then Writeln "<input type='hidden' name='jogai_hasshin' value='" & jogai_hasshin & "'>"
		If jogai_response_shitei <> "" Then Writeln "<input type='hidden' name='jogai_response_shitei' value='" & jogai_response_shitei & "'>"
		If jogai_response <> "" Then Writeln "<input type='hidden' name='jogai_response' value='" & jogai_response & "'>"
		If taishou_hasshin_shitei <> "" Then Writeln "<input type='hidden' name='taishou_hasshin_shitei' value='" & taishou_hasshin_shitei & "'>"
		If taishou_hasshin <> "" Then Writeln "<input type='hidden' name='taishou_hasshin' value='" & taishou_hasshin & "'>"
		If taishou_response_shitei <> "" Then Writeln "<input type='hidden' name='taishou_response_shitei' value='" & taishou_response_shitei & "'>"
		If taishou_response <> "" Then Writeln "<input type='hidden' name='taishou_response' value='" & taishou_response & "'>"
		If member_list_shitei <> "" Then Writeln "<input type='hidden' name='member_list_shitei' value='" & member_list_shitei & "'>"
		If member_list <> "" Then Writeln "<input type='hidden' name='member_list' value='" & member_list & "'>"
		If html_mail_shitei <> "" Then Writeln "<input type='hidden' name='html_mail_shitei' value='" & html_mail_shitei & "'>"
		If html_mail <> "" Then Writeln "<input type='hidden' name='html_mail' value='" & html_mail & "'>"
		If oshirase_mail_shitei <> "" Then Writeln "<input type='hidden' name='oshirase_mail_shitei' value='" & oshirase_mail_shitei & "'>"
		If oshirase_mail <> "" Then Writeln "<input type='hidden' name='oshirase_mail' value='" & oshirase_mail & "'>"
		If jogai_baitai <> "" Then Writeln "<input type='hidden' name='jogai_baitai' value='" & jogai_baitai & "'>"
		If taishou_baitai <> "" Then Writeln "<input type='hidden' name='taishou_baitai' value='" & taishou_baitai & "'>"
		If jogai_domain <> "" Then Writeln "<input type='hidden' name='jogai_domain' value='" & jogai_domain & "'>"
		If taishou_domain <> "" Then Writeln "<input type='hidden' name='taishou_domain' value='" & taishou_domain & "'>"
	End Sub

	'=== 検索条件をhiddenから取得 ===
	Public Sub ReadCondition()
		seibetsu_m = Request.Form("seibetsu_m")
		seibetsu_f = Request.Form("seibetsu_f")
		nenrei_from = Request.Form("nenrei_from")
		nenrei_to = Request.Form("nenrei_to")
		mikikon_mi = Request.Form("mikikon_mi")
		mikikon_ki = Request.Form("mikikon_ki")
		shokugyou = Request.Form("shokugyou")
		kyojuu_shitei = Request.Form("kyojuu_shitei")
		kyojuu = Request.Form("kyojuu")
		kinmu_shitei = Request.Form("kinmu_shitei")
		kinmu_andor = Request.Form("kinmu_andor")
		kinmu = Request.Form("kinmu")
		kyoumi_genre_shitei = Request.Form("kyoumi_genre_shitei")
		kyoumi_genre_andor = Request.Form("kyoumi_genre_andor")
		kyoumi_genre1 = Request.Form("kyoumi_genre1")
		kyoumi_genre2 = Request.Form("kyoumi_genre2")
		yokuikumise_shitei = Request.Form("yokuikumise_shitei")
		yokuikumise = Request.Form("yokuikumise")
		docchiha_shitei = Request.Form("docchiha_shitei")
		docchiha = Request.Form("docchiha")
		sql_shitei = Request.Form("sql_shitei")
		sql_text = Request.Form("sql_text")
		zip_shitei = Request.Form("zip_shitei")
		zip = Request.Form("zip")
		jogai_hasshin_shitei = Request.Form("jogai_hasshin_shitei")
		jogai_hasshin = Request.Form("jogai_hasshin")
		jogai_response_shitei = Request.Form("jogai_response_shitei")
		jogai_response = Request.Form("jogai_response")
		taishou_hasshin_shitei = Request.Form("taishou_hasshin_shitei")
		taishou_hasshin = Request.Form("taishou_hasshin")
		taishou_response_shitei = Request.Form("taishou_response_shitei")
		taishou_response = Request.Form("taishou_response")
		member_list_shitei = Request.Form("member_list_shitei")
		member_list = Request.Form("member_list")
		html_mail_shitei = Request.Form("html_mail_shitei")
		html_mail = Request.Form("html_mail")
		oshirase_mail_shitei = Request.Form("oshirase_mail_shitei")
		oshirase_mail = Request.Form("oshirase_mail")
		jogai_baitai = Request.Form("jogai_baitai")
		taishou_baitai = Request.Form("taishou_baitai")
		jogai_domain = Request.Form("jogai_domain")
		taishou_domain = Request.Form("taishou_domain")
 End Sub

	'=== 検索条件をＤＢから読み込み ===
	Public Sub ReadDB(search_id)
		Dim sql, ds

		sql = "SELECT * FROM T_SEARCH WHERE SE_search_id=" & search_id
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			kyoumi_genre_shitei = ds("SE_kyoumi_genre_shitei").Value
			kyoumi_genre_andor = ds("SE_kyoumi_genre_andor").Value
			kyoumi_genre1 = ds("SE_kyoumi_genre1").Value
			kyoumi_genre2 = ds("SE_kyoumi_genre2").Value
			seibetsu_m = ds("SE_seibetsu_m").Value
			seibetsu_f = ds("SE_seibetsu_f").Value
			nenrei_from = ds("SE_nenrei_from").Value
			nenrei_to = ds("SE_nenrei_to").Value
			mikikon_mi = ds("SE_mikikon_mi").Value
			mikikon_ki = ds("SE_mikikon_ki").Value
			shokugyou = ds("SE_shokugyou").Value
			kyojuu_shitei = ds("SE_kyojuu_shitei").Value
			kyojuu = ds("SE_kyojuu").Value
			kinmu_shitei = ds("SE_kinmu_shitei").Value
			kinmu_andor = ds("SE_kinmu_andor").Value
			kinmu = ds("SE_kinmu").Value
			yokuikumise_shitei = ds("SE_yokuikumise_shitei").Value
			yokuikumise = ds("SE_yokuikumise").Value
			docchiha_shitei = ds("SE_docchiha_shitei").Value
			docchiha = ds("SE_docchiha").Value
			sql_shitei = ds("SE_sql_shitei").Value
			sql_text = ds("SE_sql_text").Value
			zip_shitei = ds("SE_zip_shitei").Value
			zip = ds("SE_zip").Value
			jogai_hasshin_shitei = ds("SE_jogai_hasshin_shitei").Value
			jogai_hasshin = ds("SE_jogai_hasshin").Value
			jogai_response_shitei = ds("SE_jogai_response_shitei").Value
			jogai_response = ds("SE_jogai_response").Value
			taishou_hasshin_shitei = ds("SE_taishou_hasshin_shitei").Value
			taishou_hasshin = ds("SE_taishou_hasshin").Value
			taishou_response_shitei = ds("SE_taishou_response_shitei").Value
			taishou_response = ds("SE_taishou_response").Value
			member_list_shitei = ds("SE_member_list_shitei").Value
			member_list = ds("SE_member_list2").Value
			If IsNull(member_list) Then
				member_list = ds("SE_member_list").Value
			End If
			html_mail_shitei = ds("SE_html_mail_shitei").Value
			html_mail = ds("SE_html_mail").Value
			oshirase_mail_shitei = ds("SE_oshirase_mail_shitei").Value
			oshirase_mail = ds("SE_oshirase_mail").Value
			jogai_baitai = ds("SE_jogai_baitai").Value
			taishou_baitai = ds("SE_taishou_baitai").Value
			jogai_domain = ds("SE_jogai_domain").Value
			taishou_domain = ds("SE_taishou_domain").Value
		End If
	End Sub

	'=== 検索条件をＤＢに格納 ===
	Public Sub WriteDB(search_id)
		Dim sql, ds

		sql = "SELECT * FROM T_SEARCH WHERE SE_search_id=" & search_id
		Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
		If ds.EOF Then
			ds.AddNew
			ds("SE_search_id").Value = search_id
		Else
			ds.Edit
		End If

		ds("SE_seibetsu_m").Value = seibetsu_m
		ds("SE_seibetsu_f").Value = seibetsu_f
		ds("SE_nenrei_from").Value = nenrei_from
		ds("SE_nenrei_to").Value = nenrei_to
		ds("SE_mikikon_mi").Value = mikikon_mi
		ds("SE_mikikon_ki").Value = mikikon_ki
		ds("SE_shokugyou").Value = shokugyou
		ds("SE_kyojuu_shitei").Value = kyojuu_shitei
		ds("SE_kyojuu").Value = kyojuu
		ds("SE_kinmu_shitei").Value = kinmu_shitei
		ds("SE_kinmu_andor").Value = kinmu_andor
		ds("SE_kinmu").Value = kinmu
		ds("SE_kyoumi_genre_shitei").Value = kyoumi_genre_shitei
		ds("SE_kyoumi_genre_andor").Value = kyoumi_genre_andor
		ds("SE_kyoumi_genre1").Value = kyoumi_genre1
		ds("SE_kyoumi_genre2").Value = kyoumi_genre2
		ds("SE_yokuikumise_shitei").Value = yokuikumise_shitei
		ds("SE_yokuikumise").Value = yokuikumise
		ds("SE_docchiha_shitei").Value = docchiha_shitei
		ds("SE_docchiha").Value = docchiha
		ds("SE_sql_shitei").Value = sql_shitei
		ds("SE_sql_text").Value = sql_text
		ds("SE_zip_shitei").Value = zip_shitei
		ds("SE_zip").Value = zip
		ds("SE_jogai_hasshin_shitei").Value = jogai_hasshin_shitei
		ds("SE_jogai_hasshin").Value = jogai_hasshin
		ds("SE_jogai_response_shitei").Value = jogai_response_shitei
		ds("SE_jogai_response").Value = jogai_response
		ds("SE_taishou_hasshin_shitei").Value = taishou_hasshin_shitei
		ds("SE_taishou_hasshin").Value = taishou_hasshin
		ds("SE_taishou_response_shitei").Value = taishou_response_shitei
		ds("SE_taishou_response").Value = taishou_response
		ds("SE_member_list_shitei").Value = member_list_shitei
		ds("SE_member_list").Value = Null
		ds("SE_member_list2").Value = member_list
		ds("SE_html_mail_shitei").Value = html_mail_shitei
		ds("SE_html_mail").Value = html_mail
		ds("SE_oshirase_mail_shitei").Value = oshirase_mail_shitei
		ds("SE_oshirase_mail").Value = oshirase_mail
		ds("SE_jogai_baitai").Value = jogai_baitai
		ds("SE_taishou_baitai").Value = taishou_baitai
		ds("SE_jogai_domain").Value = jogai_domain
		ds("SE_taishou_domain").Value = taishou_domain
		ds.Update
	End Sub

	'=== 検索用SQLのWHERE句生成 ===
	Public Function MakeSQL()
		Dim sql, temp, temp2, arry, item, n, i, and_or, kaigai, ds

		MakeSQL = ""
		sql = ""

		'性別
		temp = ""
		If seibetsu_m = "1" Then
			If seibetsu_f = "1" Then
			Else
				temp = "MM_seibetsu='0'"
			End If
		Else
			If seibetsu_f = "1" Then
				temp = "MM_seibetsu='1'"
			End If
		End If
		AndJoin sql, temp

		'年齢
		temp = ""
		If nenrei_from <> "" Then
			temp = "MM_seinengappi<=ADD_MONTHS(SYSDATE," & -nenrei_from * 12 & ")"
		End If
		If nenrei_to <> "" Then
			AndJoin temp, "MM_seinengappi>ADD_MONTHS(SYSDATE," & -(nenrei_to+1) * 12 & ")"
		End If
		AndJoin sql, temp

		'未既婚
		temp = ""
		If mikikon_mi = "1" Then
			If mikikon_ki = "1" Then
			Else
				temp = "MM_mikikon='0'"
			End If
		Else
			If mikikon_ki = "1" Then
				temp = "MM_mikikon='1'"
			End If
		End If
		AndJoin sql, temp

		'職業
		temp = ""
		arry = Split(shokugyou, ",")
		For Each item In arry
			OrJoin temp, "MM_shokugyou='" & item & "'"
		Next
		If temp <> "" Then
			AndJoin sql, "(" & temp & ")"
		End If

		'居住地
		temp = ""
		If kyojuu_shitei = "1" Then
			arry = Split(kyojuu, ",")
			For Each item In arry
				n = InStr(item, "/")
				If n > 0 Then
					OrJoin temp, "ZC_todoufuken='" & Left(item, n - 1) & "'"
					AndJoin temp, "ZC_shikuchouson LIKE '" & Mid(item, n + 1) & "%'"
				Else
					If item <> "海外" Then
						OrJoin temp, "ZC_todoufuken='" & item & "'"
					End If
				End If
			Next
			If temp <> "" Then
				temp = "EXISTS (SELECT * FROM T_ZIPCODE WHERE ZC_zip_uniq=MM_zip_kyojuu AND (" & temp & "))"
			End If
		End If
		If InStr(kyojuu, "海外") > 0 Then
			If temp <> "" Then
				temp = "(" & temp & " OR MM_zip_kyojuu='0000000')"
			End If
		Else
			If temp = "" Then
				temp = "MM_zip_kyojuu<>'0000000'"
			End If
		End If

		'勤務地
		temp2 = ""
		If kinmu_shitei = "1" Then
			arry = Split(kinmu, ",")
			For Each item In arry
				n = InStr(item, "/")
				If n > 0 Then
					OrJoin temp2, "ZC_todoufuken='" & Left(item, n - 1) & "'"
					AndJoin temp2, "ZC_shikuchouson LIKE '" & Mid(item, n + 1) & "%'"
				Else
					If item <> "海外" Then
						OrJoin temp2, "ZC_todoufuken='" & item & "'"
					End If
				End If
			Next
			If temp2 <> "" Then
				temp2 = "EXISTS (SELECT * FROM T_ZIPCODE WHERE ZC_zip_uniq=MM_zip_kinmu AND (" & temp2 & "))"
			End If
		End If
		If InStr(kinmu, "海外") > 0 Then
			If temp2 <> "" Then
				temp2 = "(" & temp2 & " OR MM_zip_kyojuu='0000000')"
			End If
		Else
			If temp2 = "" Then
				temp2 = "(MM_zip_kinmu<>'0000000' OR MM_zip_kinmu IS NULL)"
			End If
		End If

		'居住地／勤務地のAND/OR
		If temp <> "" Or temp2 <> "" Then
			If kyojuu_shitei <> "1" Or kinmu_shitei <> "1" Or kinmu_andor = "A" Then
				AndJoin temp, temp2
			ElseIf kinmu_andor = "O" Then
				If temp <> "" And temp2 <> "" Then
					temp = "SELECT MM_member_id FROM T_MEMBER WHERE " & temp
					temp2 = "SELECT MM_member_id FROM T_MEMBER WHERE " & temp2
					temp = "MM_member_id IN (" & temp & " UNION " & temp2 & ")"
				Else
					OrJoin temp, temp2
				End If
			End If
			If temp <> "" Then
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		'興味ジャンル
		If kyoumi_genre_shitei = "1" Then
			temp = ""
			If kyoumi_genre_andor = "A" Then
				and_or = "AND"
			Else
				and_or = "OR"
			End If

			n = Len(kyoumi_genre1)
			If Len(kyoumi_genre2) > n Then
				n = Len(kyoumi_genre2)
			End If
			For i = 1 To n
				If Mid(kyoumi_genre1, i, 1) = "1" Then
					If Mid(kyoumi_genre2, i, 1) = "1" Then
						ExpJoin temp, and_or, "SUBSTR(MM_kyoumi_genre," & i & ",1)<>'0'"
					Else
						ExpJoin temp, and_or, "SUBSTR(MM_kyoumi_genre," & i & ",1)='1'"
					End If
				Else
					If Mid(kyoumi_genre2, i, 1) = "1" Then
						ExpJoin temp, and_or, "SUBSTR(MM_kyoumi_genre," & i & ",1)='2'"
					End If
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		'よく行く店
		If yokuikumise_shitei = "1" Then
			temp = ""
			n = Len(yokuikumise)
			For i = 1 To n
				If Mid(yokuikumise, i, 1) = "1" Then
					OrJoin temp, "SUBSTR(MM_yokuiku_mise," & i & ",1)='1'"
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		'どっち派
		If docchiha_shitei = "1" Then
			temp = ""
			n = Len(docchiha)
			For i = 1 To n
				If Mid(docchiha, i, 1) <> " " Then
					AndJoin temp, "SUBSTR(MM_docchiha," & i & ",1)='" & Mid(docchiha, i, 1) & "'"
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		'SQL
		If sql_shitei = "1" Then
			If sql_text <> "" Then
				AndJoin sql, "(" & sql_text & ")"
			End If
		End If

		'郵便番号指定
		If zip_shitei = "1" Then
			temp = ""
			temp2 = ""
			arry = Split(Replace(zip, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) = 7 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & item & "'"
				ElseIf Len(item) > 0 And Len(item) < 7 Then
					OrJoin temp2, "MM_zip_kyojuu LIKE '" & item & "%'"
				End If
			Next
			If temp <> "" Then
				temp = "MM_zip_kyojuu IN (" & temp & ")"
			End If
			OrJoin temp, temp2
			If temp <> "" Then
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		'除外発信ジョブ
		If jogai_hasshin_shitei = "1" Then
			temp = ""
			arry = Split(Replace(jogai_hasshin, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & item
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "NOT EXISTS (SELECT * FROM T_JOB,T_SENDMAIL_RR WHERE SR_member_id=MM_member_id AND JB_job_id IN (" & temp & ") AND JB_mail_id=SR_mail_id)"
			End If
		End If

		'除外レスポンスジョブ
		If jogai_response_shitei = "1" Then
			temp = ""
			arry = Split(Replace(jogai_response, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & item
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "NOT EXISTS (SELECT * FROM T_JOB,T_RECV_MAIL WHERE RM_member_id=MM_member_id AND JB_job_id IN (" & temp & ") AND RM_enq_valid='1' AND JB_mail_id=RM_mail_id)"
			End If
		End If

		'対象発信ジョブ
		If taishou_hasshin_shitei = "1" Then
			temp = ""
			arry = Split(Replace(taishou_hasshin, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & item
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "EXISTS (SELECT * FROM T_JOB,T_SENDMAIL_RR WHERE SR_member_id=MM_member_id AND JB_job_id IN (" & temp & ") AND JB_mail_id=SR_mail_id)"
			End If
		End If

		'対象レスポンスジョブ
		If taishou_response_shitei = "1" Then
			temp = ""
			arry = Split(Replace(taishou_response, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & item
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "EXISTS (SELECT * FROM T_JOB,T_RECV_MAIL WHERE RM_member_id=MM_member_id AND JB_job_id IN (" & temp & ") AND RM_enq_valid='1' AND JB_mail_id=RM_mail_id)"
			End If
		End If

		'HTMLメール受信
		If html_mail_shitei = "1" Then
			AndJoin sql, "MM_html_mail='" & html_mail & "'"
		End If

		'お知らせメール受信
		If oshirase_mail_shitei = "1" Then
			AndJoin sql, "MM_oshirase_mail='" & oshirase_mail & "'"
		End If

		'メンバーリスト
		If member_list_shitei = "1" Then
			temp = ""
			temp2 = ""
			n = 0
			arry = Split(Replace(member_list, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & Right(String(9, "0") & item, 10) & "'"

					n = n + 1
					If n Mod 1000 = 0Then
						OrJoin temp2, "MM_member_id IN (" & temp & ")"
						temp = ""
					End If
				End If
			Next
			If temp <> "" Then
				OrJoin temp2, "MM_member_id IN (" & temp & ")"
			End If
			If n > 1000 Then
				temp2 = "(" & temp2 & ")"
			End If
			If temp2 <> "" Then
				AndJoin sql, temp2
			End If
		End If

		'除外媒体
		If jogai_baitai <> "" Then
			temp = ""
			arry = Split(Replace(jogai_baitai, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & MakeID(item, 2) & "'"
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "NOT EXISTS (SELECT * FROM T_PR WHERE PR_pr_id=MM_pr_id AND PR_baitai_id IN (" & temp & "))"
			End If
		End If

		'対象媒体
		If taishou_baitai <> "" Then
			temp = ""
			arry = Split(Replace(taishou_baitai, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & MakeID(item, 2) & "'"
				End If
			Next
			If temp <> "" Then
				AndJoin sql, "EXISTS (SELECT * FROM T_PR WHERE PR_pr_id=MM_pr_id AND PR_baitai_id IN (" & temp & "))"
			End If
		End If

		'除外ドメイン
		If jogai_domain <> "" Then
			temp = ""
			arry = Split(Replace(jogai_domain, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & MakeID(item, 2) & "'"
				End If
			Next
			If temp <> "" Then
				temp2 = "SELECT LOWER(MD_name) FROM T_MAIL_DOMAIN WHERE MD_domain_id IN (" & temp & ")"
				Set ds = CreateDynaset(temp2, ORADYN_READONLY)
				temp = ""
				Do Until ds.EOF
					OrJoin temp, "LOWER(NVL(SUBSTR(MM_mail_adr," & -Len(ds(0).Value) & "),' '))='" & ds(0).Value & "'"
					ds.MoveNext
				Loop
				AndJoin sql, "NOT (" & temp & ")"
			End If
		End If

		'対象ドメイン
		If taishou_domain <> "" Then
			temp = ""
			arry = Split(Replace(taishou_domain, vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & "'" & MakeID(item, 2) & "'"
				End If
			Next
			If temp <> "" Then
				temp2 = "SELECT LOWER(MD_name) FROM T_MAIL_DOMAIN WHERE MD_domain_id IN (" & temp & ")"
				Set ds = CreateDynaset(temp2, ORADYN_READONLY)
				temp = ""
				Do Until ds.EOF
					OrJoin temp, "LOWER(NVL(SUBSTR(MM_mail_adr," & -Len(ds(0).Value) & "),' '))='" & ds(0).Value & "'"
					ds.MoveNext
				Loop
				AndJoin sql, "(" & temp & ")"
			End If
		End If

		If sql <> "" Then
			sql = "MM_status='0' AND (" & sql & ")"
		Else
			sql = "MM_status='0'"
		End If

		MakeSQL = sql

	End Function

	'=== 検索用SQLのWHERE句生成（受信拒否企業と定型処理有り） ===
	Public Function MakeSQL2(job_id, teikei_qno, teikei_andor, teikei_ans, ct_id)
		Dim sql, ds, mail_id, temp, n, i

		MakeSQL2 = ""

		sql = "SELECT JB_job_type FROM T_JOB WHERE JB_job_id=" & job_id
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			Select Case ds(0).Value
			Case "1"
				sql = "SELECT J2.JB_mail_id FROM T_JOB J1,T_JOB J2 WHERE J1.JB_job_id=" & job_id & " AND J1.JB_parent_job_id=J2.JB_job_id"
				Set ds = CreateDynaset(sql, ORADYN_READONLY)
				If Not ds.EOF Then
					mail_id = ds(0).Value

					sql = "SELECT * FROM T_RECV_MAIL WHERE RM_member_id=MM_member_id AND RM_mail_id='" & mail_id & "' AND RM_enq_valid='1'"
					teikei_qno = CLng("0" & teikei_qno)
					If teikei_qno > 0 Then
						temp = ""
						n = 0
						For i = 1 To 15
							If Mid(teikei_ans, i, 1) = "1" Then
								If temp <> "" Then
								temp = temp & ","
							End If
								temp = temp & i
								n = n + 1
							End If
						Next
						If n > 0 Then
							temp = "SELECT COUNT(*) FROM T_ANSWER_NO WHERE AS_seq_no=RM_seq_no AND AS_question_type='E' AND AS_question_no=" & teikei_qno & " AND AS_answer_no IN (" & temp & ")"
							If teikei_andor = "A" Then
								sql = sql & " AND (" & temp & ")=" & n
							Else
								sql = sql & " AND (" & temp & ")>0"
							End If
						End If
					End If
					AndJoin MakeSQL2, "EXISTS (" & sql & ")"
				End If
			Case "2"
				If Len(ct_id) = 0 Then
					ct_id = -1
				End If
				sql = "SELECT * FROM T_MAIL_CLICK WHERE MC_member_id=MM_member_id AND MC_ct_id IN (" & ct_id & ")"
				AndJoin MakeSQL2, "EXISTS (" & sql & ")"
			End Select
		End If

		AndJoin MakeSQL2, MakeSQL()

		sql = "SELECT TS_company_id" & vbCrLf & _
					"FROM T_JOB,T_PROJECT,T_TANTOUSHA" & vbCrLf & _
					"WHERE JB_job_id=" & job_id & " AND JB_project_id=PJ_project_id AND PJ_tantousha_id=TS_tantousha_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			AndJoin MakeSQL2, "NOT EXISTS (SELECT SC_member_id FROM T_SEND_COMPANY WHERE SC_member_id=MM_member_id AND SC_company_id='" & ds(0).Value & "' AND SC_kyohi_flag='1')"
		End If
	End Function

	'=== 式の結合 ===
	Private Sub ExpJoin(exp1, and_or, exp2)
		If exp2 <> "" Then
			If exp1 <> "" Then
				exp1 = exp1 & " " & and_or & " "
			End If
			exp1 = exp1 & exp2
		End If
	End Sub

	'=== AND結合 ===
	Private Sub AndJoin(exp1, exp2)
		ExpJoin exp1, "AND", exp2
	End Sub

	'=== OR結合 ===
	Private Sub OrJoin(exp1, exp2)
		ExpJoin exp1, "OR", exp2
	End Sub

	'=== 検索条件表示 ===
	Public Function GetConditionText()
		Dim msg, temp, temp2, arry, item, n, i, sql, ds, count

		msg = ""

		'興味ジャンル
		If kyoumi_genre_shitei = "1" Then
			temp = ""
			n = Len(NVL(kyoumi_genre1, ""))
			If Len(NVL(kyoumi_genre2, "")) > n Then
				n = Len(NVL(kyoumi_genre2, ""))
			End If
			count = 0
			For i = 1 To n
				If Mid(kyoumi_genre1, i, 1) = "1" Or Mid(kyoumi_genre2, i, 1) = "1" Then
					If temp <> "" Then
						temp = temp & vbCrLf
					End If

					sql = "SELECT KG_item_text FROM T_KYOUMI_GENRE WHERE KG_index=" & i & " ORDER BY KG_item_no"
					Set ds = CreateDynaset(sql, ORADYN_READONLY)
					If Not ds.EOF Then
						temp = temp & "　「" & ds(0).Value & "」"
					Else
						temp = temp & i
					End If
					temp = temp & "－"

					If Mid(kyoumi_genre1, i, 1) = "1" Then
						If Mid(kyoumi_genre2, i, 1) = "1" Then
							temp = temp & "かなり，普通"
						Else
							temp = temp & "普通"
						End If
					Else
							temp = temp & "かなり"
					End If

					count = count + 1
				End If
			Next
			If temp <> "" Then
				If count > 1 Then
					If kyoumi_genre_andor = "A" Then
						temp2 = "AND"
					Else
						temp2 = "OR"
					End If
					msg = msg & "・興味ジャンル：（ジャンル間" & temp2 & "）" & vbCrLf & temp & vbCrLf
				Else
					msg = msg & "・興味ジャンル：" & vbCrLf & temp & vbCrLf
				End If
			End If
		End If

		'性別
		temp = ""
		If seibetsu_m = "1" Then
			If seibetsu_f = "1" Then
				temp = "男,女"
			Else
				temp = "男"
			End If
		Else
			If seibetsu_f = "1" Then
				temp = "女"
			End If
		End If
		If temp <> "" Then
			msg = msg & "・性別：" & temp & vbCrLf
		End If

		'年齢
		If nenrei_from <> "" Or nenrei_to <> "" Then
			temp = ""
			If nenrei_from <> "" Then
				temp = nenrei_from & "才"
			End If
			temp = temp & "～"
			If nenrei_to <> "" Then
				temp = temp & nenrei_to & "才"
			End If
			msg = msg & "・年齢：" & temp & vbCrLf
		End If

		'未既婚
		temp = ""
		If mikikon_mi = "1" Then
			If mikikon_ki = "1" Then
				temp = "独身,既婚"
			Else
				temp = "独身"
			End If
		Else
			If mikikon_ki = "1" Then
				temp = "既婚"
			End If
		End If
		If temp <> "" Then
			msg = msg & "・未既婚：" & temp & vbCrLf
		End If

		'職業
		temp = ""
		sql = ""
		arry = Split(NVL(shokugyou, ""), ",")
		For Each item In arry
			If sql <> "" Then
				sql = sql & ","
			End If
			sql = sql & "'" & item & "'"
		Next
		If sql <> "" Then
			sql = "SELECT SG_shokugyou_name FROM T_SHOKUGYOU WHERE SG_shokugyou_code IN (" & sql & ") ORDER BY SG_seq_no"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			Do Until ds.EOF
				If temp <> "" Then
					temp = temp & vbCrLf
				End If
				temp = temp & "　「" & ds(0).Value & "」"
				ds.MoveNext
			Loop
			msg = msg & "・職業：" & vbCrLf & temp & vbCrLf
		End If

		'居住地
		If kyojuu_shitei = "1" Then
			msg = msg & "・居住地：" & Replace(kyojuu, "/" , "") & vbCrLf
		End If

		'勤務地
		If kinmu_shitei = "1" Then
			msg = msg & "・勤務地：" & Replace(kinmu, "/" , "") & vbCrLf
		End If

		'居住地／勤務地のAND/OR
		If kyojuu_shitei = "1" And kinmu_shitei = "1" Then
			temp = ""
			If kinmu_andor = "A" Then
				temp = "AND"
			ElseIf kinmu_andor = "O" Then
				temp = "OR"
			End If
			msg = msg & "・居住地／勤務地間：" & temp & vbCrLf
		End If

		'よく行く店
		If yokuikumise_shitei = "1" Then
			temp = ""
			n = Len(yokuikumise)
			For i = 1 To n
				If Mid(yokuikumise, i, 1) = "1" Then
					If temp <> "" Then
						temp = temp & vbCrLf
					End If

					sql = "SELECT YM_item_text FROM T_YOKUIKU_MISE WHERE YM_index=" & i & " ORDER BY YM_item_no"
					Set ds = CreateDynaset(sql, ORADYN_READONLY)
					If Not ds.EOF Then
						temp = temp & "　「" & ds(0).Value & "」"
					End If
				End If
			Next
			If temp <> "" Then
				msg = msg & "・よく行く店：" & vbCrLf & temp & vbCrLf
			End If
		End If

		'どっち派
		If docchiha_shitei = "1" Then
			temp = ""
			n = Len(docchiha)
			For i = 1 To n
				If Mid(docchiha, i, 1) <> " " Then
					If temp <> "" Then
						temp = temp & vbCrLf
					End If

					sql = "SELECT DH_item1_text,DH_item2_text FROM T_DOCCHI_HA WHERE DH_index=" & i & " ORDER BY DH_item_no"
					Set ds = CreateDynaset(sql, ORADYN_READONLY)
					If Not ds.EOF Then
						Select Case Mid(docchiha, i, 1)
						Case "0"
							temp = temp & "　「どちらでもない」"
						Case "1"
							temp = temp & "　「" & ds(0).Value & "」"
						Case "2"
							temp = temp & "　「" & ds(1).Value & "」"
						End Select
					End If
				End If
			Next
			If temp <> "" Then
				msg = msg & "・どっち派：" & vbCrLf & temp & vbCrLf
			End If
		End If

		'SQL
		If sql_shitei = "1" Then
			If sql_text <> "" Then
				msg = msg & "・ＳＱＬ：" & sql_text & vbCrLf
			End If
		End If

		'郵便番号指定
		If zip_shitei = "1" Then
			If zip <> "" Then
				arry = Split(Replace(zip, vbCrLf, ","), ",")
				msg = msg & "・郵便番号指定：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'除外発信ジョブ
		If jogai_hasshin_shitei = "1" Then
			If jogai_hasshin <> "" Then
				arry = Split(Replace(jogai_hasshin, vbCrLf, ","), ",")
				msg = msg & "・除外発信ジョブ：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'除外レスポンスジョブ
		If jogai_response_shitei = "1" Then
			If jogai_response <> "" Then
				arry = Split(Replace(jogai_response, vbCrLf, ","), ",")
				msg = msg & "・除外レスポンスジョブ：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'対象発信ジョブ
		If taishou_hasshin_shitei = "1" Then
			If taishou_hasshin <> "" Then
				arry = Split(Replace(taishou_hasshin, vbCrLf, ","), ",")
				msg = msg & "・対象発信ジョブ：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'対象レスポンスジョブ
		If taishou_response_shitei = "1" Then
			If taishou_response <> "" Then
				arry = Split(Replace(taishou_response, vbCrLf, ","), ",")
				msg = msg & "・対象レスポンスジョブ：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'メンバーリスト
		If member_list_shitei = "1" Then
			If member_list <> "" Then
				arry = Split(Replace(member_list, vbCrLf, ","), ",")
				msg = msg & "・メンバーリスト：" & Join(arry, ",<wbr>") & vbCrLf
			End If
		End If

		'HTMLメール受信
		If html_mail_shitei = "1" Then
			If html_mail = "1" Then
				temp = "受信可能"
			Else
				temp = "受信不可"
			End If
			msg = msg & "・HTMLメール受信可否：" & temp & vbCrLf
		End If

		'お知らせメール受信
		If oshirase_mail_shitei = "1" Then
			If oshirase_mail = "1" Then
				temp = "はい"
			Else
				temp = "いいえ"
			End If
			msg = msg & "・ﾊﾞｷｭｰﾝ・ﾊﾟｰﾐｼｮﾝ：" & temp & vbCrLf
		End If

		'除外媒体
		If jogai_baitai <> "" Then
			msg = msg & "・除外発信媒体：" & BaitaiName(jogai_baitai) & vbCrLf
		End If

		'対象媒体
		If taishou_baitai <> "" Then
			msg = msg & "・対象発信媒体：" & BaitaiName(taishou_baitai) & vbCrLf
		End If

		'除外ドメイン
		If jogai_domain <> "" Then
			msg = msg & "・除外発信ドメイン：" & DomainName(jogai_domain) & vbCrLf
		End If

		'対象ドメイン
		If taishou_domain <> "" Then
			msg = msg & "・対象発信ドメイン：" & DomainName(taishou_domain) & vbCrLf
		End If

		If msg = "" Then
			msg = "指定なし"
		End If

		GetConditionText = msg

	End Function

	'=== 媒体名出力 ===
	Private Function BaitaiName(baitai_id)
		Dim arry, item, sql, ds, temp

		arry = Split(baitai_id, ",")
		For Each item In arry
			If item <> "" Then
				If sql <> "" Then
					sql = sql & ","
				End If
				sql = sql & "'" & MakeID(item, 2) & "'"
			End If
		Next
		If sql <> "" Then
			sql = "SELECT BT_name FROM T_BAITAI WHERE BT_baitai_id IN (" & sql & ") ORDER BY BT_order"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			Do Until ds.EOF
				If temp <> "" Then
					temp = temp & ", "
				End If
				temp = temp & ds(0).Value
				ds.MoveNext
			Loop
		End If
		BaitaiName = temp
	End Function

	'=== ドメイン名出力 ===
	Private Function DomainName(domain_id)
		Dim arry, item, sql, ds, temp

		arry = Split(Replace(domain_id, vbCrLf, ","), ",")
		For Each item In arry
			If item <> "" Then
				If sql <> "" Then
					sql = sql & ","
				End If
				sql = sql & "'" & MakeID(item, 2) & "'"
			End If
		Next
		If sql <> "" Then
			sql = "SELECT MD_name FROM T_MAIL_DOMAIN WHERE MD_domain_id IN (" & sql & ") ORDER BY MD_order"
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			Do Until ds.EOF
				If temp <> "" Then
					temp = temp & ", "
				End If
				temp = temp & ds(0).Value
				ds.MoveNext
			Loop
		End If
		DomainName = temp
	End Function

End Class
%>