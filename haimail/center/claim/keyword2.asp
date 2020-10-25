<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／キーワード新規登録
'******************************************************

'=== メイン処理 ===
Dim sql, ds, keyword_id

SetHeaderTitle "クレームキーワード登録"
Response.CacheControl = "no-cache"

sql = "SELECT max(CK_keyword_id) FROM T_CLAIM_KEYWORD"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
keyword_id = NVL(ds(0), 0) + 1
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
		if (keyword.value == "") {
			alert("キーワードを入力してください。");
			keyword.focus();
			return false;
		}
	}
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onload="document.form1.keyword.focus()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="keyword4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="keyword_id" value=<%=keyword_id%>>
		<tr>
			<td class="m0">■媒体マスタ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">ID</td>
						<td class="n3"><%=keyword_id%></td>
					</tr>
					<tr>
						<td class="m3">キーワード</td>
						<td class="n3">
							<input type="text" name="keyword" size=80 maxlength=100>
							<font class="note">（全角１００文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 登録 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
