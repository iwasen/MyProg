<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:クレーム管理／キーワード修正
'******************************************************

'=== メイン処理 ===
Dim sql, ds, keyword_id

SetHeaderTitle "クレームキーワード修正"
Response.CacheControl = "no-cache"

keyword_id = Request.QueryString("keyword_id")
If IsEmpty(keyword_id) Then
	SystemError "empty keyword_id"
End If

sql = "SELECT * FROM T_CLAIM_KEYWORD WHERE CK_keyword_id='" & keyword_id & "'"
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
function OnSubmit_form1() {
 with (document.form1) {
		if (keyword.value == "") {
			alert("キーワードを入力してください。");
			keyword.focus();
			return false;
		}
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("このキーワードを削除してよろしいですか？")) {
		location.href = "keyword5.asp?keyword_id=<%=keyword_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="keyword4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="keyword_id" value="<%=ds("CK_keyword_id").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■クレームキーワード情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">ＩＤ</td>
						<td class="n3"><%=ds("CK_keyword_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">キーワード</td>
						<td class="n3">
							<input type="text" name="keyword" size=80 maxlength=100 value="<%=HTMLEncode(ds("CK_keyword").Value)%>">
							<font class="note">（全角１００文字まで）</font>
						</td>
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
