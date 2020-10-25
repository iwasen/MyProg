<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:メンバー検索画面処理
'******************************************************

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

	Writeln "</td>"
	Writeln "</tr>"
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

'=== 媒体リスト表示 ===
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

'=== メイン処理 ===
Dim job_id, search

Set search = New CSearch

SetHeaderTitle "メンバー検索"
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
		alert("対象発信媒体と除外発信媒体に、同じ媒体IDが選択されています。");
		return false;
	}
	if (f.jogai_domain_shitei.checked && f.taishou_domain_shitei.checked && dbl_check(f.jogai_domain.value, f.taishou_domain.value)) {
		alert("対象発信ドメインと除外発信ドメインに、同じドメインIDが入力されています。");
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
			<td class="m0">■検索条件を入力してください</td>
			<td align="right"><input type="button" value="ALLクリア" onclick="document.form1.reset();ShowReset()"></td>
		</tr>
		<tr>
			<td colspan=2>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3">興味ジャンル</td>
						<td class="n3">
							<input type="checkbox" name="kyoumi_genre_shitei" onclick="ShowHide('kyoumi_genre_btn',checked)"<%ValueChecked "1", search.kyoumi_genre_shitei%>>開く
							<span id="kyoumi_genre_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kyoumi_genre1', true);AllCheck('kyoumi_genre2', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kyoumi_genre1', false);AllCheck('kyoumi_genre2', false)">
								&nbsp;&nbsp;ジャンル間条件
								<input type="radio" name="kyoumi_genre_andor"<%ValueChecked "O", search.kyoumi_genre_andor%>>OR
								<input type="radio" name="kyoumi_genre_andor"<%ValueChecked "A", search.kyoumi_genre_andor%>>AND
								<br><%KyoumiGenre%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">性別</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="seibetsu_m"<%ValueChecked "1", search.seibetsu_m%>>男
							<input type="checkbox" name="seibetsu_f"<%ValueChecked "1", search.seibetsu_f%>>女
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
						<td class="m3" width="20%">未既婚</td>
						<td class="n3" width="80%">
							<input type="checkbox" name="mikikon_mi"<%ValueChecked "1", search.mikikon_mi%>>独身
							<input type="checkbox" name="mikikon_ki"<%ValueChecked "1", search.mikikon_ki%>>既婚
						</td>
					</tr>
					<tr>
						<td class="m3">職業</td>
						<td class="n3"><%Shokugyou%></td>
					</tr>
					<tr>
						<td class="m3">居住地</td>
						<td class="n3">
							<input type="checkbox" name="kyojuu_shitei" onclick="ShowHide('kyojuu_btn',checked)"<%ValueChecked "1", search.kyojuu_shitei%>>指定する
							&nbsp;<input type="checkbox" name="kyojuu" value="海外"<%=Checked(Kaigai(search.kyojuu))%>>海外を含む
							<span id="kyojuu_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kyojuu', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kyojuu', false)">
								<br><%Juusho "kyojuu", search.kyojuu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">勤務地</td>
						<td class="n3">
							<input type="checkbox" name="kinmu_shitei" onclick="ShowHide('kinmu_btn',checked)"<%ValueChecked "1", search.kinmu_shitei%>>指定する
							&nbsp;<input type="checkbox" name="kinmu" value="海外"<%=Checked(Kaigai(search.kinmu))%>>海外を含む
							<span id="kinmu_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('kinmu', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('kinmu', false)">
								&nbsp;　居住地／勤務地
								<input type="radio" name="kinmu_andor" value="O"<%ValueChecked "O", search.kinmu_andor%>>OR
								<input type="radio" name="kinmu_andor" value="A"<%ValueChecked "A", search.kinmu_andor%>>AND
								<br><%Juusho "kinmu", search.kinmu%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">よく行く店</td>
						<td class="n3">
							<input type="checkbox" name="yokuikumise_shitei" onclick="ShowHide('yokuikumise_btn',checked)"<%ValueChecked "1", search.yokuikumise_shitei%>>指定する
							<span id="yokuikumise_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('yokuikumise', true)">
								<input type="button" value="オールクリア" onclick="AllCheck('yokuikumise', false)">
								<br><%YokuikuMise%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">どっち派</td>
						<td class="n3">
							<input type="checkbox" name="docchiha_shitei" onclick="ShowHide('docchiha_btn',checked)"<%ValueChecked "1", search.docchiha_shitei%>>指定する
							<span id="docchiha_btn">&nbsp;
								<input type="button" value="オールクリア" onclick="ClearDocchiha()">
								<%DocchiHa%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">HTMLメール受信可否</td>
						<td class="n3">
							<input type="checkbox" name="html_mail_shitei" onclick="ShowHide('html_mail_btn',checked)"<%ValueChecked "1", search.html_mail_shitei%>>指定する
							<span id="html_mail_btn">&nbsp;
								<input type="radio" name="html_mail"<%ValueChecked "1", search.html_mail%>>受信可能
								<input type="radio" name="html_mail"<%ValueChecked "0", search.html_mail%>>受信不可
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">ﾊﾞｷｭｰﾝ・ﾊﾟｰﾐｼｮﾝ</td>
						<td class="n3">
							<input type="checkbox" name="oshirase_mail_shitei" onclick="ShowHide('oshirase_mail_btn',checked)"<%ValueChecked "1", search.oshirase_mail_shitei%>>指定する
							<span id="oshirase_mail_btn">&nbsp;
								<input type="radio" name="oshirase_mail" value="1"<%ValueChecked "1", search.oshirase_mail%>>はい
								<input type="radio" name="oshirase_mail" value="0"<%ValueChecked "0", search.oshirase_mail%>>いいえ
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">除外発信媒体</td>
						<td class="n3">
							<input type="checkbox" name="jogai_baitai_shitei" onclick="ShowHide('jogai_baitai_btn',checked)" value="1"<%=Checked2(search.jogai_baitai)%>>指定する
							<span id="jogai_baitai_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('jogai_baitai', true)" id=button1 name=button1>
								<input type="button" value="オールクリア" onclick="AllCheck('jogai_baitai', false)" id=button3 name=button3>
								<%BaitaiList search.jogai_baitai, "jogai_baitai"%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">対象発信媒体</td>
						<td class="n3">
							<input type="checkbox" name="taishou_baitai_shitei" onclick="ShowHide('taishou_baitai_btn',checked)" value="1"<%=Checked2(search.taishou_baitai)%>>指定する
							<span id="taishou_baitai_btn">&nbsp;
								<input type="button" value="オールチェック" onclick="AllCheck('taishou_baitai', true)" id=button2 name=button2>
								<input type="button" value="オールクリア" onclick="AllCheck('taishou_baitai', false)" id=button4 name=button4>
								<%BaitaiList search.taishou_baitai, "taishou_baitai"%>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">除外発信ドメイン</td>
						<td class="n3">
							<input type="checkbox" name="jogai_domain_shitei" onclick="ShowHide('jogai_domain_btn',checked)" value="1"<%=Checked2(search.jogai_domain)%>>指定する
							<span id="jogai_domain_btn">&nbsp;
								<font class="note">（ドメインIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="DomainSelect('除外発信ドメイン選択', 'jogai_domain')"><br>
								<textarea name="jogai_domain" cols=80 rows=3><%=search.jogai_domain%></textarea>
							</span>
						</td>
					</tr>
						<td class="m3">対象発信ドメイン</td>
						<td class="n3">
							<input type="checkbox" name="taishou_domain_shitei" onclick="ShowHide('taishou_domain_btn',checked)" value="1"<%=Checked2(search.taishou_domain)%>>指定する
							<span id="taishou_domain_btn">&nbsp;
								<font class="note">（ドメインIDをカンマで区切って入力するか、「選択」ボタンをクリックしてください）</font>&nbsp;
								<input type="button" value="選択" onclick="DomainSelect('対象発信ドメイン選択', 'taishou_domain')"><br>
								<textarea name="taishou_domain" cols=80 rows=3><%=search.taishou_domain%></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3">検索結果の出力</td>
						<td class="n3">
							<input type="radio" name="result" value="0" checked>件数のみ
							<input type="radio" name="result" value="1">会員IDを出力
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" name="regist" value=" 検索 ">
	<input type="button" value=" 戻る " onclick="location.href='../menu.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
