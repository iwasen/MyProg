<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:オペレータ情報修正処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "オペレータ情報修正"
Response.CacheControl = "no-cache"

Dim sql, ds, operator_id

operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
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
function isA(val)
{
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	// Returns true if val is a eisu
	for(var i=0; i< val.length; i++)
	{
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
		}
	return true;
}

function OnSubmit_form1() {
 with (document.form1) {
		if (operator_id.value == "") {
			alert("オペレータIDを入力してください。");
			operator_id.focus();
			return false;
		}
		if (!isA(operator_id.value)) {
			alert("オペレータIDは半角英数で入力してください。");
			operator_id.focus();
			return false;
		}
		if (!isA(mail_adr.value)) {
			alert("メールアドレスは半角英数で入力してください。");
			mail_adr.focus();
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
			alert("姓 を入力してください。");
			name1.focus();
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
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("このオペレータ情報を削除してよろしいですか？")) {
		location.href = "operator4.asp?id=<%=operator_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<%
sql = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR, T_DAIRITEN" & vbCrLf & _
			"WHERE OP_operator_id='" & operator_id & "' AND OP_dairiten_id=DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="operator3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="operator_id" value="<%=ds("OP_operator_id").Value%>">
		<input type="hidden" name="dairiten_id" value="<%=ds("OP_dairiten_id").Value%>">
		<input type="hidden" name="regist_date" value=<%=ds("OP_regist_date").Value%>>
		<tr>
			<td class="m0">■代理店情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">代理店ＩＤ</td>
						<td class="n3"><%=ds("OP_dairiten_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">代理店名</td>
						<td class="n3"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td class="m0">■オペレータ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">オペレータID</td>
						<td class="n3"><%=ds("OP_operator_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">パスワード</td>
						<td class="n3">
							<input size=20 name="password" maxlength=14 value="<%=ds("OP_password").Value%>">
							<font class="note">（半角１４文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">担当者名</td>
						<td class="n3">
							<font class="note">姓</font><input size=20 name="name1" maxlength=10 value="<%=HTMLEncode(ds("OP_name1").Value)%>">
							<font class="note">名</font><input size=20 name="name2" maxlength=10 value="<%=HTMLEncode(ds("OP_name2").Value)%>">
							<font class="note">（全角１０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">担当者名（カナ）</td>
						<td class="n3">
							<font class="note">姓</font><input size=20 name="name1_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name1_kana").Value)%>">
							<font class="note">名</font><input size=20 name="name2_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name2_kana").Value)%>">
							<font class="note">（半角２０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">メールアドレス</td>
						<td class="n3">
							<input size=50 name="mail_adr" maxlength=50 value="<%=HTMLEncode(ds("OP_mail_adr").Value)%>">
							<font class="note">（半角５０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">電話番号</td>
						<td class="n3">
							<input size=20 name="tel_no" maxlength=20 value="<%=ds("OP_tel_no").Value%>">
							<font class="note">（半角２０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">FAX番号</td>
						<td class="n3">
							<input size=20 name="fax_no" maxlength=20 value="<%=ds("OP_fax_no").Value%>">
							<font class="note">（半角２０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">登録日時</td>
						<td class="n3"><%=ds("OP_regist_date").Value%></td>
					</tr>
					<tr>
						<td class="m3">最終更新日時</td>
						<td class="n3"><%=ds("OP_update_date").Value%></td>
					</tr>
				</table>
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
