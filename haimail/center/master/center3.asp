<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:事務局マスタ修正
'******************************************************

'=== 権限選択 ===
Private Sub SelectPrivilege(privilege)
	Dim i

	For i = 1 To 9
		Writeln "<option value='" & i & "'" & Selected(CStr(i), privilege) & ">" & i & "</option>"
	Next
End Sub

'=== メイン処理 ===
SetHeaderTitle "事務局マスタ修正"
Response.CacheControl = "no-cache"

Dim sql, ds, user_id
user_id = Request.QueryString("id")
If IsEmpty(user_id) Then
	SystemError "empty user_id"
End If

sql = "SELECT * FROM T_CENTER WHERE CN_user_id='" & user_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	SystemError "not found user_id"
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
function OnSubmit_form1() {
 with (document.form1) {
		if (user_id.value == "") {
			alert("ユーザIDを入力してください。");
			user_id.focus();
			return false;
		}
		if (password.value == "") {
			alert("パスワードを入力してください。");
			password.focus();
			return false;
		}
		if (name.value == "") {
			alert("氏名を入力してください。");
			name.focus();
			return false;
		}
	}
	return confirm("登録します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("このユーザを削除してよろしいですか？")) {
		location.href = "center5.asp?id=<%=user_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="center4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■事務局マスタ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">ユーザID</td>
						<td class="n3">
							<input type="text" name="user_id" size=10 maxlength=10 value="<%=ds("CN_user_id").Value%>">
							<font class="note">（半角１０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">パスワード</td>
						<td class="n3">
							<input type="text" name="password" size=14 maxlength=14 value="<%=ds("CN_password").Value%>">
							<font class="note">（半角１４文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">氏名</td>
						<td class="n3">
							<input type="text" name="name" size=50 maxlength=25 value="<%=HTMLEncode(ds("CN_name").Value)%>">
							<font class="note">（全角２５文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">メールアドレス</td>
						<td class="n3">
							<input type="text" name="mail_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CN_mail_adr").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">権限</td>
						<td class="n3"><select name="privilege"><%SelectPrivilege ds("CN_privilege").Value%></select></td>
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
