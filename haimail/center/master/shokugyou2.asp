<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�E�ƃ}�X�^�o�^
'******************************************************

SetHeaderTitle "�E�ƃ}�X�^�o�^"
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
	return confirm("�o�^���܂��B��낵���ł����H");
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
			<td class="m0">���E�ƃ}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">�E�ƃR�[�h</td>
						<td class="n3"><%=MakeID(shokugyou_id, 2)%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="seq_no" maxlength=3 value="<%=shokugyou_id*10%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�E�Ɩ���</td>
						<td class="n3">
							<input size=50 name="shokugyou_name" maxlength=50>
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

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