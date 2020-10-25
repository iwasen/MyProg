<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:媒体マスタ修正
'******************************************************

SetHeaderTitle "媒体マスタ修正"
Response.CacheControl = "no-cache"

Dim sql, ds, baitai_id
baitai_id = Request.QueryString("id")
If IsEmpty(baitai_id) Then
	SystemError "empty baitai_id"
End If

sql = "SELECT * FROM T_BAITAI WHERE BT_baitai_id='" & baitai_id & "'"
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
		if (baitai_name.value == "") {
			alert("媒体名称を入力してください。");
			baitai_name.focus();
			return false;
		}
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
 with (document.form1) {
		if (baitai_id.value == "99") {
			alert("削除できません。");
			baitai_id.focus();
			return false;
		}
	}
	if (confirm("この媒体を削除してよろしいですか？")) {
		location.href = "baitai5.asp?id=<%=baitai_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="baitai4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="baitai_id" value="<%=ds("BT_baitai_id").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■媒体マスタ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="28%">媒体ＩＤ</td>
						<td class="n3"><%=ds("BT_baitai_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">表示順序</td>
						<td class="n3">
							<input size=3 name="order" maxlength=3 value="<%=ds("BT_order").Value%>">
							<font class="note">（数字３桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">媒体名称</td>
						<td class="n3">
							<input size=50 type="text" name="baitai_name" maxlength=50 value="<%=HTMLEncode(ds("BT_name").Value)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">配信、メンバー検索指定</td>
						<td class="n3">
							<input type="checkbox" name="search_flag"<%ValueChecked "1", ds("BT_search_flag").Value%>>有り
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
