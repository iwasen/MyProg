<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:HTML�p�w�b�_�A���z���A�t�b�^�C�����
'******************************************************

Dim ds, sql, header, impression, footer

SetHeaderTitle "HTML�p�w�b�_�C�t�b�^�C���z���C��"

sql = "SELECT MH_text FROM T_MAIL_HEADER WHERE MH_header_id=2"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	header = ds(0).Value
End If

sql = "SELECT MI_text FROM T_MAIL_IMP WHERE MI_impression_id=2"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	impression = ds(0).Value
End If

sql = "SELECT MF_text FROM T_MAIL_FOOTER WHERE MF_footer_id=2"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	footer = ds(0).Value
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
function OnClick_update() {
	with (document.form1) {
		if (header.value == "") {
			alert("�w�b�_����͂��Ă��������B");
			header.focus();
			return false;
		}
		if (impression.value == "") {
			alert("���z������͂��Ă��������B");
			impression.focus();
			return false;
		}
		if (footer.value == "") {
			alert("�t�b�^����͂��Ă��������B");
			footervalue.focus();
			return false;
		}
		if (confirm("�w�b�_�^���z���^�t�b�^���X�V���܂��B��낵���ł����H")) {
			action = "header_update.asp";
			target = "";
			submit();
		}
	}
}
function OnClick_show() {
	with (document.form1) {
		action = "header_show.asp";
		target = "_blank";
		submit();
	}
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="header_update.asp" name="form1">
	<input type="hidden" name="id" value=2>
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">�����[���w�b�_�^���z���^�t�b�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="15%">�w�b�_</td>
						<td class="n3"><textarea cols=80 rows=4 name="header" class="np"><%=header%></textarea></td>
					</tr>
					<tr>
						<td class="m3">���z��</td>
						<td class="n3"><textarea cols=80 rows=30 name="impression" class="np"><%=HTMLEncode(impression)%></textarea></td>
					</tr>
					<tr>
						<td class="m3">�t�b�^</td>
						<td class="n3"><textarea cols=80 rows=12 name="footer" class="np"><%=footer%></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="button" value=" �X�V " onclick="OnClick_update()">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �\�� " onclick="OnClick_show()">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
