<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:新規企業登録処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "新規企業登録"
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
		if (name.value == "") {
			alert("企業名を入力してください。");
			name.focus();
			return false;
		}
		if (name1.value == "") {
			alert("担当者名を入力してください。");
			name1.focus();
			return false;
		}
		if (tantousha_id.value == "") {
			alert("担当者IDを入力してください。");
			tantousha_id.focus();
			return false;
		}
		if (!isA(tantousha_id.value)) {
			alert("担当者IDは半角英数で入力してください。");
			tantousha_id.focus();
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
	}
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="regist_client2.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■企業情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">企業名　※１</td>
						<td class="n3"><input size=50 name="name" maxlength=100></td>
					</tr>
					<tr>
						<td class="m3">担当者名　※２</td>
						<td class="n3">
							<font class="note">姓</font><input size=20 name="name1" maxlength=10>
							<font class="note">名</font><input size=20 name="name2" maxlength=10>
						</td>
					</tr>
					<tr>
						<td class="m3">担当者ID</td>
						<td class="n3"><input size=20 name="tantousha_id" maxlength=10> <font class="note">（半角英数6文字以内）</font></td>
					</tr>
					<tr>
						<td class="m3">パスワード</td>
						<td class="n3"><input size=20 name="password" maxlength=14> <font class="note">（半角英数10文字以内）</font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value="　登録　">
	<input type="button" value="キャンセル" onclick="window.close()">
</form>
  <p>　</p>
    <table width="800" border="3" cellspacing="0" cellpadding="3" bordercolor="cd853f">
      <tr>
        <td>
          <table border="0" cellspacing="5" cellpadding="0" width="100%">
			<tr> 
				<td nowrap valign="top" class="info"> 
				<div align="right"><font color="336699">※１</font></div>
				</td>
				<td valign="top" class="info">メールのヘッダに発信企業名として表示されます。正確にご記入下さい。</td>
				</tr>
			<tr>
				<td nowrap valign="top" class="info"> 
				<div align="right"><font color="336699">※２</font></div>
				</td>
				<td valign="top" class="info">ご担当者様名は必須事項となっております。</td>
			</tr>
          </table>
        </td>
      </tr>
    </table>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
