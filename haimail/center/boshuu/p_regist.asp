<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:個人アフィリエイト募集登録画面
'******************************************************

'=== 前データのID取得 ===
Private Function GetPrevPRID()
	Dim sql, ds

	sql = "SELECT MAX(PR_pr_id) FROM T_PR WHERE PR_baitai_id='03'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetPrevPRID = ds(0).Value
	End If
End Function

'=== 次のPRID取得 ===
Private Function GetNextPRID()
	Dim sql, ds

	sql = "SELECT MAX(PR_pr_id) FROM T_PR"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	GetNextPRID = MakeID(CLng(NVL(ds(0).Value, 0) + 1), 7)
End Function

'=== チェックサム生成 ===
Private Function CreateCheckSum(id)
	Dim sum, i

	sum = 0
	For i = 1 To Len(id)
		sum = (sum + CInt(Mid(id, i, 1))) Mod 10
	Next
	CreateCheckSum = Chr((10 - sum) Mod 10 + Asc("0"))
End Function

'=== ＤＢよりデータ読み込み ===
Private Sub ReadData(id)
	Dim sql, ds

	sql = "SELECT * FROM T_PR WHERE PR_pr_id='" & id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		member_id = ds("PR_member_id").Value
		member_hp = ds("PR_member_hp").Value
		regist_adr = ds("PR_regist_adr").Value
		url = ds("PR_url").Value
		haishin_start_y = Year(ds("PR_haishin_start").Value)
		haishin_start_m = Month(ds("PR_haishin_start").Value)
		haishin_start_d = Day(ds("PR_haishin_start").Value)
		haishin_end_y = Year(ds("PR_haishin_end").Value)
		haishin_end_m = Month(ds("PR_haishin_end").Value)
		haishin_end_d = Day(ds("PR_haishin_end").Value)
		affiliate = ds("PR_affiliate").Value
		bikou = ds("PR_bikou").Value
	End If
End Sub

'=== メイン処理 ===
SetHeaderTitle "個人アフィリエイト募集登録"

Dim pr_id, member_id, member_hp, regist_adr, url
Dim haishin_start_y, haishin_start_m, haishin_start_d, haishin_end_y, haishin_end_m, haishin_end_d, affiliate, bikou
Dim new_pr

pr_id = Request.QueryString("id")
If IsEmpty(pr_id) Then
	pr_id = GetNextPRID()
	If IsEmpty(Request.QueryString("cp")) Then
		haishin_start_m = 1
		haishin_start_d = 1
		haishin_end_m = 1
		haishin_end_d = 1
		affiliate = 100
		member_hp = "http://"
	Else
		ReadData GetPrevPRID
	End If
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
		if (member_id.value == "") {
			alert("会員IDを入力してください。");
			member_id.focus();
			return false;
		}
	}
	return true;
}
function OnClick_regist() {
	if (InputCheck()) {
		if (confirm("ＰＲを登録します。よろしいですか？")) {
			document.form1.action.value = "regist";
			document.form1.submit();
		}
	}
}
function OnClick_update() {
	if (InputCheck()) {
		if (confirm("ＰＲを更新します。よろしいですか？")) {
			document.form1.action.value = "update";
			document.form1.submit();
		}
	}
}
function OnClick_delete() {
	if (InputCheck()) {
		if (confirm("ＰＲを削除します。よろしいですか？")) {
			document.form1.action.value = "delete";
			document.form1.submit();
		}
	}
}
function OnClick_copy() {
	location.href = "p_regist.asp?cp=1";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="p_regist2.asp" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='75%'>
		<tr>
			<td class="m0">■ＰＲ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">PRID</td>
						<td class="n3"><%=pr_id%><input type="hidden" name="pr_id" value="<%=pr_id%>"></td>
					</tr>
					<tr>
						<td class="m3">メンバーID</td>
						<td class="n3">
							<input type="text" name="member_id" size=12 maxlength=10 value="<%=member_id%>">
							<font class="note">（上位の0は省略可）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">登録アドレス</td>
						<td class="n3">
							<input type="text" name="regist_adr" size=50 maxlength=50 value="<%=HTMLEncode(regist_adr)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">メンバーＨＰアドレス</td>
						<td class="n3">
							<input type="text" name="member_hp" size=70 maxlength=100 value="<%=HTMLEncode(member_hp)%>">
							<input type="button" value="表示" onclick="window.open(document.form1.member_hp.value)">
							<font class="note">（半角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">URL</td>
						<td class="n3"><%=url%><input type="hidden" name="url" value="<%=HTMLEncode(url)%>"></td>
					</tr>
					<tr>
						<td class="m3">配信開始日</td>
						<td class="n3">
							<input type="text" name="haishin_start_y" size=4 maxlength=4 value="<%=haishin_start_y%>">年&nbsp;
							<select name="haishin_start_m"><%SelectMonth haishin_start_m%></select>月&nbsp;
							<select name="haishin_start_d"><%SelectDay haishin_start_d%></select>日
						</td>
					</tr>
					<tr>
						<td class="m3">配信終了日</td>
						<td class="n3">
							<input type="text" name="haishin_end_y" size=4 maxlength=4 value="<%=haishin_end_y%>">年&nbsp;
							<select name="haishin_end_m"><%SelectMonth haishin_end_m%></select>月&nbsp;
							<select name="haishin_end_d"><%SelectDay haishin_end_d%></select>日
						</td>
					</tr>
					<tr>
						<td class="m3">アフィリエイト単価</td>
						<td class="n3">
							<input type="text" name="affiliate" value="<%=affiliate%>">
							<font class="note">（数字８桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">備考</td>
						<td class="n3">
							<input type="text" name="bikou" size=80 value="<%=HTMLEncode(bikou)%>">
							<font class="note">（全角２５０文字まで）</font>
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
	<input type="button" name="copy" value="前ﾃﾞｰﾀｺﾋﾟｰ" Onclick="OnClick_copy()">
	<input type="button" name="regist" value=" 登録 " Onclick="OnClick_regist()">
<%
Else
%>
	<input type="button" name="update" value=" 更新 " Onclick="OnClick_update()">
	<input type="button" name="delete" value=" 削除 " Onclick="OnClick_delete()">
<%
End If
%>
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
	<input type="hidden" name="action">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
