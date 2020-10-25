<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:企業情報登録処理
'******************************************************

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

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== メイン処理 ===
Dim dairiten_id, pm, d_class

Set pm = New CPermission

dairiten_id = GetDairitenID(g_login_id)
GetPermission dairiten_id

SetHeaderTitle "企業情報登録"
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
	return confirm("登録します。よろしいですか？");
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
				<p><font color="#000000">発信企業情報の登録が行えます。下記項目をご入力下さい。</font></p>
				<p>■発信企業情報</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">発信企業名　※１</td>
						<td class="n3" width="80%">
							<input size=50 name="name" maxlength=50>
							<font class="note">（必須）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">発信企業名かな</td>
						<td class="n3" width="80%">
							<input size=50 name="name_kana" maxlength=100>
							<font class="note">（必須）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">郵便番号</td>
						<td class="n3" width="80%">
							<input size=3 name="zipcode1" maxlength=3>
							-
							<input size=4 name="zipcode2" maxlength=4>
							<font class="note">（任意：半角数字７桁　例：105-0004）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">住所１</td>
						<td class="n3" width="80%">
							<input size=50 name="address1" maxlength=50>
							<font class="note">（任意：都道府県～市区町村まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">住所２</td>
						<td class="n3" width="80%">
							<input size=50 name="address2" maxlength=50>
							<font class="note">（任意：ビル・マンション名等）</font>
						</td>
					</tr>
					<tr>
						<td class="np" width="20%" valign="top">
							<div align="right">※１：</div>
						</td>
						<td class="np" width="80%">発信企業名はお間違いのないよう、ご入力をお願いいたします。<br>
							入力されたデータは、メールのヘッダに発信企業名として入力されます。 </td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0" width="140">■許可する権限</td>
			<td class="m0" width="560"><font size="-1" color="#000000">ご入稿から、クリックレポートの出力までクライアント様へ付与する機能の権限設定が可能です。</font></td>
		</tr>
		<tr>
			<td colspan="2"><%PermissionForm 3, pm, pm%></td>
		</tr>
	</table>
	<p>　</p>
	<p>
		<input type="submit" value=" 登録 ">
		<input type="reset" value="リセット">
		<input type="button" value=" 戻る " onclick="history.back()">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
