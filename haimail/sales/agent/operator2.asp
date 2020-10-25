<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店担当者情報変更処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "代理店担当者情報変更"
'Response.CacheControl = "no-cache"

Dim sql, ds, operator_id

operator_id = Request.QueryString("id")
If IsEmpty(operator_id) Then
	SystemError "empty operator_id"
End If

sql = "SELECT *" & vbCrLf & _
			"FROM T_OPERATOR,T_DAIRITEN" & vbCrLf & _
			"WHERE OP_operator_id='" & operator_id & "' AND OP_dairiten_id=DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
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
function OnSubmit_form1() {
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
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("この担当者情報を削除してよろしいですか？")) {
		location.href = "operator4.asp?id=<%=operator_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="operator3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="operator_id" value="<%=operator_id%>">
		<tr>
			<td class="m0">
					<p><font color="#000000">代理店ご担当者様の基本情報変更を行います。変更後、更新ボタンを押して下さい。</font></p>
					<p>■代理店情報</p>
				</td>
		</tr>
		<tr>
			<td>

					<table border=0 cellspacing=2 cellpadding=3 width="100%">
						<tr>
						<td class="m3" width="25%">代理店ＩＤ</td>
						<td class="n3"><input type="hidden" name="dairiten_id" value="<%=ds("DA_dairiten_id").Value%>"><%=ds("DA_dairiten_id").Value%></td>
					</tr>
					<tr>

							<td class="m3" width="25%">代理店名</td>
						<td class="n3"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
		<tr>
			<td class="m0">■担当者情報</td>
		</tr>
		<tr>
			<td>

					<table border=0 cellspacing=2 cellpadding=3 width="100%">
						<tr>
							<td class="m3" width="25%">担当者ID</td>
							<td class="n3"><%=ds("OP_operator_id").Value%><font class="note">（必須：半角英数5文字以内）</font></td>
						</tr>
						<tr>
							<td class="m3" width="25%">パスワード</td>
							<td class="n3">
								<input size=20 name="password" maxlength=14 value="<%=ds("OP_password").Value%>">
								<font class="note">（必須：半角英数10文字以内）</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">担当者名</td>
							<td class="n3"> <font class="note">姓</font>
								<input size=20 name="name1" maxlength=10 value="<%=HTMLEncode(ds("OP_name1").Value)%>">
								<font class="note">名</font>
								<input size=20 name="name2" maxlength=10 value="<%=HTMLEncode(ds("OP_name2").Value)%>">
								<font class="note">（必須：全角）</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">担当者名（カナ）</td>
							<td class="n3"> <font class="note">姓</font>
								<input size=20 name="name1_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name1_kana").Value)%>">
								<font class="note">名</font>
								<input size=20 name="name2_kana" maxlength=20 value="<%=HTMLEncode(ds("OP_name2_kana").Value)%>">
								<font class="note">（必須：半角カナ）</font> </td>
						</tr>
						<tr>
							<td class="m3" width="25%">メールアドレス</td>
							<td class="n3">
								<input size=50 name="mail_adr" maxlength=50 value="<%=HTMLEncode(ds("OP_mail_adr").Value)%>">
								<font class="note">（必須：半角英数）</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">電話番号</td>
							<td class="n3">
								<input size=20 name="tel_no" maxlength=20 value="<%=ds("OP_tel_no").Value%>">
								<font class="note">（必須：半角英数）</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">FAX番号</td>
							<td class="n3">
								<input size=20 name="fax_no" maxlength=20 value="<%=ds("OP_fax_no").Value%>">
								<font class="note">（必須：半角英数）</font>
							</td>
						</tr>
						<tr>
							<td class="m3" width="25%">登録日時</td>
							<td class="n3"><%=ds("OP_regist_date").Value%></td>
						</tr>
						<tr>
							<td class="m3" width="25%">最終更新日時</td>
							<td class="n3"><%=ds("OP_update_date").Value%></td>
						</tr>
						<tr>
							<td width="25%">　</td>
							<td>　</td>
						</tr>
						<tr>
							<td colspan="2" class="np">削除ボタンを押されてしまいますと、過去に実施されたプロジェクトの閲覧が<b>全て不可</b>となります。ご注意下さい。
							</td>
						</tr>
					</table>
			</td>
		</tr>
	</table>
	<p>　</p>
	<p>
		<input type="submit" value=" 更新 ">
		<input type="button" value=" 削除 " onclick="OnClick_sakujo()">
		<input type="reset" value="リセット">
		<input type="button" value=" 戻る " onclick="history.back()">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
