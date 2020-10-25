<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:送信メールテンプレート修正画面
'******************************************************

SetHeaderTitle "送信メールテンプレート修正"
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
		if (subject.value == "") {
			alert("件名を入力してください。");
			subject.focus();
			return false;
		}
		if (from.value == "") {
			alert("送信元アドレスを入力してください。");
			from.focus();
			return false;
		}
		if (body.value == "") {
			alert("本文を入力してください。");
			body.focus();
			return false;
		}
	}
	return confirm("送信メールテンプレートを更新します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
Dim ds, sql, id

id = Request.QueryString("id")
If IsEmpty(id) Then
	SystemError "empty id"
End If

sql = "SELECT * FROM T_MAIL_TEMPLATE WHERE MT_id='" & id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<center>
<form method="post" action="mail_template3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="id" value="<%=ds("MT_id").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■メール情報 <font size="-1">（"%"で囲まれた部分はプログラムでデータを埋め込むので、変更しないでください）</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="20%">種類</td>
						<td class="n3"><%=ds("MT_guide").Value%></td>
					</tr>
					<tr>
						<td class="m3">件名</td>
						<td class="n3">
							<input type="text" name="subject" size=80 maxlength=100 value="<%=HTMLEncode(ds("MT_subject").Value)%>">
							<font class="note">（全角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">送信元アドレス</td>
						<td class="n3">
							<input type="text" name="from" size=80 maxlength=200 value="<%=HTMLEncode(ds("MT_from").Value)%>">
							<font class="note">（半角２００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">本文</td>
						<td class="n3"><textarea cols=80 rows=30 name="body" class="np"><%=ReadCLOB(ds("MT_body").Value)%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
