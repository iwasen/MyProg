<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
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

	Writeln "</td>"
	Writeln "</tr>"
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
	Dim count, sql, ds, checked, cols

	Writeln "<table border=1 width='100%' bgcolor='#FFFFFF' bordercolorlight='#d0d0d0'>"

	cols = 2
	count = 0
	sql = "SELECT BT_baitai_id,BT_name FROM T_BAITAI WHERE BT_search_flag='1' ORDER BY BT_order"
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
function ShowReset() {
	with (document.form1) {
		ShowHide('kyoumi_genre_btn',kyoumi_genre_shitei.checked);
		ShowHide('kyojuu_btn',kyojuu_shitei.checked);
		ShowHide('kinmu_btn',kinmu_shitei.checked);
		ShowHide('yokuikumise_btn',yokuikumise_shitei.checked);
		ShowHide('docchiha_btn',docchiha_shitei.checked);
		ShowHide('html_mail_btn',html_mail_shitei.checked);
		ShowHide('oshirase_mail_btn',oshirase_mail_shitei.checked);
		ShowHide('jogai_baitai_btn',jogai_baitai_shitei.checked);
		ShowHide('taishou_baitai_btn',taishou_baitai_shitei.checked);
		ShowHide('jogai_domain_btn',jogai_domain_shitei.checked);
		ShowHide('taishou_domain_btn',taishou_domain_shitei.checked);
	}
}
function DomainSelect(title, name) {
	var url;
	url = "../common/domain_select.asp?title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
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
function OnSubmit_form1(f) {
	var jogai = get_check(f.jogai_baitai);
	var taishou = get_check(f.taishou_baitai);
	if (f.jogai_baitai_shitei.checked && f.taishou_baitai_shitei.checked && dbl_check(jogai, taishou)) {
		alert("�Ώ۔��M�}�̂Ə��O���M�}�̂ɁA�����}��ID���I������Ă��܂��B");
		return false;
	}
	if (f.jogai_domain_shitei.checked && f.taishou_domain_shitei.checked && dbl_check(f.jogai_domain.value, f.taishou_domain.value)) {
		alert("�Ώ۔��M�h���C���Ə��O���M�h���C���ɁA�����h���C��ID�����͂���Ă��܂��B");
		return false;
	}
	return true;
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
<body onload="ShowReset()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="result.asp" target="_blank" onsubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='850'>
		<tr>
			<td class="m0">��������������͂��Ă�������</td>
			<td align="right"><input type="button" value="ALL�N���A" onclick="document.form1.reset();ShowReset()"></td>
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
						<td class="m3">���Z�n</td>
						<td class="n3">
							<input type="checkbox" name="kyojuu_shitei" onclick="ShowHide('kyojuu_btn',checked)"<%ValueChecked "1", search.kyojuu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kyojuu" value="�C�O"<%=Checked(Kaigai(search.kyojuu))%>>�C�O���܂�
							<span id="kyojuu_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kyojuu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kyojuu', false)">
								<br><%Juusho "kyojuu", search.kyojuu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�Ζ��n</td>
						<td class="n3">
							<input type="checkbox" name="kinmu_shitei" onclick="ShowHide('kinmu_btn',checked)"<%ValueChecked "1", search.kinmu_shitei%>>�w�肷��
							&nbsp;<input type="checkbox" name="kinmu" value="�C�O"<%=Checked(Kaigai(search.kinmu))%>>�C�O���܂�
							<span id="kinmu_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('kinmu', true)">
								<input type="button" value="�I�[���N���A" onclick="AllCheck('kinmu', false)">
								&nbsp;�@���Z�n�^�Ζ��n
								<input type="radio" name="kinmu_andor" value="O"<%ValueChecked "O", search.kinmu_andor%>>OR
								<input type="radio" name="kinmu_andor" value="A"<%ValueChecked "A", search.kinmu_andor%>>AND
								<br><%Juusho "kinmu", search.kinmu%>
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
								<%DocchiHa%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">HTML���[����M��</td>
						<td class="n3">
							<input type="checkbox" name="html_mail_shitei" onclick="ShowHide('html_mail_btn',checked)"<%ValueChecked "1", search.html_mail_shitei%>>�w�肷��
							<span id="html_mail_btn">&nbsp;
								<input type="radio" name="html_mail"<%ValueChecked "1", search.html_mail%>>��M�\
								<input type="radio" name="html_mail"<%ValueChecked "0", search.html_mail%>>��M�s��
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�޷��݁E�߰м��</td>
						<td class="n3">
							<input type="checkbox" name="oshirase_mail_shitei" onclick="ShowHide('oshirase_mail_btn',checked)"<%ValueChecked "1", search.oshirase_mail_shitei%>>�w�肷��
							<span id="oshirase_mail_btn">&nbsp;
								<input type="radio" name="oshirase_mail" value="1"<%ValueChecked "1", search.oshirase_mail%>>�͂�
								<input type="radio" name="oshirase_mail" value="0"<%ValueChecked "0", search.oshirase_mail%>>������
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">���O���M�}��</td>
						<td class="n3">
							<input type="checkbox" name="jogai_baitai_shitei" onclick="ShowHide('jogai_baitai_btn',checked)" value="1"<%=Checked2(search.jogai_baitai)%>>�w�肷��
							<span id="jogai_baitai_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('jogai_baitai', true)" id=button1 name=button1>
								<input type="button" value="�I�[���N���A" onclick="AllCheck('jogai_baitai', false)" id=button3 name=button3>
								<%BaitaiList search.jogai_baitai, "jogai_baitai"%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�Ώ۔��M�}��</td>
						<td class="n3">
							<input type="checkbox" name="taishou_baitai_shitei" onclick="ShowHide('taishou_baitai_btn',checked)" value="1"<%=Checked2(search.taishou_baitai)%>>�w�肷��
							<span id="taishou_baitai_btn">&nbsp;
								<input type="button" value="�I�[���`�F�b�N" onclick="AllCheck('taishou_baitai', true)" id=button2 name=button2>
								<input type="button" value="�I�[���N���A" onclick="AllCheck('taishou_baitai', false)" id=button4 name=button4>
								<%BaitaiList search.taishou_baitai, "taishou_baitai"%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">���O���M�h���C��</td>
						<td class="n3">
							<input type="checkbox" name="jogai_domain_shitei" onclick="ShowHide('jogai_domain_btn',checked)" value="1"<%=Checked2(search.jogai_domain)%>>�w�肷��
							<span id="jogai_domain_btn">&nbsp;
								<font class="note">�i�h���C��ID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="DomainSelect('���O���M�h���C���I��', 'jogai_domain')"><br>
								<textarea name="jogai_domain" cols=80 rows=3><%=search.jogai_domain%></textarea>
							</span>
						</td>
					</tr>
						<td class="m3">�Ώ۔��M�h���C��</td>
						<td class="n3">
							<input type="checkbox" name="taishou_domain_shitei" onclick="ShowHide('taishou_domain_btn',checked)" value="1"<%=Checked2(search.taishou_domain)%>>�w�肷��
							<span id="taishou_domain_btn">&nbsp;
								<font class="note">�i�h���C��ID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������j</font>&nbsp;
								<input type="button" value="�I��" onclick="DomainSelect('�Ώ۔��M�h���C���I��', 'taishou_domain')"><br>
								<textarea name="taishou_domain" cols=80 rows=3><%=search.taishou_domain%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">�������ʂ̏o��</td>
						<td class="n3">
							<input type="radio" name="result" value="0" checked>�����̂�
							<input type="radio" name="result" value="1">���ID���o��
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" name="regist" value=" ���� ">
	<input type="button" value=" �߂� " onclick="location.href='../menu.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
