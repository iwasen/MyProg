<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�E�ƃ}�X�^�C��
'******************************************************

SetHeaderTitle "�E�ƃ}�X�^�C��"
Response.CacheControl = "no-cache"

Dim sql, ds, shokugyou_id
shokugyou_id = Request.QueryString("id")
If IsEmpty(shokugyou_id) Then
	SystemError "empty shokugyou_id"
End If

sql = "SELECT * FROM T_SHOKUGYOU WHERE SG_shokugyou_code='" & shokugyou_id & "'"
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
		if (seq_no.value == "") {
			alert("�\����������͂��Ă��������B");
			seq_no.focus();
			return false;
		}
		if (!num_chk(seq_no.value)){
			alert("�\�������͔��p�̐��l�œ��͂��Ă��������B");
			seq_no.focus();
			return(false);
		}
		if (shokugyou_name.value == "") {
			alert("�E�Ɩ��̂���͂��Ă��������B");
			shokugyou_name.focus();
			return false;
		}
	}
	return confirm("�X�V���܂��B��낵���ł����H");
}
function OnClick_sakujo() {
	if (confirm("���̐E�Ƃ��폜���Ă�낵���ł����H")) {
		location.href = "shokugyou5.asp?id=<%=shokugyou_id%>";
	}
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="shokugyou4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<input type="hidden" name="shokugyou_id" value="<%=ds("SG_shokugyou_code").Value%>">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���E�ƃ}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">�E�ƃR�[�h</td>
						<td class="n3"><%=ds("SG_shokugyou_code").Value%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="seq_no" maxlength=3 value="<%=ds("SG_seq_no").Value%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�E�Ɩ���</td>
						<td class="n3">
							<input size=50 type="text" name="shokugyou_name" maxlength=50 value="<%=ds("SG_shokugyou_name").Value%>">
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
