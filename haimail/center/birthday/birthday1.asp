<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:誕生日メール登録画面処理
'******************************************************

Dim ds, sql, seq_no, priority, seibetsu, age_from, age_to, from_adr, subject, body, valid_flag

SetHeaderTitle "誕生日メール登録"

valid_flag = "1"

seq_no = Request.QueryString("id")
If Not IsEmpty(seq_no) Then
	sql = "SELECT *" & vbCrLf & _
				"FROM T_BIRTHDAY_MAIL" & vbCrLf & _
				"WHERE BM_seq_no=" & seq_no
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		priority = ds("BM_priority").Value
		seibetsu = NVL(ds("BM_seibetsu").Value, "")
		age_from = NVL(ds("BM_age_from").Value, "")
		age_to = NVL(ds("BM_age_to").Value, "")
		from_adr = ds("BM_from_adr").Value
		subject = ds("BM_subject").Value
		body = ReadCLOB(ds("BM_body").Value)
		valid_flag = ds("BM_valid_flag").Value
	End If
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
function OnSubmit_form1(c) {
	if (c.priority.value == "") {
		alert("優先順位を入力してください。");
		c.priority.focus();
		return false;
	}
	if (c.from_adr.value == "") {
		alert("送信者アドレスを入力してください。");
		c.from_adr.focus();
		return false;
	}
	if (c.subject.value == "") {
		alert("件名を入力してください。");
		c.subject.focus();
		return false;
	}
	if (c.body.value == "") {
		alert("本文を入力してください。");
		c.body.focus();
		return false;
	}
	return true;
}
function OnClick_sakujo() {
	if (confirm("このデータを削除しますか？"))
		location.href = "birthday3.asp?id=<%=seq_no%>";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="birthday2.asp" name="form1" onSubmit="return OnSubmit_form1(this)">
<%If Not IsEmpty(seq_no) Then%>
	<input type="hidden" name="seq_no" value="<%=seq_no%>">
<%End If%>
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■誕生日メール情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">優先順位</td>
						<td class="n3">
							<input type="text" name="priority" size=5 value="<%=priority%>">
							<font class="note">（1から999までの数値を入力してください）</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">性別</td>
						<td class="n3">
							<input type="radio" name="seibetsu"<%ValueChecked "", seibetsu%>>指定無し
							<input type="radio" name="seibetsu"<%ValueChecked "0", seibetsu%>>男性
							<input type="radio" name="seibetsu"<%ValueChecked "1", seibetsu%>>女性
						</td>
					</tr>
					<tr>
						<td class="m3">年齢</td>
						<td class="n3">
							<input type="text" name="age_from" size=5 value="<%=age_from%>">才～
							<input type="text" name="age_to" size=5 value="<%=age_to%>">才まで
							<font class="note">（年齢を指定しない場合はブランクにしてください）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">送信者アドレス</td>
						<td class="n3">
							<input type="text" name="from_adr" size=50 maxlength=50 value="<%=HTMLEncode(from_adr)%>">
							<font class="note">（半角５０文字まで）</font>
							</td>
					</tr>
					<tr>
						<td class="m3">件名</td>
						<td class="n3">
							<input type="text" name="subject" size=80 maxlength=50 value="<%=HTMLEncode(subject)%>">
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">本文</td>
						<td class="n3">
							<textarea cols=80 rows=30 name="body"><%=body%></textarea><br>
							<font class="note">（年齢は %AGE%，ポイントは %POINT% と入力してください）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">有効フラグ</td>
						<td class="n3">
							<input type="radio" name="valid_flag"<%ValueChecked "1", valid_flag%>>有効
							<input type="radio" name="valid_flag"<%ValueChecked "0", valid_flag%>>無効
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
<%If IsEmpty(seq_no) Then%>
	<input type="submit" value=" 登録 ">
<%Else%>
	<input type="submit" value=" 更新 ">
	<input type="button" value=" 削除 " onclick="OnClick_sakujo()">
<%End If%>
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
