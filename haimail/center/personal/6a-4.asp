<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'***************************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（個人ユーザーサポート:個人情報詳細）
'***************************************************************

'=== 会員情報取得 ===
Private Sub ReadMember(member_id)

	Dim sql, ds, sub1, sub2, sub3, sub4

	sub1 = "SELECT SUM(PT_total_point) FROM T_POINT_TOTAL WHERE PT_member_id=MM_member_id"
	sub2 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='10' AND PT_member_id=MM_member_id"
	sub3 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='11' AND PT_member_id=MM_member_id"
	sub4 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='12' AND PT_member_id=MM_member_id"

	sql = "SELECT MM_member_id,MM_password,MM_regist_date,MM_update_date,MM_update_cnt,MM_seibetsu,MM_mikikon,MM_mail_adr," & vbCrLf & _
				" MM_seinengappi,MM_zip_kyojuu,MM_zip_kinmu,MM_html_mail,MM_oshirase_mail,MM_regist_type,MM_status," & vbCrLf & _
				" MM_kyoumi_genre, MM_yokuiku_mise, MM_docchiha," & vbCrLf & _
				" TK_taikai_riyuu,TK_taikai_date," & vbCrLf & _
				" Z1.ZC_todoufuken ken_kyojuu,Z2.ZC_todoufuken ken_kinmu,SG_shokugyou_name," & vbCrLf & _
				"(" & sub1 & ") PT_count_1,(" & sub2 & ") PT_count_2,(" & sub3 & ") PT_count_3,(" & sub4 & ") PT_count_4" & vbCrLf & _
				"FROM T_MEMBER,T_TAIKAI,T_SHOKUGYOU,T_ZIPCODE Z1,T_ZIPCODE Z2" & vbCrLf & _
				"WHERE MM_member_id='" & member_id & "' AND MM_member_id=TK_member_id(+)" & vbCrLf & _
				" AND MM_zip_kyojuu=Z1.ZC_zip_uniq(+) AND MM_zip_kinmu=Z2.ZC_zip_uniq(+) AND MM_shokugyou=SG_shokugyou_code(+)" & vbCrLf & _
				"ORDER BY MM_member_id DESC"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		member_id = ds("MM_member_id").Value
		password = ds("MM_password").Value
		regist_date = DateFormat2(ds("MM_regist_date").Value)
		update_date = DateFormat2(ds("MM_update_date").Value)
		update_cnt = NumFormat(ds("MM_update_cnt").Value)
		total_point = NumFormat(ds("PT_count_1").Value)
		send_point = NumFormat(ds("PT_count_2").Value)
		ankeart_point = NumFormat(ds("PT_count_3").Value)
		kansou_point = NumFormat(ds("PT_count_4").Value)
		seibetsu = SeibetsuText(ds("MM_seibetsu").Value) & "性"
		mikikon = MikikonText(ds("MM_mikikon").Value)
		mail_adr = ds("MM_mail_adr").Value
		seinengappi = DateFormat2(ds("MM_seinengappi").Value)
		age = GetAge(seinengappi)
		ken_kyojuu = ds("ken_kyojuu").Value
		zip_kyojuu = ds("MM_zip_kyojuu").Value
		ken_kinmu = ds("ken_kinmu").Value
		zip_kinmu = ds("MM_zip_kinmu").Value
		shokugyou_name = ds("SG_shokugyou_name").Value
		If ds("MM_html_mail").Value = "0" Then html_mail = "　" Else html_mail = "○"
		If ds("MM_oshirase_mail").Value = "0" Then oshirase_mail = "　" Else oshirase_mail = "○"
		If ds("MM_regist_type").Value = "0" Then regist_type = "いますぐ登録" Else regist_type = "ゆっくり登録"
		If ds("MM_status").Value = "9" Then
			taikai_date = DateFormat2(ds("TK_taikai_date").Value)
			taikai_riyuu = ds("TK_taikai_riyuu").Value
		Else
			taikai_date = "-"
			taikai_riyuu = ""
		End If
		kyoumi_genre = ds("MM_kyoumi_genre").Value
		yokuiku_mise = ds("MM_yokuiku_mise").Value
		docchiha = ds("MM_docchiha").Value
	Else
		err_flg = 8
	End If
End Sub

'=== ■個人情報詳細表示 ===
Private Sub SetKojinDetail()
	Dim w_keipoint, q_keipoint

	w_keipoint = Cdbl(send_point) + Cdbl(ankeart_point) + Cdbl(kansou_point)
	q_keipoint = NumFormat(w_keipoint)

	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"
	Writeln "<tr><td class='m0'>■個人情報詳細</td></tr>"
	Writeln "</table>"
	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='90%'>"
	WriteLn "<tr><td>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>はいめーるＩＤ</td>"
	Writeln "<td class='n3' width='36%'>" & member_id & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>ＰＷ</td>"
	Writeln "<td class='n3' width='36%'>" & password & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>メールアドレス</td>"
	Writeln "<td class='n3' width='36%'>" & mail_adr & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>登録日</td>"
	Writeln "<td class='n3' width='36%'>" & regist_date & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>更新日</td>"
	Writeln "<td class='n3' width='36%'>" & update_date & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>更新回数</td>"
	Writeln "<td class='n3' width='36%'>" & update_cnt & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>性別</td>"
	Writeln "<td class='n3' width='36%'>" & seibetsu & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>未既婚</td>"
	Writeln "<td class='n3' width='36%'>" & mikikon & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>年齢</td>"
	Writeln "<td class='n3' width='36%'>" & age & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>生年月日</td>"
	Writeln "<td class='n3' width='36%'>" & seinengappi & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>居住地都道府県</td>"
	Writeln "<td class='n3' width='36%'>" & ken_kyojuu & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>〒</td>"
	Writeln "<td class='n3' width='36%'>" & zip_kyojuu & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>勤務地都道府県</td>"
	Writeln "<td class='n3' width='36%'>" & ken_kinmu & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>〒</td>"
	Writeln "<td class='n3' width='36%'>" & zip_kinmu & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>職業</td>"
	Writeln "<td class='n3' width='36%'>" & shokugyou_name & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>入会区分</td>"
	Writeln "<td class='n3' width='36%'>" & regist_type & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>ＨＴＭＬ</td>"
	Writeln "<td class='n3' width='36%'>" & html_mail & "</td>"
	Writeln "<td width='46%'>※許可○　拒否Ｘ</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>バキューン</td>"
	Writeln "<td class='n3' width='36%'>" & oshirase_mail & "</td>"
	Writeln "<td width='46%'>※許可○　拒否Ｘ</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>総合計ポイント</td>"
	Writeln "<td class='n3' width='36%'>" & total_point & "ﾎﾟｲﾝﾄ</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%' rowspan=2 valign='top'>はいめーる・Ｎｅｔ</td>"
	Writeln "<td class='n3' width='18%'>送信</td>"
	Writeln "<td class='n3' width='18%'>ｱﾝｹｰﾄ</td>"
	Writeln "<td class='n3' width='18%'>感想文</td>"
	Writeln "<td class='n3' width='18%'>合計</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "<tr><td class='n3' width='18%' align='right'>" & send_point & "</td>"
	Writeln "<td class='n3' width='18%' align='right'>" & ankeart_point & "</td>"
	Writeln "<td class='n3' width='18%' align='right'>" & kansou_point & "</td>"
	Writeln "<td class='n3' width='18%' align='right'>" & q_keipoint & "</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>退会日時</td>"
	Writeln "<td class='n3' width='36%'>" & taikai_date & "</td>"
	Writeln "<td width='46%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='18%'>退会理由</td>"
	Writeln "<td class='n3' width='72%'>" & taikai_riyuu & "</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "</td></tr>"
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■興味ジャンル表示 ===
Private Sub SetKyoumigenre()
	Dim sql, ds, i
	Dim t_cnt, k_cnt, f_cnt
	Dim t_tbl(999), k_tbl(999), f_tbl(999)
	Dim t_data, k_data, f_data

	t_cnt = 0: k_cnt = 0: f_cnt = 0

	sql = "SELECT * FROM T_KYOUMI_GENRE"
	sql = sql & " WHERE KG_valid_flag='1' ORDER BY KG_category,KG_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		i = Cint(ds("KG_index").Value)

		Select Case Mid(kyoumi_genre,i,1)
		Case "1"
				t_cnt = t_cnt + 1
				t_tbl(t_cnt) = ds("KG_item_text").Value
				f_cnt = f_cnt + 1
				f_tbl(f_cnt) = ds("KG_item_text").Value
		Case "2"
				t_cnt = t_cnt + 1
				t_tbl(t_cnt) = ds("KG_item_text").Value
				k_cnt = k_cnt + 1
				k_tbl(k_cnt) = ds("KG_item_text").Value
		End Select

		ds.MoveNext
	Loop
	ds.Close

	If t_cnt <> 0 Then
		t_data = ""
		For i = 1 To t_cnt: t_data = t_data & t_tbl(i) & "<br>": Next
	Else
		t_data = "　"
	End If
	If k_cnt <> 0 Then
		k_data = ""
		For i = 1 To k_cnt: k_data = k_data & k_tbl(i) & "<br>": Next
	Else
		k_data = "　"
	End If
	If f_cnt <> 0 Then
		f_data = ""
		For i = 1 To f_cnt: f_data = f_data & f_tbl(i) & "<br>": Next
	Else
		f_data = "　"
	End If

	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"
	Writeln "<tr><td class='m0'>■興味ジャンル</td></tr>"
	Writeln "</table>"
	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='90%'>"
	WriteLn "<tr><td>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='90%'>ある:合計" & NumFormat(t_cnt) & "個</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "<tr><td class='n3' width='90%'>" & t_data & "</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"

	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='45%'>かなり:合計" & NumFormat(k_cnt) & "個</td>"
	Writeln "<td class='m3' width='45%'>ふつうに:合計" & NumFormat(f_cnt) & "個</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "<tr><td class='n3' width='45%' valign='top'>" & k_data & "</td>"
	Writeln "<td class='n3' width='45%' valign='top'>" & f_data & "</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "</td></tr>"
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■よく行くお店表示 ===
Private Sub SetYokuikumise()
	Dim sql, ds, i
	Dim s_cnt, c_cnt, g_cnt, m_cnt
	Dim s_tbl(250), c_tbl(250), g_tbl(250), m_tbl(250)
	Dim s_data, c_data, g_data, m_data

	s_cnt = 0: c_cnt = 0: g_cnt = 0: m_cnt = 0

	sql = "SELECT * FROM T_YOKUIKU_MISE"
	sql = sql & " WHERE YM_valid_flag='1' ORDER BY YM_category,YM_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		i = Cint(ds("YM_index").Value)

		Select Case ds("YM_category").Value
		Case "01"
			If Mid(yokuiku_mise,i,1) = "1" Then
				c_cnt = c_cnt + 1
				c_tbl(c_cnt) = ds("YM_item_text").Value
			End If
		Case "02"
			If Mid(yokuiku_mise,i,1) = "1" Then
				s_cnt = s_cnt + 1
				s_tbl(s_cnt) = ds("YM_item_text").Value
			End If
		Case "03"
			If Mid(yokuiku_mise,i,1) = "1" Then
				g_cnt = g_cnt + 1
				g_tbl(g_cnt) = ds("YM_item_text").Value
			End If
		Case "04"
			If Mid(yokuiku_mise,i,1) = "1" Then
				m_cnt = m_cnt + 1
				m_tbl(m_cnt) = ds("YM_item_text").Value
			End If
		End Select

		ds.MoveNext
	Loop
	ds.Close

	If s_cnt <> 0 Then
		s_data = ""
		For i = 1 To s_cnt: s_data = s_data & s_tbl(i) & "<br>": Next
	Else
		s_data = "　"
	End If
	If c_cnt <> 0 Then
		c_data = ""
		For i = 1 To c_cnt: c_data = c_data & c_tbl(i) & "<br>": Next
	Else
		c_data = "　"
	End If
	If g_cnt <> 0 Then
		g_data = ""
		For i = 1 To g_cnt: g_data = g_data & g_tbl(i) & "<br>": Next
	Else
		g_data = "　"
	End If
	If m_cnt <> 0 Then
		m_data = ""
		For i = 1 To m_cnt: m_data = m_data & m_tbl(i) & "<br>": Next
	Else
		m_data = "　"
	End If

	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"
	Writeln "<tr><td class='m0'>■よく行くお店（コンビニ・スーパー）</td></tr>"
	Writeln "</table>"
	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='90%'>"
	WriteLn "<tr><td>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='45%'>スーパー:合計" & NumFormat(s_cnt) & "個</td>"
	Writeln "<td class='m3' width='45%'>コンビニ:合計" & NumFormat(c_cnt) & "個</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "<tr><td class='n3' width='45%' valign='top'>" & s_data & "</td>"
	Writeln "<td class='n3' width='45%' valign='top'>" & c_data & "</td>"
	Writeln "<td width='10%' valign='top'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "</td></tr>"
	Writeln "</table>"
	Writeln "<br clear>"

	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"
	Writeln "<tr><td class='m0'>■よく行くお店（その他）</td></tr>"
	Writeln "</table>"
	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='90%'>"
	WriteLn "<tr><td>"
	Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
	Writeln "<tr><td class='m3' width='45%'>業態・サービス:合計" & NumFormat(g_cnt) & "個</td>"
	Writeln "<td class='m3' width='45%'>お店・サービス:合計" & NumFormat(m_cnt) & "個</td>"
	Writeln "<td width='10%'>　</td></tr>"
	Writeln "<tr><td class='n3' width='45%' valign='top'>" & g_data & "</td>"
	Writeln "<td class='n3' width='45%' valign='top'>" & m_data & "</td>"
	Writeln "<td width='10%' valign='top'>　</td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "</td></tr>"
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== ■どっち派表示 ===
Private Sub SetDocchiha()
	Dim sql, ds, i, vs_name, vs_docchi

	sql = "SELECT * FROM T_DOCCHI_HA"
	sql = sql & " WHERE DH_valid_flag='1' ORDER BY DH_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"
	Writeln "<tr><td class='m0'>■どっち派？</td></tr>"
	Writeln "</table>"
	Writeln "<table border=0 cellpadding=0 cellspacing=0 width='90%'>"
	WriteLn "<tr><td>"

	Do Until ds.EOF
		vs_name = ds("DH_item1_text").Value & "VS" & ds("DH_item1_text").Value
		i = Cint(ds("DH_index").Value)
		Select Case Mid(docchiha,i,1)
		Case "1": vs_docchi = ds("DH_item1_text").Value
		Case "2": vs_docchi = ds("DH_item2_text").Value
		Case Else: vs_docchi = "どちらでもない"
		End Select

		Writeln "<table align='left' border=0 cellpadding=3 cellspacing=1 width='100%'>"
		Writeln "<tr><td class='m3' width='60%'>" & vs_name & "</td>"
		Writeln "<td class='n3' width='30%'>" & vs_docchi & "</td>"
		Writeln "<td width='10%'>　</td></tr>"
		Writeln "</table>"
		Writeln "<br clear='left'>"

		ds.MoveNext
	Loop
	ds.Close

	Writeln "</td></tr>"
	Writeln "</table>"
	Writeln "<br clear>"
End Sub

'=== メイン処理 ===
Dim rtn_url
Dim s_time, e_time
Dim member_id, password, regist_date, update_date, update_cnt, total_point, send_point, ankeart_point, kansou_point, seibetsu
Dim mikikon, mail_adr, seinengappi, age, ken_kyojuu, zip_kyojuu, ken_kinmu, zip_kinmu, shokugyou_name, html_mail, oshirase_mail
Dim regist_type, taikai_date, taikai_riyuu, kyoumi_genre, yokuiku_mise, docchiha

member_id = Request.QueryString("id")
If IsEmpty(member_id) Then
	SystemError "member_id is empty"
End If

'会員情報取得
ReadMember member_id

'タイトル
SetHeaderTitle "メンバー個人検索（個人情報詳細）"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form>
<%
SetKojinDetail
SetKyoumigenre
SetYokuikumise
SetDocchiha
%>
<input type="button" name="btn_end" value=" 戻る " onClick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
