<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��ʕ�W�o�^���
'******************************************************

'=== �}�̑I���� ===
Private Sub WriteBaitai(baitai_id)
	Dim sql, ds

	sql = "SELECT * FROM T_BAITAI WHERE BT_baitai_id<>'03' ORDER BY BT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(baitai_id, "") & ">- �I�����Ă������� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("BT_baitai_id").Value & "'" & Selected(baitai_id, ds("BT_baitai_id").Value) & ">" & ds("BT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== �L���^�C�v�I���� ===
Private Sub WriteKoukokuType(koukoku_id)
	Dim sql, ds

	sql = "SELECT * FROM T_KOUKOKU_TYPE ORDER BY KT_order"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(koukoku_id, "") & ">- �I�����Ă������� -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("KT_koukoku_id").Value & "'" & Selected(koukoku_id, ds("KT_koukoku_id").Value) & ">" & ds("KT_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== �O�f�[�^��ID�擾 ===
Private Function GetPrevPRID()
	Dim sql, ds

	sql = "SELECT MAX(PR_pr_id) FROM T_PR WHERE PR_baitai_id<>'03'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetPrevPRID = ds(0).Value
	End If
End Function

'=== ����PRID�擾 ===
Private Function GetNextPRID()
	Dim sql, ds

	sql = "SELECT MAX(PR_pr_id) FROM T_PR"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	GetNextPRID = MakeID(CLng(NVL(ds(0).Value, 0) + 1), 7)
End Function

'=== �`�F�b�N�T������ ===
Private Function CreateCheckSum(id)
	Dim sum, i

	sum = 0
	For i = 1 To Len(id)
		sum = (sum + CInt(Mid(id, i, 1))) Mod 10
	Next
	CreateCheckSum = Chr((10 - sum) Mod 10 + Asc("0"))
End Function

'=== �c�a���f�[�^�ǂݍ��� ===
Private Sub ReadData(id)
	Dim sql, ds

	sql = "SELECT * FROM T_PR WHERE PR_pr_id='" & id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		baitai_id = ds("PR_baitai_id").Value
		pr_name = ds("PR_pr_name").Value
		koukoku_id = ds("PR_koukoku_id").Value
		contents_type = ds("PR_contents_type").Value
		contents_text = ReadCLOB(ds("PR_contents_text").Value)
		url = ds("PR_url").Value
		haishin_suu = ds("PR_haishin_suu").Value
		click_suu = ds("PR_click_suu").Value
		haishin_start_y = Year(ds("PR_haishin_start").Value)
		haishin_start_m = Month(ds("PR_haishin_start").Value)
		haishin_start_d = Day(ds("PR_haishin_start").Value)
		haishin_end_y = Year(ds("PR_haishin_end").Value)
		haishin_end_m = Month(ds("PR_haishin_end").Value)
		haishin_end_d = Day(ds("PR_haishin_end").Value)
		cost = ds("PR_cost").Value
		affiliate = ds("PR_affiliate").Value
		bikou = ds("PR_bikou").Value
		contents_pict = False
		If Not IsNull(ds("PR_contents_pict").Value) Then
			If Not ds("PR_contents_pict").Value.IsNull Then
				If ds("PR_contents_pict").Value.Size > 0 Then
					contents_pict = True
				End If
			End If
		End If
	End If
End Sub

'=== ���C������ ===
SetHeaderTitle "��ʕ�W�o�^"

Dim pr_id, baitai_id, pr_name, koukoku_id, contents_type, contents_text, contents_pict, url, haishin_suu, click_suu
Dim haishin_start_y, haishin_start_m, haishin_start_d, haishin_end_y, haishin_end_m, haishin_end_d, cost, affiliate, bikou
Dim new_pr

pr_id = Request.QueryString("id")
If IsEmpty(pr_id) Then
	pr_id = GetNextPRID()
	If IsEmpty(Request.QueryString("cp")) Then
		haishin_start_m = 1
		haishin_start_d = 1
		haishin_end_m = 1
		haishin_end_d = 1
	Else
		ReadData GetPrevPRID
	End If
	contents_text = ""
	url = "http://www.haimail.net/pr/" & pr_id & CreateCheckSum(pr_id) & "/"
	new_pr = True
Else
	ReadData pr_id
End If

%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function InputCheck() {
	with (document.form1) {
		if (baitai_id.value == "") {
			alert("�}�̖���I�����Ă��������B");
			baitai_id.focus();
			return false;
		}
		if (pr_name.value == "") {
			alert("�o�q������͂��Ă��������B");
			pr_name.focus();
			return false;
		}
		if (koukoku_id.value == "") {
			alert("�L���^�C�v��I�����Ă��������B");
			koukoku_id.focus();
			return false;
		}
	}
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("�o�q��o�^���܂��B��낵���ł����H")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("�o�q���X�V���܂��B��낵���ł����H")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (InputCheck()) {
		if (confirm("�o�q���폜���܂��B��낵���ł����H")) {
			document.form1.action.value = "delete";
			document.form1.submit();
		}
	}
}
function OnClick_showpict() {
	if (document.form1.contents_pict.value != "") {
		document.all.pict.style.width = "";
		document.all.pict.style.height = "";
		document.all.pict.src = document.form1.contents_pict.value;
	}
}
function OnClick_copy() {
	location.href = "g_regist.asp?cp=1";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="g_regist2.asp" name="form1" ENCtype="multipart/form-data">
	<table border=0 cellspacing=0 cellpadding=0 width='75%'>
		<tr>
			<td class="m0">���o�q���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">PRID</td>
						<td class="n3"><%=pr_id%><input type="hidden" name="pr_id" value="<%=pr_id%>"></td>
					</tr>
					<tr>
						<td class="m3">�}�̖�</td>
						<td class="n3"><select name="baitai_id"><%WriteBaitai baitai_id%></select></td>
					</tr>
					<tr>
						<td class="m3">�o�q��</td>
						<td class="n3">
							<input type="text" name="pr_name" size=50 maxlength=25 value="<%=HTMLEncode(pr_name)%>">
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L���^�C�v</td>
						<td class="n3"><select name="koukoku_id"><%WriteKoukokuType koukoku_id%></select></td>
					</tr>
					<tr>
						<td class="m3">�R���e���c�i�e�L�X�g�j</td>
						<td class="n3"><textarea name="contents_text" cols=78 rows=5 class="np"><%=contents_text%></textarea></td>
					</tr>
					<tr>
						<td class="m3">�R���e���c�i�摜�j</td>
						<td class="n3"><img src="contents_pict.asp?id=<%=pr_id%>" id="pict" alt="�摜"<%If Not contents_pict Then Response.Write " style='width:0;height:0'"%>><br>
							<input type="file" name="contents_pict" size=50>
							<input type="button" value="�\��" onclick="OnClick_showpict()"></td>
					</tr>
					<tr>
						<td class="m3">URL</td>
						<td class="n3"><%=url%><input type="hidden" name="url" value="<%=HTMLEncode(url)%>"></td>
					</tr>
					<tr>
						<td class="m3">�z�M��</td>
						<td class="n3">
							<input type="text" name="haishin_suu" maxlength=8 value="<%=haishin_suu%>">
							<font class="note">�i�����W���܂Łj</font>
							</td>
					</tr>
					<tr>
						<td class="m3">�ۏ؃N���b�N��</td>
						<td class="n3">
							<input type="text" name="click_suu" maxlength=8 value="<%=click_suu%>">
							<font class="note">�i�����W���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�z�M�J�n��</td>
						<td class="n3">
							<input type="text" name="haishin_start_y" size=4 maxlength=4 value="<%=haishin_start_y%>">�N&nbsp;
							<select name="haishin_start_m"><%SelectMonth haishin_start_m%></select>��&nbsp;
							<select name="haishin_start_d"><%SelectDay haishin_start_d%></select>��
						</td>
					</tr>
					<tr>
						<td class="m3">�z�M�I����</td>
						<td class="n3">
							<input type="text" name="haishin_end_y" size=4 maxlength=4 value="<%=haishin_end_y%>">�N&nbsp;
							<select name="haishin_end_m"><%SelectMonth haishin_end_m%></select>��&nbsp;
							<select name="haishin_end_d"><%SelectDay haishin_end_d%></select>��
						</td>
					</tr>
					<tr>
						<td class="m3">�R�X�g</td>
						<td class="n3">
							<input type="text" name="cost" maxlength=8 value="<%=cost%>">
							<font class="note">�i�����W���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�A�t�B���G�C�g�P��</td>
						<td class="n3">
							<input type="text" name="affiliate" maxlength=8 value="<%=affiliate%>">
							<font class="note">�i�����W���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���l</td>
						<td class="n3">
							<input type="text" name="bikou" size=80 value="<%=HTMLEncode(bikou)%>">
							<font class="note">�i�S�p�Q�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
<%
If new_pr THen
%>
	<input type="button" name="copy" value="�O�ް���߰" Onclick="OnClick_copy()">
	<input type="button" name="regist" value=" �o�^ " Onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" �X�V " Onclick="OnClick_update()">
	<input type="button" name="delete" value=" �폜 " Onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
	<input type="hidden" name="action">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
