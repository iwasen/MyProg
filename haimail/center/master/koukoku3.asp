<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�L���^�C�v�}�X�^�C��
'******************************************************

SetHeaderTitle "�L���^�C�v�}�X�^�C��"
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
			alert("�\����������͂��Ă��������B");
			order.focus();
			return false;
		}
		if (!num_chk(order.value)){
			alert("�\�������͔��p�̐��l�œ��͂��Ă��������B");
			order.focus();
			return(false);
		}
		if (koukoku_name.value == "") {
			alert("�L�����̂���͂��Ă��������B");
			koukoku_name.focus();
			return false;
		}
	}
	return confirm("�X�V���܂��B��낵���ł����H");
}
function OnClick_sakujo() {
 with (document.form1) {
		if (koukoku_id.value == "99") {
			alert("�폜�ł��܂���B");
			koukoku_id.focus();
			return false;
		}
	}
	if (confirm("���̍L���^�C�v���폜���Ă�낵���ł����H")) {
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
			<td class="m0">���L���^�C�v�}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�L���^�C�vID</td>
						<td class="n3"><%=ds("KT_koukoku_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="order" maxlength=3 value="<%=ds("KT_order").Value%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L������</td>
						<td class="n3">
							<input size=50 type="text" name="koukoku_name" maxlength=50 value="<%=HTMLEncode(ds("KT_name").Value)%>">
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �X�V ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
