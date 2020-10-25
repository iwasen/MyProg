<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�����o�[������ʏ���
'******************************************************

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

'=== �}�̃��X�g�\�� ===
Private Sub BaitaiList(sel, name)
	Dim count, sql, ds, checked, cols, temp, arry, where, item

	cols = 2
	count = 0

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

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

		Writeln "<td width='50%'><input type='checkbox' name='" & name & "' value='" & ds("BT_baitai_id").Value & "'>" & ds("BT_name").Value & "</td>"

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
Dim job_id, search

Set search = New CSearch

SetHeaderTitle "�����o�[����"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_kyoumi_genre(n) {
	if (document.form1.kyoumi_genre1[n].checked)
		document.form1.kyoumi_genre2[n].checked = true;
}
function AllCheck(name, check) {
	var n = document.form1(name).length;
	for (var i = 0; i < n; i++)
		document.form1(name)[i].checked = check;
}
function ShowHide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function ClearDocchiha() {
	var a = document.form1.docchiha_all.value.split(",");
	for (var i = 0; i < a.length; i++) {
		if (a[i] != "") {
			document.form1("docchiha" + a[i])[0].checked = false;
			document.form1("docchiha" + a[i])[1].checked = false;
			document.form1("docchiha" + a[i])[2].checked = false;
		}
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
function OnSubmit_form1(f) {
	var cnt;

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
<%If CheckPermission(PM_JOGAI_BAITAI) And CheckPermission(PM_TAISHOU_BAITAI) Then%>
	var jogai = get_check(f.jogai_baitai);
	var taishou = get_check(f.taishou_baitai);
	if (f.jogai_baitai_shitei.checked && f.taishou_baitai_shitei.checked && dbl_check(jogai, taishou)) {
		alert("�Ώ۔��M�}�̂Ə��O���M�}�̂ɁA�����}��ID���I������Ă��܂��B");
		return false;
	}
<%End If%>
	return true;
}
function OnLoad_body() {
	with (document.form1) {
		ShowHide('kyoumi_genre_btn',kyoumi_genre_shitei.checked);
		ShowHide('kyojuu_btn',kyojuu_shitei.checked);
		ShowHide('kinmu_btn',kinmu_shitei.checked);
		ShowHide('yokuikumise_btn',yokuikumise_shitei.checked);
		ShowHide('docchiha_btn',docchiha_shitei.checked);
		ShowHide('html_mail_btn',html_mail_shitei.checked);
<%If CheckPermission(PM_JOGAI_BAITAI) Then%>
		ShowHide('jogai_baitai_btn',jogai_baitai_shitei.checked);
<%End If%>
<%If CheckPermission(PM_TAISHOU_BAITAI) Then%>
		ShowHide('taishou_baitai_btn',taishou_baitai_shitei.checked);
<%End If%>
	}
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
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="result.asp" onsubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='850'>
		<tr>
			<td class="m0">���������������w�艺����</td>
			<td align="right"><input type="button" value="ALL�N���A" onclick="document.form1.reset();OnLoad_body()"></td>
		</tr>
		<tr>
			<td colspan=2>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3">�����W������</td>
						<td class="n3">
							<input type="checkbox" name="kyoumi_genre_shitei" onclick="ShowHide('kyoumi_genre_btn',checked)"<%ValueChecked "1", search.kyoumi_genre_shitei%>>�J��
							<span id="kyoumi_genre_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kyoumi_genre1', true);AllCheck('kyoumi_genre2', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kyoumi_genre1', false);AllCheck('kyoumi_genre2', false)">
								&nbsp;&nbsp;�W�������ԏ���
								<input type="radio" name="kyoumi_genre_andor"<%ValueChecked "O", search.kyoumi_genre_andor%>>OR
								<input type="radio" name="kyoumi_genre_andor"<%ValueChecked "A", search.kyoumi_genre_andor%>>AND
								<br><%KyoumiGenre%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="seibetsu_m"<%ValueChecked "1", search.seibetsu_m%>>�j
							<input type="checkbox" name="seibetsu_f"<%ValueChecked "1", search.seibetsu_f%>>��
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
						<td class="m3" width="20%">������</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="mikikon_mi"<%ValueChecked "1", search.mikikon_mi%>>�Ɛg
							<input type="checkbox" name="mikikon_ki"<%ValueChecked "1", search.mikikon_ki%>>����
						</td>
					</tr>
					<tr>
						<td class="m3">�E��</td>
						<td class="n3"><%Shokugyou%></td>
					</tr>
					<tr>
						<td class="m3">���Z�n�@��</td>
						<td class="n3">
							<input type="checkbox" name="kyojuu_shitei" onclick="ShowHide('kyojuu_btn',checked)"<%ValueChecked "1", search.kyojuu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kyojuu" value="�C�O"<%=Checked(Kaigai(search.kyojuu))%>>�C�O���܂�
							<span id="kyojuu_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kyojuu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kyojuu', false)">
								<br><%juusho "kyojuu", search.kyojuu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�Ζ��n�@��</td>
						<td class="n3">
							<input type="checkbox" name="kinmu_shitei" onclick="ShowHide('kinmu_btn',checked)"<%ValueChecked "1", search.kinmu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kinmu" value="�C�O"<%=Checked(Kaigai(search.kinmu))%>>�C�O���܂�
							<span id="kinmu_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kinmu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kinmu', false)">
								&nbsp;�@���Z�n�^�Ζ��n
								<input type="radio" name="kinmu_andor" value="O"<%ValueChecked "O", search.kinmu_andor%>>OR
								<input type="radio" name="kinmu_andor" value="A"<%ValueChecked "A", search.kinmu_andor%>>AND
								<br><%juusho "kinmu", search.kinmu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�悭�s���X</td>
						<td class="n3">
							<input type="checkbox" name="yokuikumise_shitei" onclick="ShowHide('yokuikumise_btn',checked)"<%ValueChecked "1", search.yokuikumise_shitei%>>�w�肷��
							<span id="yokuikumise_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('yokuikumise', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('yokuikumise', false)">
								<br><%YokuikuMise%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�ǂ����h</td>
						<td class="n3">
							<input type="checkbox" name="docchiha_shitei" onclick="ShowHide('docchiha_btn',checked)"<%ValueChecked "1", search.docchiha_shitei%>>�w�肷��
							<span id="docchiha_btn">&nbsp;
								<input type="button" value="�I�[���N���A" onclick="ClearDocchiha()">
								<%docchiha%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">HTML���[����M��</td>
						<td class="n3">
							<input type="checkbox" name="html_mail_shitei" onclick="ShowHide('html_mail_btn',checked)" <%ValueChecked "1", search.html_mail_shitei%>>�w�肷��
							<span id="html_mail_btn">&nbsp;
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
							<span id="jogai_baitai_btn">&nbsp;
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
							<span id="taishou_baitai_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('taishou_baitai', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('taishou_baitai', false)">
								<%BaitaiList search.taishou_baitai, "taishou_baitai"%>
							</span>
						</td>
					</tr>
<%End If%>
				</table>
			</td>
		</tr>
	</table>
	<p>�@</p>
		<table border=0 cellspacing=3 cellpadding=0 align="center">
			<tr>
				<td class="np" valign='TOP'>
					<div align="right">��</div>
				</td>
				<td class="np" nowrap> �u�w�肷��v�{�b�N�X���`�F�b�N���Ă��������ƁA47�s���{���i�܂��͎�v���ߎw��s�s�j�̂��w�肪�\�ł��B<br>
					�܂��A�u�C�O���܂ށv�{�b�N�X�Ƀ`�F�b�N����܂��ƁA�C�O�����o�[�ɂ��z�M���\�ł��B</td>
			</tr>
		</table>
		<p>
			<input type="submit" name="regist" value="�@�����@">
			<input type="button" value="�@�߂�@" onclick="location.href='../top/index.asp'">
		</p>
	</form>
</center>

<p align="center"> </p>
<div align="center">
	<table width="700" border="2" cellspacing="0" cellpadding="0" bordercolor="peru">
		<tr>
			<td>
				<table border=0 cellspacing=10 cellpadding=0 width='100%' align="center">
					<tr>
						<td class="info" valign='TOP'>
							<div align="right"><font color="#336699">��</font></div>
						</td>
						<td class="info"> �͂��߁[��Enet�ł́A�I�v�g�C�����[���ł��邽�߁A���[���R���e���c�Ƌ����W�������A�������̓��e�̐���������点�Ē����Ă���܂��B�z�M������e�Ƃ��w�荀�ڂ̓��e�ɂ��܂��ẮA�����e������������ɃZ���^�[���m�F�����Ă����������Ƃ��������܂��B���炩���߂��������������B<a href="http://manyu.haimail.net/sales/top/faq.html" target="_blank">�i�z�M�O�Ɋm�F�����Ă��������P�[�X�j</a>
						</td>
					</tr>
					<tr>
						<td class="info" valign='TOP'>
							<div align="right"><font color="#336699">��</font></div>
						</td>
						<td class="info"> ���������ɂ���ẮA�������ʂ̕\���ɏ��X�����Ԃ����������ꍇ���������܂��B </td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
