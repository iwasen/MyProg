<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:企業情報変更処理
'******************************************************

'=== 代理店選択肢 ===
Sub SelectDairiten(dairiten_id)
	Dim sql, ds

	sql = "SELECT * FROM T_DAIRITEN ORDER BY DA_dairiten_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		Writeln "<option value='" & ds("DA_dairiten_id").Value & "'" & Selected(dairiten_id, ds("DA_dairiten_id").Value) & ">" & ds("DA_name") & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== 企業情報取得 ===
Sub GetCompany(company_id)
	Dim sql, ds

	sql = "SELECT * FROM T_COMPANY WHERE CM_company_id='" & company_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		dairiten_id = ds("CM_dairiten_id").Value
		name = ds("CM_name").Value
		name_kana = ds("CM_name_kana").Value
		zipcode = ds("CM_zipcode").Value
		address1 = ds("CM_address1").Value
		address2 = ds("CM_address2").Value
		regist_date = ds("CM_regist_date").Value
		update_date = ds("CM_update_date").Value
		pm_own.permission = ds("CM_permission").Value
	End If
End Sub

'=== メイン処理 ===
Dim company_id, dairiten_id, name, name_kana, zipcode, address1, address2, regist_date, update_date
Dim pm_own, pm_def

company_id = Request.QueryString("id")
If IsEmpty(company_id) Then
	SystemError "empty company_id"
End If

Set pm_own = New CPermission
Set pm_def = New CPermission
pm_def.SetAll

GetCompany company_id

SetHeaderTitle "企業情報変更"
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
 with (document.form1) {
		if (name.value == "") {
			alert("企業名を入力してください。");
			name.focus();
			return false;
		}
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("この企業を削除してよろしいですか？")) {
		location.href = "kigyou_c4.asp?id=<%=company_id%>";
	}
}
//-->
</SCRIPT>
<%
PermissionScript
%>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<form method="post" action="kigyou_c3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■企業情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">企業ＩＤ　</td>
						<td class="n3"><input type="hidden" name="company_id" value="<%=company_id%>"><%=company_id%></td>
					</tr>
					<tr>
						<td class="m3">代理店名</td>
						<td class="n3"><select name="dairiten_id"><%SelectDairiten(dairiten_id)%></select></td>
					</tr>
					<tr>
						<td class="m3">企業名</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50 value="<%=HTMLEncode(name)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">企業名（かな）</td>
						<td class="n3">
							<input size=50 name="name_kana" maxlength=50 value="<%=HTMLEncode(name_kana)%>">
							<font class="note">（半角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">郵便番号</td>
						<td class="n3">
							<input size=3 name="zipcode1" maxlength=3 value="<%=Mid(zipcode,1,3)%>"> -
							<input size=4 name="zipcode2" maxlength=4 value="<%=Mid(zipcode,4,4)%>">
							<font class="note">（数字３桁－４桁）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所１</td>
						<td class="n3">
							<input size=50 name="address1" maxlength=100 value="<%=HTMLEncode(address1)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所２</td>
						<td class="n3">
							<input size=50 name="address2" maxlength=100 value="<%=HTMLEncode(address2)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">登録日時</td>
						<td class="n3"><%=regist_date%></td>
					</tr>
					<tr>
						<td class="m3">最終更新日時</td>
						<td class="n3"><%=update_date%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm_own, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 削除 " onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
