<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:企業情報変更処理
'******************************************************

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

'=== 代理店ID取得 ===
Private Function GetDairitenID(operator_id)
	Dim sql, ds

	sql = "SELECT OP_dairiten_id FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetDairitenID = ds(0).Value
	End If
End Function

'=== クライアント権限取得 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_name,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm_def.permission = ds("DA_pm_client").Value
		dairiten_name = ds("DA_name").Value
	End If
End Sub

'=== メイン処理 ===
SetHeaderTitle "企業情報変更"
Response.CacheControl = "no-cache"

Dim company_id, dairiten_id, name, name_kana, zipcode, address1, address2, regist_date, update_date, dairiten_name
Dim pm_own, pm_def

company_id = Request.QueryString("id")
If IsEmpty(company_id) Then
	SystemError "empty company_id"
End If

Set pm_own = New CPermission
Set pm_def = New CPermission

GetCompany company_id

dairiten_id = GetDairitenID(g_login_id)
GetPermission dairiten_id
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
		location.href = "company4.asp?id=<%=company_id%>";
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
<form method="post" action="company3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>

				<td class="m0">
					<table width="700" border="0" cellspacing="2" cellpadding="3">
						<tr>
							<td class="m0">
								<p><font color="#000000">発信企業情報の更新が行えます。更新箇所をご入力の上、「更新」ボタンを押して下さい。</font></p>
								<p>■発信企業情報 </p>
							</td>
						</tr>
					</table>

				</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">企業ＩＤ</td>
						<td class="n3" width="80%"><input type="hidden" name="company_id" value="<%=company_id%>"><%=company_id%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">代理店名</td>
						<td class="n3" width="80%"><%=ReplaceTag(dairiten_name)%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">企業名</td>
						<td class="n3" width="80%">
							<input size=50 name="name" maxlength=50 value="<%=HTMLEncode(name)%>">
							<font class="note">（必須）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">企業名（かな）</td>
						<td class="n3" width="80%">
							<input size=50 name="name_kana" maxlength=50 value="<%=HTMLEncode(name_kana)%>">
							<font class="note">（必須）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">郵便番号</td>
						<td class="n3" width="80%">
							<input size=3 name="zipcode1" maxlength=3 value="<%=Mid(zipcode,1,3)%>"> -
							<input size=4 name="zipcode2" maxlength=4 value="<%=Mid(zipcode,4,4)%>">
							<font class="note">（任意：半角数字７桁　例：105-0004）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">住所１</td>
						<td class="n3" width="80%">
							<input size=50 name="address1" maxlength=100 value="<%=HTMLEncode(address1)%>">
							<font class="note">（任意：都道府県～市区町村まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">住所２</td>
						<td class="n3" width="80%">
							<input size=50 name="address2" maxlength=100 value="<%=HTMLEncode(address2)%>">
							<font class="note">（任意：ビル・マンション名等）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">登録日時</td>
						<td class="n3" width="80%"><%=regist_date%></td>
					</tr>
					<tr>
						<td class="m3" width="20%">最終更新日時</td>
						<td class="n3" width="80%"><%=update_date%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table border=0 cellspacing=2 cellpadding=0 width="700">
		<tr>
			<td class="m0" width="20%">■許可する権限</td>
			<td class="m0" width="80%"><font size="-1" color="#000000">ご入稿から、クリックレポートの出力までクライアントへ付与する機能の権限設定が可能です。</font></td>
		</tr>
		<tr>
			<td colspan="2"><%PermissionForm 3, pm_own, pm_def%></td>
		</tr>
		<tr>
			<td colspan="2">　</td>
		</tr>
		<tr>
			<td colspan="2" class="np">削除ボタンを押されてしまいますと、過去に実施されたプロジェクトの閲覧が<b>全て不可</b>となります。ご注意下さい。</td>
		</tr>
	</table>
	<p>　</p>
	<p>
		<input type="submit" value=" 更新 ">
		<input type="reset" value="リセット">
		<input type="button" value=" 削除 " onclick="OnClick_sakujo()">
		<input type="button" value=" 戻る " onclick="history.back()">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
