<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�N���b�N�����C���|�[�g
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�N���b�N�����C���|�[�g"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onsubmit_form1(f) {
	if (f.click_log.value == "") {
		alert("�N���b�N�����t�@�C������͂��Ă��������B");
		f.click_log.focus();
		return false;
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="cc_imp2.asp" name="form1" ENCtype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='75%'>
		<tr>
			<td class="m0">���N���b�N�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�N���b�N�����t�@�C��</td>
						<td class="n3">
							<input type="file" name="click_log" size=60>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	<input type="submit" name="regist" value=" �o�^ ">
	<input type="button" value=" �߂� " onclick="history.back()">
	<input type="hidden" name="action">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
