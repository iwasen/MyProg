<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�L���^�C�v�}�X�^�o�^
'******************************************************

SetHeaderTitle "�L���^�C�v�}�X�^�o�^"
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
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<%
	Dim sql, ds, koukoku_id

	sql = "SELECT max(KT_koukoku_id) FROM T_KOUKOKU_TYPE WHERE KT_koukoku_id<>'99'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	koukoku_id = NVL(ds(0), 0) + 1
%>

<center>
<form method="post" action="koukoku4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="koukoku_id" value=<%=koukoku_id%>>
		<tr>
			<td class="m0">���L���^�C�v�}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�L���^�C�vID</td>
						<td class="n3"><%=MakeID(koukoku_id, 2)%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="order" maxlength=3 value="<%=koukoku_id*10%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L������</td>
						<td class="n3">
							<input size=50 name="koukoku_name" maxlength=50>
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
