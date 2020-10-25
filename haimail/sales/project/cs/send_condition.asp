<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:���M������ʏ���
'******************************************************

'=== ���M�����擾 ===
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

'=== �����W�������\�� ===
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
			Writeln "<td class='m3' align='center'>�����W������</td>"
			Writeln "<td class='m3' align='center'>���Ȃ�</td>"
			Writeln "<td class='m3' align='center'>����</td>"
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

'=== �E�ƕ\�� ===
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

'=== �Z���\�� ===
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
				kakko = "�i"
				flag = True
			End If
			disp = "<font color='#008000'>" & ds("CI_shiku").Value & "</font>"
		End If

		ds.MoveNext

		If flag Then
			If ds.EOF Then
				disp = disp & "�j"
			Else
				If IsNull(ds("CI_shiku").Value) Then
					disp = disp & "�j"
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

'=== �C�O�`�F�b�N ===
Private Function Kaigai(adr)
	If InStr(adr, "�C�O") > 0 Then
		Kaigai = "1"
	End If
End Function

'=== �悭�s���X�\�� ===
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
				category_name = "�R���r�j"
			Case "02"
				category_name = "�X�[�p�["
			Case "03"
				category_name = "�悭���p����ƑԂ�T�[�r�X"
			Case "04"
				category_name = "�悭���p���邨�X��T�[�r�X"
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

'=== �ǂ����h�\�� ===
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
		Writeln "<td width='33%'><input type='radio' name='" & name & "' value='0'" & check0 & ">�ǂ���ł��Ȃ�</td>"
		Writeln "</tr>"

		count = count + 1
		ds.MoveNext
	Loop

	Writeln "</table>"
	Writeln "<input type='hidden' name='docchiha_all' value='" & all & "'>"
End Sub

'=== ��^�����\�� ===
Private Sub TeikeiShori()
	Dim i

	For i = 1 To 15
		Writeln "<input type='checkbox' name='teikei_ans' value='" & i & "'" & Checked(Mid(teikei_ans, i, 1)) & ">" & i
	Next
End Sub

'=== �o�[�W�����[�����M�^�C�~���O�\������ ===
Private Function DispVirginSendTiming(virgin_mail)
	If virgin_mail = "0" Then
		DispVirginSendTiming = "0"
	Else
		DispVirginSendTiming = "1"
	End If
End Function

'=== �N���b�N�J�E���^�\�� ===
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

'=== �}�̃��X�g�\�� ===
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

'=== ���C������ ===
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

SetHeaderTitle "���M�����ݒ�"
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
		if (confirm("���M������o�^���܂��B��낵���ł����H")) {
			f.target = "";
			f.action = "send_condition2.asp";
			f.submit();
		}
	}
}
function InputCheck() {
	var f = document.form1;
	if (f.start_date_y.value == "") {
		alert("���M�J�n������͂��Ă��������B");
		f.start_date_y.focus();
		return false;
	}
<%If a_type Then%>
	if (f.end_date_y.value == "") {
		alert("���M�I��������͂��Ă��������B");
		f.end_date_y.focus();
		return false;
	}
<%End If%>
	if (f.send_num.value == "") {
		alert("�ő唭�M������͂��Ă��������B");
		f.send_num.focus();
		return false;
	}
<%If job_type = "1" Then%>
	if (!CheckRadio(f.send_timing)) {
		alert("���M�^�C�~���O��I�����Ă��������B");
		f.send_timing[0].focus();
		return false;
	}
<%End If%>
	if (f.jogai_hasshin_shitei.checked && f.taishou_hasshin_shitei.checked && dbl_check(f.jogai_hasshin.value, f.taishou_hasshin.value)) {
		alert("�Ώ۔��M�W���u�Ə��O���M�W���u�ɁA�����W���uID�����͂���Ă��܂��B");
		return false;
	}
	if (f.jogai_response_shitei.checked && f.taishou_response_shitei.checked && dbl_check(f.jogai_response.value, f.taishou_response.value)) {
		alert("�Ώۃ��X�|���X�W���u�Ə��O���X�|���X�W���u�ɁA�����W���uID�����͂���Ă��܂��B");
		return false;
	}
<%If CheckPermission(PM_JOGAI_BAITAI) And CheckPermission(PM_TAISHOU_BAITAI) Then%>
	var jogai = get_check(f.jogai_baitai);
	var taishou = get_check(f.taishou_baitai);
	if (f.jogai_baitai_shitei.checked && f.taishou_baitai_shitei.checked && dbl_check(jogai, taishou)) {
		alert("�Ώ۔��M�}�̂Ə��O���M�}�̂ɁA�����}��ID���I������Ă��܂��B");
		return false;
	}
<%End If%>
<%If system_id = "center" Then%>
	if (f.jogai_domain_shitei.checked && f.taishou_domain_shitei.checked && dbl_check(f.jogai_domain.value, f.taishou_domain.value)) {
		alert("�Ώ۔��M�h���C���Ə��O���M�h���C���ɁA�����h���C��ID�����͂���Ă��܂��B");
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
		alert("�����W�������͕K���w�肵�Ă��������B");
		f.kyoumi_genre_shitei.focus(f);
		return false;
	}
	if (cnt > 5) {
		alert("�����W�������̎w��͂T�ȓ��ł��肢���܂��B");
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
			<td class="m0">�����M��������͂��Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3" width="20%"><%If a_type Then%>���M�J�n��<%Else%>���M��]��<%End If%></td>
						<td class="n3" width="80%">����
							<select name="start_date_y"><%SelectYear4 start_date_y%></select>�N&nbsp;
							<select name="start_date_m"><%SelectMonth start_date_m%></select>��&nbsp;
							<select name="start_date_d"><%SelectDay start_date_d%></select>��&nbsp;
							<span<%Show a_type%>><select name="start_date_h"><%SelectHour start_date_h%></select>����</span>
						</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">���M�I����</td>
						<td class="n3">����
							<select name="end_date_y"><%SelectYear4 end_date_y%></select>�N&nbsp;
							<select name="end_date_m"><%SelectMonth end_date_m%></select>��&nbsp;
							<select name="end_date_d"><%SelectDay end_date_d%></select>��&nbsp;
							<select name="end_date_h"><%SelectHour end_date_h%></select>�܂�
						</td>
					</tr>
					<tr>
						<td class="m3">�ő唭�M��</td>
						<td class="n3"><input type="text" name="send_num" value="<%=send_num%>"></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">�������M�Ԋu</td>
						<td class="n3"><input type="text" name="bunkatsu_kankaku" value="<%=bunkatsu_kankaku%>">��</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">�������M��</td>
						<td class="n3"><input type="text" name="bunkatsu_kaisuu" value="<%=bunkatsu_kaisuu%>">��</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">��M�|�C���g</td>
						<td class="n3"><input type="text" name="point_jushin" value="<%=point_jushin%>"></td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">���z���|�C���g</td>
						<td class="n3"><input type="text" name="point_kansou" value="<%=point_kansou%>"></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_HENSHIN_POINT)%>>
						<td class="m3">�ԐM�|�C���g</td>
						<td class="n3"><input type="text" name="point_enquete" value="<%=point_enquete%>"></td>
					</tr>
					<tr>
						<td class="m3">���؂�</td>
						<td class="n3">����
							<select name="limit_date_y"><%SelectYear2 limit_date_y%></select>�N&nbsp;
							<select name="limit_date_m"><%SelectMonth limit_date_m%></select>��&nbsp;
							<select name="limit_date_d"><%SelectDay limit_date_d%></select>��&nbsp;
							<select name="limit_date_h"><%SelectHour2 limit_date_h%></select>�܂�
						</td>
					</tr>
<%If get_kansou = "1" Then%>
					<tr>
						<td class="m3">���z�����؂�</td>
						<td class="n3">����
							<select name="imp_limit_y"><%SelectYear2 imp_limit_y%></select>�N&nbsp;
							<select name="imp_limit_m"><%SelectMonth imp_limit_m%></select>��&nbsp;
							<select name="imp_limit_d"><%SelectDay imp_limit_d%></select>��&nbsp;
							<select name="imp_limit_h"><%SelectHour2 imp_limit_h%></select>�܂�
						</td>
					</tr>
<%End If%>
<%If job_type = "0" Then%>
					<tr<%Show a_type Or CheckPermission(PM_JOB_SOUSHIN_TAISHOU)%>>
						<td class="m3">���M�Ώۉ��</td>
						<td class="n3">
							<input type="radio" name="virgin_mail"<%ValueChecked "0", virgin_mail%> onclick="ShowHide('virgin_send_timing',false)">��������̂�
							<input type="radio" name="virgin_mail"<%ValueChecked "2", virgin_mail%> onclick="ShowHide('virgin_send_timing',true)">��������ƐV�K���
							<input type="radio" name="virgin_mail"<%ValueChecked "1", virgin_mail%> onclick="ShowHide('virgin_send_timing',true)">�V�K����̂�
							<div id="virgin_send_timing" <%=Display(DispVirginSendTiming(virgin_mail))%>>�@�V�K����ւ̑��M�^�C�~���O�F
								���������<input type="text" name="virgin_day" size=3 value="<%=virgin_day%>">����
								<input type="text" name="virgin_hour" size=2 value="<%=virgin_hour%>">��
							</div>
						</td>
					</tr>
<%ElseIf job_type = "1" Then%>
					<tr>
						<td class="m3">���M�^�C�~���O</td>
						<td class="n3">
							<input type="hidden" name="virgin_mail" value="0">
							<input type="radio" name="send_timing" <%ValueChecked "1", send_timing%>>��M��A���M�J�n�������ĂɁB���Ƃ͂��̓s�x�A�ʑ��M<br>
							<table border=0 cellspacing=0 cellpadding=0>
								<tr>
									<td><input type="radio" name="send_timing" <%ValueChecked "2", send_timing%>>��M���Ԃ���</td>
									<td>&nbsp;<input type="text" name="send_timing_h" size=4 value="<%=send_timing_h%>">&nbsp;���Ԍ�Ɍʑ��M</td>
								</tr>
								<tr>
									<td><input type="radio" name="send_timing" <%ValueChecked "3", send_timing%>>��M������</td>
									<td>&nbsp;<input type="text" name="send_timing_d" size=4 value="<%=send_timing_d%>">&nbsp;����Ɍʑ��M</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="m3">��^����</td>
						<td class="n3">
							����ԍ�&nbsp;<input type="text" name="teikei_qno" size=4 value="<%=teikei_qno%>">&nbsp;
							<input type="radio" name="teikei_andor" <%ValueChecked "O", teikei_andor%>>OR
							<input type="radio" name="teikei_andor" <%ValueChecked "A", teikei_andor%>>AND
							<br><%TeikeiShori%>
						</td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">�N���b�N�J�E���^</td>
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
			<td class="m0">��������������͂��Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3">�����W������</td>
						<td class="n3">
							<input type="checkbox" name="kyoumi_genre_shitei" onclick="ShowHide('kyoumi_genre_btn',checked)" <%ValueChecked "1", search.kyoumi_genre_shitei%>>�J��
							<span id="kyoumi_genre_btn" <%=Display(search.kyoumi_genre_shitei)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kyoumi_genre1', true);AllCheck('kyoumi_genre2', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kyoumi_genre1', false);AllCheck('kyoumi_genre2', false)">
								&nbsp;&nbsp;�W�������ԏ���
								<input type="radio" name="kyoumi_genre_andor" <%ValueChecked "O", search.kyoumi_genre_andor%>>OR
								<input type="radio" name="kyoumi_genre_andor" <%ValueChecked "A", search.kyoumi_genre_andor%>>AND
								<br><%KyoumiGenre%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="seibetsu_m" <%ValueChecked "1", search.seibetsu_m%>>�j
							<input type="checkbox" name="seibetsu_f" <%ValueChecked "1", search.seibetsu_f%>>��
						</td>
					</tr>
					<tr>
						<td class="m3">�N��</td>
						<td class="n3">
							<input type="text" name="nenrei_from" size=4 value="<%=search.nenrei_from%>">�˂���
							<input type="text" name="nenrei_to" size=4 value="<%=search.nenrei_to%>">�˂܂�
						</td>
					</tr>
					<tr>
						<td class="m3">������</td>
						<td class="n3">
							<input type="checkbox" name="mikikon_mi" <%ValueChecked "1", search.mikikon_mi%>>�Ɛg
							<input type="checkbox" name="mikikon_ki" <%ValueChecked "1", search.mikikon_ki%>>����
						</td>
					</tr>
					<tr>
						<td class="m3">�E��</td>
						<td class="n3"><%Shokugyou%></td>
					</tr>
					<tr>
						<td class="m3">���Z�n</td>
						<td class="n3">
							<input type="checkbox" name="kyojuu_shitei" onclick="ShowHide('kyojuu_btn',checked)" <%ValueChecked "1", search.kyojuu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kyojuu" value="�C�O"<%=Checked(Kaigai(search.kyojuu))%>>�C�O���܂�
							<span id="kyojuu_btn" <%=Display(search.kyojuu_shitei)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kyojuu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kyojuu', false)">
								<br><%Juusho "kyojuu", search.kyojuu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�Ζ��n</td>
						<td class="n3">
							<input type="checkbox" name="kinmu_shitei" onclick="ShowHide('kinmu_btn',checked)" <%ValueChecked "1", search.kinmu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kinmu" value="�C�O"<%=Checked(Kaigai(search.kinmu))%>>�C�O���܂�
							<span id="kinmu_btn" <%=Display(search.kinmu_shitei)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kinmu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kinmu', false)">
								&nbsp;�@���Z�n�^�Ζ��n
								<input type="radio" name="kinmu_andor" value="O" <%ValueChecked "O", search.kinmu_andor%>>OR
								<input type="radio" name="kinmu_andor" value="A" <%ValueChecked "A", search.kinmu_andor%>>AND
								<br><%Juusho "kinmu", search.kinmu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�悭�s���X</td>
						<td class="n3">
							<input type="checkbox" name="yokuikumise_shitei" onclick="ShowHide('yokuikumise_btn',checked)" <%ValueChecked "1", search.yokuikumise_shitei%>>�w�肷��
							<span id="yokuikumise_btn" <%=Display(search.yokuikumise_shitei)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('yokuikumise', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('yokuikumise', false)">
								<br><%YokuikuMise%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�ǂ����h</td>
						<td class="n3">
							<input type="checkbox" name="docchiha_shitei" onclick="ShowHide('docchiha_btn',checked)" <%ValueChecked "1", search.docchiha_shitei%>>�w�肷��
							<span id="docchiha_btn" <%=Display(search.docchiha_shitei)%>>&nbsp;
								<input type="button" value="�I�[���N���A" onclick="ClearDocchiha()">
								<%DocchiHa%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�r�p�k</td>
						<td class="n3">
							<input type="checkbox" name="sql_shitei" onclick="ShowHide('sql_btn',checked)" <%ValueChecked "1", search.sql_shitei%>>�w�肷��
							<span id="sql_btn" <%=Display(search.sql_shitei)%>>&nbsp;
								<font class="note">�iSQL����WHERE��������͂��Ă��������j</font><br>
								<textarea name="sql_text" cols=80 rows=5><%=search.sql_text%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�X�֔ԍ��w��</td>
						<td class="n3">
							<input type="checkbox" name="zip_shitei" onclick="ShowHide('zip_btn',checked)" <%ValueChecked "1", search.zip_shitei%>>�w�肷��
							<span id="zip_btn" <%=Display(search.zip_shitei)%>>&nbsp;
								<font class="note">�i�X�֔ԍ����J���}�ŋ�؂��ē��͂��Ă��������j</font><br>
								<textarea name="zip" cols=80 rows=5><%=search.zip%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">���O���M�W���u</td>
						<td class="n3">
							<input type="checkbox" name="jogai_hasshin_shitei" onclick="ShowHide('jogai_hasshin_btn',checked)" <%ValueChecked "1", search.jogai_hasshin_shitei%>>�w�肷��
							<span id="jogai_hasshin_btn" <%=Display(search.jogai_hasshin_shitei)%>>&nbsp;
								<font class="note">�i�W���uID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="JobSelect('���O���M�W���u�I��', 'jogai_hasshin')"><br>
								<textarea name="jogai_hasshin" cols=80 rows=3><%=search.jogai_hasshin%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">���O���X�|���X�W���u</td>
						<td class="n3">
							<input type="checkbox" name="jogai_response_shitei" onclick="ShowHide('jogai_response_btn',checked)" <%ValueChecked "1", search.jogai_response_shitei%>>�w�肷��
							<span id="jogai_response_btn" <%=Display(search.jogai_response_shitei)%>>&nbsp;
								<font class="note">�i�W���uID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="JobSelect('���O���X�|���X�W���u�I��', 'jogai_response')"><br>
								<textarea name="jogai_response" cols=80 rows=3><%=search.jogai_response%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�Ώ۔��M�W���u</td>
						<td class="n3">
							<input type="checkbox" name="taishou_hasshin_shitei" onclick="ShowHide('taishou_hasshin_btn',checked)" <%ValueChecked "1", search.taishou_hasshin_shitei%>>�w�肷��
							<span id="taishou_hasshin_btn" <%=Display(search.taishou_hasshin_shitei)%>>&nbsp;
								<font class="note">�i�W���uID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="JobSelect('�Ώ۔��M�W���u�I��', 'taishou_hasshin')"><br>
								<textarea name="taishou_hasshin" cols=80 rows=3><%=search.taishou_hasshin%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�Ώۃ��X�|���X�W���u</td>
						<td class="n3">
							<input type="checkbox" name="taishou_response_shitei" onclick="ShowHide('taishou_response_btn',checked)" <%ValueChecked "1", search.taishou_response_shitei%>>�w�肷��
							<span id="taishou_response_btn" <%=Display(search.taishou_response_shitei)%>>&nbsp;
								<font class="note">�i�W���uID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="JobSelect('�Ώۃ��X�|���X�W���u�I��', 'taishou_response')"><br>
								<textarea name="taishou_response" cols=80 rows=3><%=search.taishou_response%></textarea>
							</span>
						</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�����o�[���X�g����</td>
						<td class="n3">
							<input type="checkbox" name="member_list_shitei" onclick="ShowHide('member_list_btn',checked)" <%ValueChecked "1", search.member_list_shitei%>>�w�肷��
							<span id="member_list_btn" <%=Display(search.member_list_shitei)%>>&nbsp;
								<font class="note">�i���ID���J���}�ŋ�؂��ē��͂��Ă��������j</font><br>
								<textarea name="member_list" cols=88 rows=5><%=search.member_list%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">HTML���[����M��</td>
						<td class="n3">
							<input type="checkbox" name="html_mail_shitei" onclick="ShowHide('html_mail_btn',checked)" <%ValueChecked "1", search.html_mail_shitei%>>�w�肷��
							<span id="html_mail_btn" <%=Display(search.html_mail_shitei)%>>&nbsp;
								<input type="radio" name="html_mail"<%ValueChecked "1", search.html_mail%>>��M�\
								<input type="radio" name="html_mail"<%ValueChecked "0", search.html_mail%>>��M�s��
							</span>
						</td>
					</tr>
<%If CheckPermission(PM_JOGAI_BAITAI) Then%>
					<tr>
						<td class="m3">���O���M�}��</td>
						<td class="n3">
							<input type="checkbox" name="jogai_baitai_shitei" onclick="ShowHide('jogai_baitai_btn',checked)" value="1"<%=Checked2(search.jogai_baitai)%>>�w�肷��
							<span id="jogai_baitai_btn" <%=Display2(search.jogai_baitai)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('jogai_baitai', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('jogai_baitai', false)">
								<%BaitaiList search.jogai_baitai, "jogai_baitai"%>
							</span>
						</td>
					</tr>
<%End If%>
<%If CheckPermission(PM_TAISHOU_BAITAI) Then%>
					<tr>
						<td class="m3">�Ώ۔��M�}��</td>
						<td class="n3">
							<input type="checkbox" name="taishou_baitai_shitei" onclick="ShowHide('taishou_baitai_btn',checked)" value="1"<%=Checked2(search.taishou_baitai)%>>�w�肷��
							<span id="taishou_baitai_btn" <%=Display2(search.taishou_baitai)%>>&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('taishou_baitai', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('taishou_baitai', false)">
								<%BaitaiList search.taishou_baitai, "taishou_baitai"%>
							</span>
						</td>
					</tr>
<%End If%>
<%If system_id = "center" Then%>
					<tr>
						<td class="m3">���O���M�h���C��</td>
						<td class="n3">
							<input type="checkbox" name="jogai_domain_shitei" onclick="ShowHide('jogai_domain_btn',checked)" value="1"<%=Checked2(search.jogai_domain)%>>�w�肷��
							<span id="jogai_domain_btn" <%=Display2(search.jogai_domain)%>>&nbsp;
								<font class="note">�i�h���C��ID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="DomainSelect('���O���M�h���C���I��', 'jogai_domain')"><br>
								<textarea name="jogai_domain" cols=80 rows=3><%=search.jogai_domain%></textarea>
							</span>
						</td>
					</tr>
						<td class="m3">�Ώ۔��M�h���C��</td>
						<td class="n3">
							<input type="checkbox" name="taishou_domain_shitei" onclick="ShowHide('taishou_domain_btn',checked)" value="1"<%=Checked2(search.taishou_domain)%>>�w�肷��
							<span id="taishou_domain_btn" <%=Display2(search.taishou_domain)%>>&nbsp;
								<font class="note">�i�h���C��ID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="DomainSelect('�Ώ۔��M�h���C���I��', 'taishou_domain')"><br>
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
	<input type="button" value=" �o�^ " onclick="OnClick_regist()">
	<input type="reset" value="���Z�b�g">
<%End If%>
	<input type="button" value="�l������" onclick="OnClick_search()">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
