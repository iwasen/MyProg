<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー個人検索（個人ユーザーサポート）
'******************************************************

'=== 会員情報取得 ===
Private Sub ReadMember(member_id, mail_adr)
	Dim sql, ds, sub1, sub2, sub3, sub4, cond

	If member_id <> "" Then
		cond = " MM_member_id = '" & Right(String(10, "0") & Trim(member_id), 10) & "'"
	Else
		cond = " MM_mail_adr = '" & mail_adr & "'"
	End If

	sub1 = "SELECT SUM(PT_total_point) FROM T_POINT_TOTAL WHERE PT_member_id=MM_member_id"
	sub2 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='10' AND PT_member_id=MM_member_id"
	sub3 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='11' AND PT_member_id=MM_member_id"
	sub4 = "SELECT PT_total_point FROM T_POINT_TOTAL WHERE PT_point_type='12' AND PT_member_id=MM_member_id"

	sql = "SELECT MM_member_id,MM_password,MM_regist_date,MM_update_date,MM_update_cnt,MM_seibetsu,MM_mikikon,MM_mail_adr," & vbCrLf & _
				" MM_seinengappi,MM_html_mail,MM_oshirase_mail,MM_regist_type,MM_status,TK_taikai_date," & vbCrLf & _
				"(" & sub1 & ") PT_count_1,(" & sub2 & ") PT_count_2,(" & sub3 & ") PT_count_3,(" & sub4 & ") PT_count_4" & vbCrLf & _
				"FROM T_MEMBER,T_TAIKAI" & vbCrLf & _
				"WHERE" & cond & " AND MM_member_id=TK_member_id(+)" & vbCrLf & _
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
		If ds("MM_html_mail").Value = "0" Then html_mail = "　" Else html_mail = "○"
		If ds("MM_oshirase_mail").Value = "0" Then oshirase_mail = "　" Else oshirase_mail = "○"
		If ds("MM_regist_type").Value = "0" Then regist_type = "いますぐ登録" Else regist_type = "ゆっくり登録"
		taikai_date = DateFormat2(ds("TK_taikai_date").Value)
		status = StatusText(ds("MM_status").Value)
	Else
		err_flg = 8
	End If
End Sub

'=== 状態コード ===
Private Function StatusText(status)
	Select Case status
	Case "0"
		StatusText = "有効"
	Case "1"
		StatusText = "一時停止"
	Case "9"
		StatusText = "退会"
	Case Else
		StatusText = "-"
	End Select
End Function

'=== ■はいめーるＩＤ／メールアドレスエラー ===
Private Sub SetErrorMessage()
	Dim rtn_url

	rtn_url = "location.href=""6-2.asp?f1=" & member_id & "&f2=" & mail_adr & """"
		
	Writeln "<table border=0 cellspacing=0 cellpadding=1 width='95%'>"
	Writeln "<tr><td><font color='#FF0000'>"
	If err_flg = 9 Then
		Writeln "「はいめーるＩＤ」、「メールアドレス」が入力されていません。<br>"
		Writeln "どちらかは必ず入力してください。<br>"
	ElseIf member_id <> "" Then
		Writeln "入力された「はいめーるＩＤ」は会員マスタにありません。<br>"
		Writeln "正しい「はいめーるＩＤ」を入力してください。<br>"
	Else
		Writeln "入力された「メールアドレス」は会員マスタにありません。<br>"
		Writeln "正しい「メールアドレス」を入力してください。<br>"
	End If
	Writeln "<br></font></td></tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<br>"
	Writeln "<input type='button' name='btn_end' value=' 戻る ' onClick='" & rtn_url & "'>"
End Sub

'=== ■個人情報表示 ===
Private Sub SetDataMessage()
	Dim rtn_url1, rtn_url2, rtn_url3, rtn_url4

	rtn_url1 = "location.href=""6a-4.asp?id=" & member_id & """"
	rtn_url2 = "location.href=""6b-4.asp?id=" & member_id & """"
	rtn_url3 = "location.href=""6e-5.asp?id=" & member_id & """"
	rtn_url4 = "location.href=""6f-5.asp?id=" & member_id & """"

	Writeln "<table border=1 cellspacing=1 cellpadding=0 width='100%' class='small'>"
	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>ID</th>"
	Writeln "<th>PW</th>"
	Writeln "<th>登録日</th>"
	Writeln "<th>最終更新日</th>"
	Writeln "<th>更新回数</th>"
	Writeln "<th>合計ﾎﾟｲﾝﾄ</th>"
	Writeln "<th>送信加算ﾎﾟｲﾝﾄ</th>"
	Writeln "<th>ｱﾝｹｰﾄﾎﾟｲﾝﾄ</th>"
	Writeln "<th>感想文ﾎﾟｲﾝﾄ</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#FFEEE0'>"
	Writeln "<td align='center'>" & member_id & "</td>"
	Writeln "<td>" & password & "</td>"
	Writeln "<td align='center'>" & regist_date & "</td>"
	Writeln "<td align='center'>" & update_date & "</td>"
	Writeln "<td align='right'>" & update_cnt & "</td>"
	Writeln "<td align='right'>" & total_point & "</td>"
	Writeln "<td align='right'>" & send_point & "</td>"
	Writeln "<td align='right'>" & ankeart_point & "</td>"
	Writeln "<td align='right'>" & kansou_point & "</td>"
	Writeln "</tr>"

	Writeln "<tr bgcolor='#E0FFE0'>"
	Writeln "<th>性別</th>"
	Writeln "<th>未既婚</th>"
	Writeln "<th>ﾒｰﾙｱﾄﾞﾚｽ</th>"
	Writeln "<th>生年月日</th>"
	Writeln "<th>HTML受信設定</th>"
	Writeln "<th>ﾊﾞｷｭｰﾝ受信設定</th>"
	Writeln "<th>入会区分</th>"
	Writeln "<th>退会日時</th>"
	Writeln "<th>状態</th>"
	Writeln "</tr>"
	Writeln "<tr bgcolor='#FFEEE0'>"
	Writeln "<td align='center'>" & seibetsu & "</td>"
	Writeln "<td align='center'>" & mikikon & "</td>"
	Writeln "<td>" & mail_adr & "</td>"
	Writeln "<td align='center'>" & seinengappi & "</td>"
	Writeln "<td align='center'>" & html_mail & "</td>"
	Writeln "<td align='center'>" & oshirase_mail & "</td>"
	Writeln "<td align='center'>" & regist_type & "</td>"
	Writeln "<td align='center'>" & taikai_date & "</td>"
	Writeln "<td align='center'>" & status & "</td>"
	Writeln "</tr>"
	Writeln "</table>"
	Writeln "<br clear='left'>"
	Writeln "<br>"
	Writeln "<input type='button' value='個人情報詳細' onClick='" & rtn_url1 & "'>"
	Writeln "<input type='button' value='送受信履歴' onClick='" & rtn_url2 & "'>"
	Writeln "<input type='button' value='強制一時停止' onClick='" & rtn_url3 & "'>"
	Writeln "<input type='button' value='強制退会' onClick='" & rtn_url4 & "'>"
	Writeln "<input type='button' value=' 戻る ' onClick='location.href=""6-2.asp""'>"
	Writeln "<br>"
End Sub

'=== メイン処理 ===
Dim err_flg
Dim member_id, password, regist_date, update_date, update_cnt, total_point, send_point, ankeart_point, kansou_point, seibetsu
Dim mikikon, mail_adr, seinengappi, html_mail, oshirase_mail, regist_type, taikai_date, status

'リクエスト／クエリ文字列取得
member_id = Request("id")
mail_adr = Request("mail_adr")
If IsEMpty(member_id) And IsEMpty(mail_adr) Then
	Response.Redirect "6-2.asp"
End If

'タイトル設定
SetHeaderTitle "メンバー個人検索"

'会員情報取得
ReadMember member_id, mail_adr
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
	<br>
<%
If err_flg = False Then
	SetDataMessage
Else
	SetErrorMessage
End If
%>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
