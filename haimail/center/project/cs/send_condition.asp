<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:発信条件画面処理
'******************************************************

'=== 発信条件取得 ===
Private Sub ReadSendCondition(job_id)
	Dim sql, ds, temp

	sql = "SELECT * FROM T_JOB,T_SEND_MAIL WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		temp = ds("SM_start_date").Value
		If Not IsNull(temp) Then
			start_date_y = Year(temp)
			start_date_m = Month(temp)
			start_date_d = Day(temp)
			start_date_h = Hour(temp)
		End If
		temp = ds("SM_end_date").Value
		If Not IsNull(temp) Then
			end_date_y = Year(temp)
			end_date_m = Month(temp)
			end_date_d = Day(temp)
			end_date_h = Hour(temp)
		End If
		send_num = ds("SM_send_num").Value
		bunkatsu_kankaku = ds("SM_bunkatsu_kankaku").Value
		bunkatsu_kaisuu = ds("SM_bunkatsu_kaisuu").Value

		point_jushin = ds("SM_point_jushin").Value
		If IsNull(point_jushin) Then
			If ds("JB_get_jushin").Value = "1" Then
				point_jushin = 1
			Else
				point_jushin = 0
			End If
		End If

		point_kansou = ds("SM_point_kansou").Value
		If IsNull(point_kansou) Then
			If ds("JB_get_kansou").Value = "1" Then
				point_kansou = 1
			Else
				point_kansou = 0
			End If
		End If

		point_enquete = ds("SM_point_enquete").Value

		temp = ds("SM_limit_date").Value
		If Not IsNull(temp) Then
			limit_date_y = Year(temp)
			limit_date_m = Month(temp)
			limit_date_d = Day(temp)
			If Minute(temp) = 0 Then
				limit_date_h = Hour(temp)
			Else
				limit_date_h = -1
			End If
		End If

		temp = ds("SM_imp_limit").Value
		If Not IsNull(temp) Then
			imp_limit_y = Year(temp)
			imp_limit_m = Month(temp)
			imp_limit_d = Day(temp)
			If Minute(temp) = 0 Then
				imp_limit_h = Hour(temp)
			Else
				imp_limit_h = -1
			End If
		End If

		virgin_mail = NVL(ds("SM_virgin_mail").Value, "0")
		virgin_day = NVL(ds("SM_virgin_day").Value, 2)
		virgin_hour = NVL(ds("SM_virgin_hour").Value, 0)

		sm_status = NVL(ds("SM_status").Value, "0")

		get_kansou = ds("JB_get_kansou").Value
		send_timing = ds("JB_send_timing").Value
		send_timing_h = ds("JB_send_timing_h").Value
		send_timing_d = ds("JB_send_timing_d").Value
		teikei_qno = ds("JB_teikei_qno").Value
		teikei_andor = ds("JB_teikei_andor").Value
		teikei_ans = ds("JB_teikei_ans").Value
		parent_job_id = ds("JB_parent_job_id").Value
		status = ds("JB_status").Value
		job_type = ds("JB_job_type").Value
		ct_id = ds("JB_ct_id").Value
		mail_type = ds("JB_mail_type").Value
	End If

	If IsNull(ds("JB_search_id").Value) Then
		If parent_job_id <> "" Then
			sql = "SELECT JB_search_id FROM T_JOB WHERE JB_job_id=" & parent_job_id
			Set ds = CreateDynaset(sql, ORADYN_READONLY)
			If Not ds.EOF Then
				If Not IsNull(ds("JB_search_id").Value) Then
					search.ReadDB ds("JB_search_id").Value
				End If
			End If
		End If
	Else
		search.ReadDB ds("JB_search_id").Value
	End If
End Sub

'=== 興味ジャンル表示 ===
Private Sub KyoumiGenre()
	Dim sql, ds, half, count, index

	sql = "SELECT KG_index,KG_item_text FROM T_KYOUMI_GENRE WHERE KG_valid_flag='1' ORDER BY KG_index"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	half = (ds.RecordCount + 1) \ 2

	Writeln "<table border=0 cellspacing=0 cellpadding=0 width='100%'>"
	Writeln "<tr>"

	count = 0
	Do Until ds.EOF
		If count Mod half = 0 Then
			Writeln "<td width='50%' valign='top'>"
			Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"
			Writeln "<tr>"
			Writeln "<td class='m3' align='center'>興味ジャンル</td>"
			Writeln "<td class='m3' align='center'>かなり</td>"
			Writeln "<td class='m3' align='center'>普通</td>"
			Writeln "</tr>"
		End If

		index = ds("KG_index").Value
		If count Mod 2 = 0 Then
			Writeln "<tr bgcolor='#fff6ea'>"
		Else
			Writeln "<tr>"
		End If
		Writeln "<td>" & ds("KG_item_text").Value & "</td>"
		Writeln "<td align='center'><input type='checkbox' name='kyoumi_genre2' value='" & index & "'" & Checked(Mid(search.kyoumi_genre2, index, 1)) & "></td>"
		Writeln "<td align='center'><input type='checkbox' name='kyoumi_genre1' value='" & index & "' onclick='OnClick_kyoumi_genre(" & count & ")'" & Checked(Mid(search.kyoumi_genre1, index, 1)) & "></td>"
		Writeln "</tr>"

		count = count + 1
		If count Mod half = 0 Then
			Writeln "</table>"
			Writeln "</td>"
		End If

		ds.MoveNext
	Loop

	If count Mod half <> 0 Then
		Writeln "</table>"
		Writeln "</td>"
	End If

	Writeln "</tr>"
	Writeln "</table>"
End Sub

'=== 職業表示 ===
Private Sub Shokugyou()
	Dim sql, ds, code, check

	sql = "SELECT SG_shokugyou_code,SG_shokugyou_name FROM T_SHOKUGYOU ORDER BY SG_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		code = ds("SG_shokugyou_code").Value
		If InStr(search.shokugyou, code) > 0 Then
			check = " checked"
		Else
			check = ""
		End If
		Writeln "<nobr><input type='checkbox' name='shokugyou' value='" & code & "'" & check & ">" & ds("SG_shokugyou_name").Value & "</nobr>"
		ds.MoveNext
	Loop
End Sub

'=== 住所表示 ===
Private Sub Juusho(name, def)
	Dim sql, ds, defc, value, disp, kakko, flag, count, cols, check

	defc = def & ","

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

	sql = "SELECT CI_todoufuken,CI_shiku FROM T_CITY ORDER BY CI_seq_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	flag = False
	count = 0
	cols = 6
	Do Until ds.EOF
		kakko = ""
		If IsNull(ds("CI_shiku").Value) Then
			value = ds("CI_todoufuken").Value
			disp = ds("CI_todoufuken").Value
		Else
			value = ds("CI_todoufuken").Value & "/" & ds("CI_shiku").Value
			If flag = False Then
				kakko = "（"
				flag = True
			End If
			disp = "<font color='#008000'>" & ds("CI_shiku").Value & "</font>"
		End If

		ds.MoveNext

		If flag Then
			If ds.EOF Then
				disp = disp & "）"
			Else
				If IsNull(ds("CI_shiku").Value) Then
					disp = disp & "）"
					flag = False
				End If
			End If
		End If

		If count Mod cols = 0 Then
			Writeln "<tr>"
		End If

		If InStr(defc, value & ",") > 0 Then
			check = " checked"
		Else
			check = ""
		End If

		Writeln "<td width='10%'><nobr>" & kakko & "<input type='checkbox' name='" & name & "' value='" & value & "'" & check & ">" & disp & "</nobr></td>"

		count = count + 1
		If count Mod cols = 0 Then
			Writeln "</tr>"
		End If
	Loop

	If count Mod cols <> 0 Then
		Writeln "</tr>"
	End If

	Writeln "</table>"
End Sub

'=== 海外チェック ===
Private Function Kaigai(adr)
	If InStr(adr, "海外") > 0 Then
		Kaigai = "1"
	End If
End Function

'=== よく行く店表示 ===
Private Sub YokuikuMise()
	Dim sql, ds, category, category_name, count, cols, index

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

	sql = "SELECT YM_index,YM_category,YM_item_text FROM T_YOKUIKU_MISE WHERE YM_valid_flag='1' ORDER BY YM_category,YM_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)

	cols = 3
	category = ""
	Do Until ds.EOF
		If category <> ds("YM_category").Value Then
			category = ds("YM_category").Value
			Select Case category
			Case "01"
				category_name = "コンビニ"
			Case "02"
				category_name = "スーパー"
			Case "03"
				category_name = "よく利用する業態やサービス"
			Case "04"
				category_name = "よく利用するお店やサービス"
			End Select

			Writeln "<tr>"
			Writeln "<td class='m3' colspan=" & cols & ">" & category_name & "</td>"
			Writeln "</tr>"
			count = 0
		End If

		If count Mod cols = 0 Then
			Writeln "<tr>"
		End If

		index = ds("YM_index").Value
		Writeln "<td width='10%'><nobr><input type='checkbox' name='yokuikumise' value='" & index & "'" & Checked(Mid(search.yokuikumise, index, 1)) & ">" & ds("YM_item_text").Value & "</nobr></td>"

		count = count + 1
		If count Mod cols = 0 Then
			Writeln "</tr>"
		End If

		ds.MoveNext
	Loop

	If count Mod cols <> 0 Then
		Writeln "</tr>"
	End If

	Writeln "</table>"
End Sub

'=== どっち派表示 ===
Private Sub DocchiHa()
	Dim sql, ds, index, name, check0, check1, check2, all, count

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

	count = 0
	sql = "SELECT DH_index,DH_item1_text,DH_item2_text FROM T_DOCCHI_HA WHERE DH_valid_flag='1' ORDER BY DH_item_no"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		index = ds("DH_index").Value
		all = all & index & ","
		name = "docchiha" & index
		check0 = RadioChecked(Mid(search.docchiha, index, 1), "0")
		check1 = RadioChecked(Mid(search.docchiha, index, 1), "1")
		check2 = RadioChecked(Mid(search.docchiha, index, 1), "2")

		If count Mod 2 = 0 Then
			Writeln "<tr bgcolor='#fff6ea'>"
		Else
			Writeln "<tr>"
		End If
		Writeln "<td width='33%'><input type='radio' name='" & name & "' value='1'" & check1 & ">" & ds("DH_item1_text").value & "</td>"
		Writeln "<td width='33%'><input type='radio' name='" & name & "' value='2'" & check2 & ">" & ds("DH_item2_text").value & "</td>"
		Writeln "<td width='33%'><input type='radio' name='" & name & "' value='0'" & check0 & ">どちらでもない</td>"
		Writeln "</tr>"

		count = count + 1
		ds.MoveNext
	Loop

	Writeln "</table>"
	Writeln "<input type='hidden' name='docchiha_all' value='" & all & "'>"
End Sub

'=== 定型処理表示 ===
Private Sub TeikeiShori()
	Dim i

	For i = 1 To 15
		Writeln "<input type='checkbox' name='teikei_ans' value='" & i & "'" & Checked(Mid(teikei_ans, i, 1)) & ">" & i
	Next
End Sub

'=== バージンメール送信タイミング表示判定 ===
Private Function DispVirginSendTiming(virgin_mail)
	If virgin_mail = "0" Then
		DispVirginSendTiming = "0"
	Else
		DispVirginSendTiming = "1"
	End If
End Function

'=== クリックカウンタ表示 ===
Private Sub ClickCounter()
	Dim sql, ds, arry, chk, id1, id2

	arry = Split(NVL(ct_id, ""), ",")
	sql = "SELECT CC_ct_id,CC_url_name" & vbCrLf & _
				"FROM T_CLICK_COUNT" & vbCrLf & _
				"WHERE CC_job_id=" & parent_job_id & vbCrLf & _
				"ORDER BY CC_ct_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		chk = ""
		id1 = ds("CC_ct_id").Value
		For Each id2 In arry
			If id1 = id2 Then
				chk = " checked"
				Exit For
			End If
		Next

		Writeln "<input type='checkbox' name='ct_id' value='" & id1 & "'" & chk & ">" & id1 & " " & ReplaceTag(ds("CC_url_name").Value) & "<br>"
		ds.MoveNext
	Loop
End Sub

'=== 媒体リスト表示 ===
Private Sub BaitaiList(sel, name)
	Dim count, sql, ds, checked, cols, temp, arry, where, item

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

	cols = 2
	count = 0

	where = "BT_search_flag='1'"

	If system_id = "sales" Then
		sql = "SELECT DA_" & name & " FROM T_OPERATOR,T_DAIRITEN WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			temp = ""
			arry = Split(Replace(NVL(ds(0).Value, ""), vbCrLf, ","), ",")
			For Each item In arry
				If Len(item) > 0 Then
					If temp <> "" Then
						temp = temp & ","
					End If
					temp = temp & MakeID(item, 2)
				End If
			Next
			If temp = "" Then
				temp = "''"
			End If
			where = where & " AND BT_baitai_id IN (" & temp & ")"
		End If
	End If

	sql = "SELECT BT_baitai_id,BT_name FROM T_BAITAI WHERE " & where & " ORDER BY BT_order"

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If InStr(sel, ds("BT_baitai_id").Value) > 0 Then
			checked = " checked"
		Else
			checked = ""
		End If

		If count Mod cols = 0 Then
			Writeln "<tr>"
		End If

		Writeln "<td width='50%'><input type='checkbox' name='" & name & "' value='" & ds("BT_baitai_id").Value & "'" & checked & ">" & ds("BT_name").Value & "</td>"

		count = count + 1
		If count Mod cols = 0 Then
			Writeln "</tr>"
		End If

		ds.MoveNext
	Loop

	If count Mod cols <> 0 Then
		Writeln "</tr>"
	End If

	Writeln "</table>"
End Sub

'=== メイン処理 ===
Dim job_id, search, a_type
Dim start_date_y, start_date_m, start_date_d, start_date_h, end_date_y, end_date_m, end_date_d, end_date_h
Dim send_num, point_jushin, point_kansou, point_enquete, limit_date_y, limit_date_m, limit_date_d, limit_date_h
Dim bunkatsu_kankaku, bunkatsu_kaisuu
Dim send_timing, send_timing_h, send_timing_d, teikei_qno, teikei_andor, teikei_ans, ct_id, mail_type
Dim parent_job_id, status, virgin_mail, get_kansou, job_type, sm_status
Dim imp_limit_y, imp_limit_m, imp_limit_d, imp_limit_h
Dim virgin_day, virgin_hour

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	a_type = True
ElseIf CheckPermission(PM_REGIST_JOB_B) Then
	a_type = False
Else
	NoPermission
End If

Set search = New CSearch

ReadSendCondition job_id

SetHeaderTitle "発信条件設定"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_regist() {
	var f = document.form1;
	if (InputCheck() && CheckKyoumiGenre()) {
		if (confirm("発信条件を登録します。よろしいですか？")) {
			f.target = "";
			f.action = "send_condition2.asp";
			f.submit();
		}
	}
}
function InputCheck() {
	var f = document.form1;
	if (f.start_date_y.value == "") {
		alert("発信開始日を入力してください。");
		f.start_date_y.focus();
		return false;
	}
<%If a_type Then%>
	if (f.end_date_y.value == "") {
		alert("発信終了日を入力してください。");
		f.end_date_y.focus();
		return false;
	}
<%End If%>
	if (f.send_num.value == "") {
		alert("最大発信数を入力してください。");
		f.send_num.focus();
		return false;
	}
<%If job_type = "1" Then%>
	if (!CheckRadio(f.send_timing)) {
		alert("発信タイミングを選択してください。");
		f.send_timing[0].focus();
		return false;
	}
<%End If%>
	if (f.jogai_hasshin_shitei.checked && f.taishou_hasshin_shitei.checked && dbl_check(f.jogai_hasshin.value, f.taishou_hasshin.value)) {
		alert("対象発信ジョブと除外発信ジョブに、同じジョブIDが入力されています。");
		return false;
	}
	if (f.jogai_response_shitei.checked && f.taishou_response_shitei.checked && dbl_check(f.jogai_response.value, f.taishou_response.value)) {
		alert("対象レスポンスジョブと除外レスポンスジョブに、同じジョブIDが入力されています。");
		return false;
	}
<%If CheckPermission(PM_JOGAI_BAITAI) And CheckPermission(PM_TAISHOU_BAITAI) Then%>
	var jogai = get_check(f.jogai_baitai);
	var taishou = get_check(f.taishou_baitai);
	if (f.jogai_baitai_shitei.checked && f.taishou_baitai_shitei.checked && dbl_check(jogai, taishou)) {
		alert("対象発信媒体と除外発信媒体に、同じ媒体IDが選択されています。");
		return false;
	}
<%End If%>
<%If system_id = "center" Then%>
	if (f.jogai_domain_shitei.checked && f.taishou_domain_shitei.checked && dbl_check(f.jogai_domain.value, f.taishou_domain.value)) {
		alert("対象発信ドメインと除外発信ドメインに、同じドメインIDが入力されています。");
		return false;
	}
<%End If%>
	return true;
}
function OnClick_kyoumi_genre(n) {
	var f = document.form1;
	if (f.kyoumi_genre1[n].checked)
		f.kyoumi_genre2[n].checked = true;
}
function AllCheck(name, check) {
	var f = document.form1;
	var n = f(name).length;
	for (var i = 0; i < n; i++)
		f(name)[i].checked = check;
}
function ShowHide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function JobSelect(title, name) {
	var url;
	url = "job_select.asp?job_id=<%=job_id%>&title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "job_select", "resizable=yes,scrollbars=yes,width=550");
}
function ClearDocchiha() {
	var f = document.form1;
	var a = f.docchiha_all.value.split(",");
	for (var i = 0; i < a.length; i++) {
		if (a[i] != "") {
			f("docchiha" + a[i])[0].checked = false;
			f("docchiha" + a[i])[1].checked = false;
			f("docchiha" + a[i])[2].checked = false;
		}
	}
}
function OnClick_search() {
	var f = document.form1;
	if (CheckKyoumiGenre()) {
		f.target = "_blank";
		f.action = "job_search.asp";
		f.submit();
	}
}
function CountKyoumiGenre(f) {
	var i, n, cnt;

	cnt = 0;
	n = f.kyoumi_genre1.length;
	for (i = 0; i < n ; i++) {
		if (f.kyoumi_genre1[i].checked || f.kyoumi_genre2[i].checked)
			cnt++;
	}
	return cnt;
}
function CheckKyoumiGenre() {
	var cnt;
	var f = document.form1
<%If mail_type <> "OH" And job_type = "0" Then%>
	cnt = CountKyoumiGenre(f);
	if (f.kyoumi_genre_shitei.checked == false || cnt == 0) {
		alert("興味ジャンルは必ず指定してください。");
		f.kyoumi_genre_shitei.focus(f);
		return false;
	}
	if (cnt > 5) {
		alert("興味ジャンルの指定は５個以内でお願いします。");
		f.kyoumi_genre_shitei.focus(f);
		return false;
	}
<%End If%>
	return true;
}
function CheckRadio(r) {
	var i;
	if (r.length) {
		for (i = 0; i < r.length; i++) {
			if (r[i].checked)
				return true;
		}
	} else {
		if (r.checked)
			return true;
	}
	return false;
}
function DomainSelect(title, name) {
	var url;
	url = "../../common/domain_select.asp?title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "domain_select", "resizable=yes,scrollbars=yes,width=550");
}
function dbl_check(s1, s2) {
	var a1 = s1.replace("\r", "").replace("\n", ",").split(",");
	var a2 = s2.replace("\r", "").replace("\n", ",").split(",");
	for (c1 in a1) {
		for (c2 in a2) {
			if (a1[c1] != "" && Number(a1[c1]) == Number(a2[c2]))
				return true;
		}
	}
	return false;
}
function get_check(c) {
	var v = "";
	if (c) {
		if (c.length) {
			for (var i = 0; i < c.length; i++) {
				if (c[i].checked) {
					if (v != "")
						v += ",";
					v += c[i].value;
				}
			}
		} else {
			if (c.checked)
				v = c.value;
		}
	}
	return v;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" name="form1" action="send_condition2.asp">
	<input type="hidden" name="job_id" value="<%=job_id%>">
	<table border=0 cellspacing=0 cellpadding=0 width='850'>
		<tr>
			<td class="m0">■発信条件を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3" width="20%"><%If a_type Then%>発信開始日<%Else%>発信希望日<%End If%></td>
						<td class="n3" width="80%">西暦
							<select name="start_date_y"><%SelectYear4 start_date_y%></select>年&nbsp;
							<select name="start_date_m"><%SelectMonth start_date_m%></select>月&nbsp;
							<select name="start_date_d"><%SelectDay start_date_d%></select>日&nbsp;
							<span<%Show a_type%>><select name="start_date_h"><%SelectHour start_date_h%></select>から</span>
						</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">発信終了日</td>
						<td class="n3">西暦
							<select name="end_date_y"><%SelectYear4 end_date_y%></select>年&nbsp;
							<select name="end_date_m"><%SelectMonth end_date_m%></select>月&nbsp;
							<select name="end_date_d"><%SelectDay end_date_d%></select>日&nbsp;
							<select name="end_date_h"><%SelectHour end_date_h%></select>まで
						</td>
					</tr>
					<tr>
						<td class="m3">最大発信数</td>
						<td class="n3"><input type="text" name="send_num" value="<%=send_num%>"></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">分割発信間隔</td>
						<td class="n3"><input type="text" name="bunkatsu_kankaku" value="<%=bunkatsu_kankaku%>">日</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">分割発信回数</td>
						<td class="n3"><input type="text" name="bunkatsu_kaisuu" value="<%=bunkatsu_kaisuu%>">回</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">受信ポイント</td>
						<td class="n3"><input type="text" name="point_jushin" value="<%=point_jushin%>"></td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">感想文ポイント</td>
						<td class="n3"><input type="text" name="point_kansou" value="<%=point_kansou%>"></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_HENSHIN_POINT)%>>
						<td class="m3">返信ポイント</td>
						<td class="n3"><input type="text" name="point_enquete" value="<%=point_enquete%>"></td>
					</tr>
					<tr>
						<td class="m3">締切り</td>
						<td class="n3">西暦
							<select name="limit_date_y"><%SelectYear2 limit_date_y%></select>年&nbsp;
							<select name="limit_date_m"><%SelectMonth limit_date_m%></select>月&nbsp;
							<select name="limit_date_d"><%SelectDay limit_date_d%></select>日&nbsp;
							<select name="limit_date_h"><%SelectHour2 limit_date_h%></select>まで
						</td>
					</tr>
<%If get_kansou = "1" Then%>
					<tr>
						<td class="m3">感想文締切り</td>
						<td class="n3">西暦
							<select name="imp_limit_y"><%SelectYear2 imp_limit_y%></select>年&nbsp;
							<select name="imp_limit_m"><%SelectMonth imp_limit_m%></select>月&nbsp;
							<select name="imp_limit_d"><%SelectDay imp_limit_d%></select>日&nbsp;
							<select name="imp_limit_h"><%SelectHour2 imp_limit_h%></select>まで
						</td>
					</tr>
<%End If%>
<%If job_type = "0" Then%>
					<tr<%Show a_type Or CheckPermission(PM_JOB_SOUSHIN_TAISHOU)%>>
						<td class="m3">送信対象会員</td>
						<td class="n3">
							<input type="radio" name="virgin_mail"<%ValueChecked "0", virgin_mail%> onclick="ShowHide('virgin_send_timing',false)">既存会員のみ
							<input type="radio" name="virgin_mail"<%ValueChecked "2", virgin_mail%> onclick="ShowHide('virgin_send_timing',true)">既存会員と新規会員
							<input type="radio" name="virgin_mail"<%ValueChecked "1", virgin_mail%> onclick="ShowHide('virgin_send_timing',true)">新規会員のみ
							<div id="virgin_send_timing" <%=Display(DispVirginSendTiming(virgin_mail))%>>　新規会員への送信タイミング：
								入会日から<input type="text" name="virgin_day" size=3 value="<%=virgin_day%>">日後
								<input type="text" name="virgin_hour" size=2 value="<%=virgin_hour%>">時
							</div>
						</td>
					</tr>
<%ElseIf job_type = "1" Then%>
					<tr>
						<td class="m3">発信タイミング</td>
						<td class="n3">
							<input type="hidden" name="virgin_mail" value="0">
							<input type="radio" name="send_timing" <%ValueChecked "1", send_timing%>>受信後、発信開始日から一斉に。あとはその都度、個別送信<br>
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
									<td><input type="radio" name="send_timing" <%ValueChecked "2", send_timing%>>受信時間から</td>
									<td>&nbsp;<input type="text" name="send_timing_h" size=4 value="<%=send_timing_h%>">&nbsp;時間後に個別送信</td>
								</tr>
								<tr>
									<td><input type="radio" name="send_timing" <%ValueChecked "3", send_timing%>>受信日から</td>
									<td>&nbsp;<input type="text" name="send_timing_d" size=4 value="<%=send_timing_d%>">&nbsp;日後に個別送信</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="m3">定型処理</td>
						<td class="n3">
							質問番号&nbsp;<input type="text" name="teikei_qno" size=4 value="<%=teikei_qno%>">&nbsp;
							<input type="radio" name="teikei_andor" <%ValueChecked "O", teikei_andor%>>OR
							<input type="radio" name="teikei_andor" <%ValueChecked "A", teikei_andor%>>AND
							<br><%TeikeiShori%>
						</td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">クリックカウンタ</td>
						<td class="n3"><%ClickCounter%></td>
					</tr>
<%End If%>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">■検索条件を入力してください</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3">興味ジャンル</td>
						<td class="n3">
							<input type="checkbox" name="kyoumi_genre_shitei" onclick="ShowHide('kyoumi_genre_btn',checked)" <%ValueChecked "1", search.kyoumi_genre_shitei%>>開く
							<span id="kyoumi_genre_btn" <%=Display(search.kyoumi_genre_shitei)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kyoumi_genre1', true);AllCheck('kyoumi_genre2', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kyoumi_genre1', false);AllCheck('kyoumi_genre2', false)">
								&nbsp;&nbsp;ジャンル間条件
								<input type="radio" name="kyoumi_genre_andor" <%ValueChecked "O", search.kyoumi_genre_andor%>>OR
								<input type="radio" name="kyoumi_genre_andor" <%ValueChecked "A", search.kyoumi_genre_andor%>>AND
								<br><%KyoumiGenre%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">性別</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="seibetsu_m" <%ValueChecked "1", search.seibetsu_m%>>男
							<input type="checkbox" name="seibetsu_f" <%ValueChecked "1", search.seibetsu_f%>>女
						</td>
					</tr>
					<tr>
						<td class="m3">年齢</td>
						<td class="n3">
							<input type="text" name="nenrei_from" size=4 value="<%=search.nenrei_from%>">才から
							<input type="text" name="nenrei_to" size=4 value="<%=search.nenrei_to%>">才まで
						</td>
					</tr>
					<tr>
						<td class="m3">未既婚</td>
						<td class="n3">
							<input type="checkbox" name="mikikon_mi" <%ValueChecked "1", search.mikikon_mi%>>独身
							<input type="checkbox" name="mikikon_ki" <%ValueChecked "1", search.mikikon_ki%>>既婚
						</td>
					</tr>
					<tr>
						<td class="m3">職業</td>
						<td class="n3"><%Shokugyou%></td>
					</tr>
					<tr>
						<td class="m3">居住地</td>
						<td class="n3">
							<input type="checkbox" name="kyojuu_shitei" onclick="ShowHide('kyojuu_btn',checked)" <%ValueChecked "1", search.kyojuu_shitei%>>指定する
							&nbsp;<input type="checkbox" name="kyojuu" value="海外"<%=Checked(Kaigai(search.kyojuu))%>>海外を含む
							<span id="kyojuu_btn" <%=Display(search.kyojuu_shitei)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kyojuu', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kyojuu', false)">
								<br><%Juusho "kyojuu", search.kyojuu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">勤務地</td>
						<td class="n3">
							<input type="checkbox" name="kinmu_shitei" onclick="ShowHide('kinmu_btn',checked)" <%ValueChecked "1", search.kinmu_shitei%>>指定する
							&nbsp;<input type="checkbox" name="kinmu" value="海外"<%=Checked(Kaigai(search.kinmu))%>>海外を含む
							<span id="kinmu_btn" <%=Display(search.kinmu_shitei)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kinmu', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kinmu', false)">
								&nbsp;　居住地／勤務地
								<input type="radio" name="kinmu_andor" value="O" <%ValueChecked "O", search.kinmu_andor%>>OR
								<input type="radio" name="kinmu_andor" value="A" <%ValueChecked "A", search.kinmu_andor%>>AND
								<br><%Juusho "kinmu", search.kinmu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">よく行く店</td>
						<td class="n3">
							<input type="checkbox" name="yokuikumise_shitei" onclick="ShowHide('yokuikumise_btn',checked)" <%ValueChecked "1", search.yokuikumise_shitei%>>指定する
							<span id="yokuikumise_btn" <%=Display(search.yokuikumise_shitei)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('yokuikumise', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('yokuikumise', false)">
								<br><%YokuikuMise%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">どっち派</td>
						<td class="n3">
							<input type="checkbox" name="docchiha_shitei" onclick="ShowHide('docchiha_btn',checked)" <%ValueChecked "1", search.docchiha_shitei%>>指定する
							<span id="docchiha_btn" <%=Display(search.docchiha_shitei)%>>&nbsp;
								<input type="button" value="オールクリア" onclick="ClearDocchiha()">
								<%DocchiHa%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">ＳＱＬ</td>
						<td class="n3">
							<input type="checkbox" name="sql_shitei" onclick="ShowHide('sql_btn',checked)" <%ValueChecked "1", search.sql_shitei%>>指定する
							<span id="sql_btn" <%=Display(search.sql_shitei)%>>&nbsp;
								<font class="note">（SQL文のWHERE条件を入力してください）</font><br>
								<textarea name="sql_text" cols=80 rows=5><%=search.sql_text%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">郵便番号指定</td>
						<td class="n3">
							<input type="checkbox" name="zip_shitei" onclick="ShowHide('zip_btn',checked)" <%ValueChecked "1", search.zip_shitei%>>指定する
							<span id="zip_btn" <%=Display(search.zip_shitei)%>>&nbsp;
								<font class="note">（郵便番号をカンマで区切って入力してください）</font><br>
								<textarea name="zip" cols=80 rows=5><%=search.zip%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">除外発信ジョブ</td>
						<td class="n3">
							<input type="checkbox" name="jogai_hasshin_shitei" onclick="ShowHide('jogai_hasshin_btn',checked)" <%ValueChecked "1", search.jogai_hasshin_shitei%>>指定する
							<span id="jogai_hasshin_btn" <%=Display(search.jogai_hasshin_shitei)%>>&nbsp;
								<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="JobSelect('除外発信ジョブ選択', 'jogai_hasshin')"><br>
								<textarea name="jogai_hasshin" cols=80 rows=3><%=search.jogai_hasshin%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">除外レスポンスジョブ</td>
						<td class="n3">
							<input type="checkbox" name="jogai_response_shitei" onclick="ShowHide('jogai_response_btn',checked)" <%ValueChecked "1", search.jogai_response_shitei%>>指定する
							<span id="jogai_response_btn" <%=Display(search.jogai_response_shitei)%>>&nbsp;
								<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="JobSelect('除外レスポンスジョブ選択', 'jogai_response')"><br>
								<textarea name="jogai_response" cols=80 rows=3><%=search.jogai_response%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">対象発信ジョブ</td>
						<td class="n3">
							<input type="checkbox" name="taishou_hasshin_shitei" onclick="ShowHide('taishou_hasshin_btn',checked)" <%ValueChecked "1", search.taishou_hasshin_shitei%>>指定する
							<span id="taishou_hasshin_btn" <%=Display(search.taishou_hasshin_shitei)%>>&nbsp;
								<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="JobSelect('対象発信ジョブ選択', 'taishou_hasshin')"><br>
								<textarea name="taishou_hasshin" cols=80 rows=3><%=search.taishou_hasshin%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">対象レスポンスジョブ</td>
						<td class="n3">
							<input type="checkbox" name="taishou_response_shitei" onclick="ShowHide('taishou_response_btn',checked)" <%ValueChecked "1", search.taishou_response_shitei%>>指定する
							<span id="taishou_response_btn" <%=Display(search.taishou_response_shitei)%>>&nbsp;
								<font class="note">（ジョブIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="JobSelect('対象レスポンスジョブ選択', 'taishou_response')"><br>
								<textarea name="taishou_response" cols=80 rows=3><%=search.taishou_response%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">メンバーリスト生成</td>
						<td class="n3">
							<input type="checkbox" name="member_list_shitei" onclick="ShowHide('member_list_btn',checked)" <%ValueChecked "1", search.member_list_shitei%>>指定する
							<span id="member_list_btn" <%=Display(search.member_list_shitei)%>>&nbsp;
								<font class="note">（会員IDをカンマで区切って入力してください）</font><br>
								<textarea name="member_list" cols=88 rows=5><%=search.member_list%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">HTMLメール受信可否</td>
						<td class="n3">
							<input type="checkbox" name="html_mail_shitei" onclick="ShowHide('html_mail_btn',checked)" <%ValueChecked "1", search.html_mail_shitei%>>指定する
							<span id="html_mail_btn" <%=Display(search.html_mail_shitei)%>>&nbsp;
								<input type="radio" name="html_mail"<%ValueChecked "1", search.html_mail%>>受信可能
								<input type="radio" name="html_mail"<%ValueChecked "0", search.html_mail%>>受信不可
							</span>
						</td>
					</tr>
<%If CheckPermission(PM_JOGAI_BAITAI) Then%>
					<tr>
						<td class="m3">除外発信媒体</td>
						<td class="n3">
							<input type="checkbox" name="jogai_baitai_shitei" onclick="ShowHide('jogai_baitai_btn',checked)" value="1"<%=Checked2(search.jogai_baitai)%>>指定する
							<span id="jogai_baitai_btn" <%=Display2(search.jogai_baitai)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('jogai_baitai', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('jogai_baitai', false)">
								<%BaitaiList search.jogai_baitai, "jogai_baitai"%>
							</span>
						</td>
					</tr>
<%End If%>
<%If CheckPermission(PM_TAISHOU_BAITAI) Then%>
					<tr>
						<td class="m3">対象発信媒体</td>
						<td class="n3">
							<input type="checkbox" name="taishou_baitai_shitei" onclick="ShowHide('taishou_baitai_btn',checked)" value="1"<%=Checked2(search.taishou_baitai)%>>指定する
							<span id="taishou_baitai_btn" <%=Display2(search.taishou_baitai)%>>&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('taishou_baitai', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('taishou_baitai', false)">
								<%BaitaiList search.taishou_baitai, "taishou_baitai"%>
							</span>
						</td>
					</tr>
<%End If%>
<%If system_id = "center" Then%>
					<tr>
						<td class="m3">除外発信ドメイン</td>
						<td class="n3">
							<input type="checkbox" name="jogai_domain_shitei" onclick="ShowHide('jogai_domain_btn',checked)" value="1"<%=Checked2(search.jogai_domain)%>>指定する
							<span id="jogai_domain_btn" <%=Display2(search.jogai_domain)%>>&nbsp;
								<font class="note">（ドメインIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="DomainSelect('除外発信ドメイン選択', 'jogai_domain')"><br>
								<textarea name="jogai_domain" cols=80 rows=3><%=search.jogai_domain%></textarea>
							</span>
						</td>
					</tr>
						<td class="m3">対象発信ドメイン</td>
						<td class="n3">
							<input type="checkbox" name="taishou_domain_shitei" onclick="ShowHide('taishou_domain_btn',checked)" value="1"<%=Checked2(search.taishou_domain)%>>指定する
							<span id="taishou_domain_btn" <%=Display2(search.taishou_domain)%>>&nbsp;
								<font class="note">（ドメインIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="DomainSelect('対象発信ドメイン選択', 'taishou_domain')"><br>
								<textarea name="taishou_domain" cols=80 rows=3><%=search.taishou_domain%></textarea>
							</span>
						</td>
					</tr>
<%End If%>
				</table>
			</td>
		</tr>
	</table>
	<br>
<%If status = "0" Or (system_id = "center" And sm_status = "0") Then%>
	<input type="button" value=" 登録 " onclick="OnClick_regist()">
	<input type="reset" value="リセット">
<%End If%>
	<input type="button" value="人数検索" onclick="OnClick_search()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
