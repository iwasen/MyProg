<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����W�������}�X�^�C��
'******************************************************

SetHeaderTitle "�����W�������}�X�^�C��"
Response.CacheControl = "no-cache"

Dim sql, ds, kyoumi_id, id, valid_flag

kyoumi_id = Request.QueryString("id")

sql = "SELECT * FROM T_KYOUMI_GENRE WHERE KG_index='" & kyoumi_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)

Session("k_category") = ds("KG_category").Value
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
		if (item_no.value == "") {
			alert("�\����������͂��Ă��������B");
			item_no.focus();
			return false;
		}
		if (!num_chk(item_no.value)){
			alert("�\�������͔��p�̐��l�œ��͂��Ă��������B");
			item_no.focus();
			return(false);
		}
		if (item_text.value == "") {
			alert("���ڃe�L�X�g����͂��Ă��������B");
			item_text.focus();
			return false;
		}
	}
	return confirm("�X�V���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<form method="post" action="kyoumi4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="kyoumi_id" value="<%=ds("KG_index").Value%>">
	<input type="hidden" name="k_category" value="<%=ds("KG_category").Value%>">
		<tr>
			<td class="m0">�������W�������}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�C���f�b�N�X�ԍ�</td>
						<td class="n3"><%=ds("KG_index").Value%></td>
					</tr>
					<tr>
						<td class="m3">�J�e�S��</td>
						<td class="n3"><%=ds("KG_category").Value%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input type="text" name="item_no" size=3 maxlength=3 value="<%=ds("KG_item_no").Value%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���ڃe�L�X�g</td>
						<td class="n3">
							<input type="text" name="item_text" size=50 maxlength=25 value="<%=HTMLEncode(ds("KG_item_text").Value)%>">
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L���t���O</td>
						<td class="n3">
							<input type="radio" name="kyoumi"<%ValueChecked "1", ds("KG_valid_flag").Value%>>�L��
							<input type="radio" name="kyoumi"<%ValueChecked "0", ds("KG_valid_flag").Value%>>����
					</tr>
				</table>
			</td>
		</tr>
	</table>
<br>
	<input type="submit" value=" �X�V ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="location.href='kyoumi.asp'">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>