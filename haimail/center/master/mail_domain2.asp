<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:Web���[���h���C���}�X�^�o�^
'******************************************************

SetHeaderTitle "Web���[���h���C���}�X�^�o�^"
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
		if (domain_name.value == "") {
			alert("�h���C��������͂��Ă��������B");
			domain_name.focus();
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
	Dim sql, ds, domain_id

	sql = "SELECT max(MD_domain_id) FROM T_MAIL_DOMAIN"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	domain_id = NVL(ds(0), 0) + 1
%>

<center>
<form method="post" action="mail_domain4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="domain_id" value=<%=domain_id%>>
		<tr>
			<td class="m0">��Web���[���h���C���}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�h���C��ID</td>
						<td class="n3"><%=MakeID(domain_id, 2)%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="order" maxlength=3 value="<%=domain_id*10%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�h���C����</td>
						<td class="n3">
							<input size=50 name="domain_name" maxlength=50>
							<font class="note">�i���p�P�O�O�����܂Łj</font>
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
