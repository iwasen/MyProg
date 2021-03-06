<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店担当者情報登録処理
'******************************************************

'=== 代理店情報取得 ===
Sub GetDairiten(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_name FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		dairiten_name = ds("DA_name").Value
	End If
End Sub

'=== メイン処理 ===
SetHeaderTitle "代理店担当者情報登録"
'Response.CacheControl = "no-cache"

Dim dairiten_id, dairiten_name

dairiten_id = Request.QueryString("dairiten_id")
If IsEmpty(dairiten_id) Then
	SystemError "empty dairiten_id"
End If

GetDairiten dairiten_id
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1(id) {
 with (document.form1) {
		if (operator_id.value == "") {
			alert("担当者IDを入力してください。");
			operator_id.focus();
			return false;
		}
		if (!isA(operator_id.value)) {
			alert("担当者IDは半角英数で入力してください。");
			operator_id.focus();
			return false;
		}
		if (password.value == "") {
			alert("パスワードを入力してください。");
			password.focus();
			return false;
		}
		if (!isA(password.value)) {
			alert("パスワードは半角英数で入力してください。");
			password.focus();
			return false;
		}
		if (name1.value == "") {
			alert("担当者名を入力してください。");
			name1.focus();
			return false;
		}
		if (!isA(mail_adr.value)) {
			alert("メールアドレスは半角英数で入力してください。");
			mail_adr.focus();
			return false;
		}
		if (!isA(tel_no.value)) {
			alert("電話番号は半角英数で入力してください。");
			tel_no.focus();
			return false;
		}
		if (!isA(fax_no.value)) {
			alert("FAX番号は半角英数で入力してください。");
			fax_no.focus();
			return false;
		}
	}
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="operator3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="id_flag" value=1>
		<tr>
			<td class="m0" colspan="2">■代理店情報</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">代理店ＩＤ</td>
						<td class="n3">
							<input type="hidden" name="dairiten_id" value=<%=dairiten_id%>>
							<%=dairiten_id%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">代理店名称</td>
						<td class="n3"><%=ReplaceTag(dairiten_name)%></td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td class="m0" width="25%">■担当者情報</td>
			<td class="m0" width="75%"><font size="-1" color="#000000">代理店担当者様基本情報をご入力下さい。</font></td>
		</tr>
		<tr>
			<td colspan="2">
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">担当者ID</td>
						<td class="n3">
							<input size=20 name="operator_id" maxlength=10>
							<font class="note">（必須：半角英数５文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">パスワード</td>
						<td class="n3">
							<input size=20 name="password" maxlength=14>
							<font class="note">（必須：半角英数１０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">担当者名</td>
						<td class="n3"> <font class="note">姓</font>
							<input size=20 name="name1" maxlength=10>
							<font class="note">名</font>
							<input size=20 name="name2" maxlength=10>
							<font class="note">（必須：全角）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">担当者名（カナ）</td>
						<td class="n3"> <font class="note">姓</font>
							<input size=20 name="name1_kana" maxlength=20>
							<font class="note">名</font>
							<input size=20 name="name2_kana" maxlength=20>
							<font class="note">（必須：半角カナ）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">メールアドレス</td>
						<td class="n3">
							<input size=50 name="mail_adr" maxlength=50>
							<font class="note">（必須：半角英数）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">電話番号</td>
						<td class="n3">
							<input size=20 name="tel_no" maxlength=20>
							<font class="note">（必須：半角数字）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="25%">FAX番号</td>
						<td class="n3">
							<input size=20 name="fax_no" maxlength=20>
							<font class="note">（必須：半角数字）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 登録 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="location.href='index.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
