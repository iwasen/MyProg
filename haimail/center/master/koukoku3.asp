<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:広告タイプマスタ修正
'******************************************************

SetHeaderTitle "広告タイプマスタ修正"
Response.CacheControl = "no-cache"

Dim sql, ds, koukoku_id
koukoku_id = Request.QueryString("id")
If IsEmpty(koukoku_id) Then
	SystemError "empty koukoku_id"
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
		if (order.value == "") {
			alert("表示順序を入力してください。");
			order.focus();
			return false;
		}
		if (!num_chk(order.value)){
			alert("表示順序は半角の数値で入力してください。");
			order.focus();
			return(false);
		}
		if (koukoku_name.value == "") {
			alert("広告名称を入力してください。");
			koukoku_name.focus();
			return false;
		}
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
 with (document.form1) {
		if (koukoku_id.value == "99") {
			alert("削除できません。");
			koukoku_id.focus();
			return false;
		}
	}
	if (confirm("この広告タイプを削除してよろしいですか？")) {
		location.href = "koukoku5.asp?id=<%=koukoku_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<%
sql = "SELECT * FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id='" & koukoku_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="koukoku4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="koukoku_id" value="<%=ds("KT_koukoku_id").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■広告タイプマスタ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">広告タイプID</td>
						<td class="n3"><%=ds("KT_koukoku_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">表示順序</td>
						<td class="n3">
							<input size=3 name="order" maxlength=3 value="<%=ds("KT_order").Value%>">
							<font class="note">（数字３桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">広告名称</td>
						<td class="n3">
							<input size=50 type="text" name="koukoku_name" maxlength=50 value="<%=HTMLEncode(ds("KT_name").Value)%>">
							<font class="note">（全角５０文字まで）</font>
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
