<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:職業マスタ登録
'******************************************************

SetHeaderTitle "職業マスタ登録"
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
		if (seq_no.value == "") {
			alert("表示順序を入力してください。");
			seq_no.focus();
			return false;
		}
		if (!num_chk(seq_no.value)){
			alert("表示順序は半角の数値で入力してください。");
			seq_no.focus();
			return(false);
		}
		if (shokugyou_name.value == "") {
			alert("職業名称を入力してください。");
			shokugyou_name.focus();
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

<%
Dim sql, ds, shokugyou_id

sql = "SELECT max(SG_shokugyou_code) FROM T_SHOKUGYOU"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
shokugyou_id = NVL(ds(0), 0) + 1

If shokugyou_id < 100 Then
%>

<center>
<form method="post" action="shokugyou4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<input type="hidden" name="shokugyou_id" value=<%=shokugyou_id%>>
		<tr>
			<td class="m0">■職業マスタ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">職業コード</td>
						<td class="n3"><%=MakeID(shokugyou_id, 2)%></td>
					</tr>
					<tr>
						<td class="m3">表示順序</td>
						<td class="n3">
							<input size=3 name="seq_no" maxlength=3 value="<%=shokugyou_id*10%>">
							<font class="note">（数字３桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">職業名称</td>
						<td class="n3">
							<input size=50 name="shokugyou_name" maxlength=50>
							<font class="note">（全角５０文字まで）</font>
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
<%
Else
	Response.Redirect("shokugyou.asp")
End If
%>