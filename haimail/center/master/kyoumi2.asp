<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�����W�������}�X�^�o�^
'******************************************************

SetHeaderTitle "�����W�������}�X�^�o�^"
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
	return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>
<!--#INCLUDE FILE="../inc/header.asp"-->
<%
Dim sql, ds, kyoumi_id, k_category, category_id

k_category = Request.QueryString("cg")

sql = "SELECT max(KG_item_no) FROM T_KYOUMI_GENRE WHERE KG_category='" & k_category & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
category_id = CInt(Int(ds(0)/10)*10) + 10

sql = "SELECT max(KG_index) FROM T_KYOUMI_GENRE"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
kyoumi_id = ds(0) + 1

If kyoumi_id < 1000 Then

%>
<center>
<form method="post" action="kyoumi4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="kyoumi_id" value="<%=kyoumi_id%>">
	<input type="hidden" name="k_category" value="<%=k_category%>">
		<tr>
			<td class="m0">�������W�������}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�C���f�b�N�X�ԍ�</td>
						<td class="n3"><%=kyoumi_id%></td>
					</tr>
					<tr>
						<td class="m3">�J�e�S��</td>
						<td class="n3">	<%=k_category%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="item_no" maxlength=3 value="<%=category_id%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���ڃe�L�X�g</td>
						<td class="n3">
							<input name="item_text" size=50 maxlength=25>
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L���t���O</td>
						<td class="n3">
							<input type="radio" name="kyoumi" value="1" checked>�L��
							<input type="radio" name="kyoumi" value="0">����
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="location.href='kyoumi.asp'">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
<%

Else
	Response.Redirect("kyoumi.asp")
End If
%>