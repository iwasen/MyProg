<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�ǂ����h�}�X�^�o�^
'******************************************************

SetHeaderTitle "�ǂ����h�}�X�^�o�^"
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
		if (item1_text.value == "") {
			alert("���ڃe�L�X�g�P����͂��Ă��������B");
			item1_text.focus();
			return false;
		}
		if (item2_text.value == "") {
			alert("���ڃe�L�X�g�Q����͂��Ă��������B");
			item2_text.focus();
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
Dim sql, ds, docchi_id

sql = "SELECT max(DH_index) FROM T_DOCCHI_HA"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
docchi_id = ds(0) + 1

If docchi_id < 1000 Then
%>
<center>
<form method="post" action="docchi4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="docchi_id" value=<%=docchi_id%>>
		<tr>
			<td class="m0">���ǂ����h�}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�C���f�b�N�X�ԍ�</td>
						<td class="n3"><%=docchi_id%></td>
					</tr>
					<tr>
						<td class="m3">�\������</td>
						<td class="n3">
							<input size=3 name="item_no" maxlength=3 value="<%=NumFormat(docchi_id*10)%>">
							<font class="note">�i�����R���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���ڃe�L�X�g�P</td>
						<td class="n3">
							<input name="item1_text" size=50 maxlength=25>
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���ڃe�L�X�g�Q</td>
						<td class="n3">
							<input name="item2_text" size=50 maxlength=25>
							<font class="note">�i�S�p�Q�T�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L���t���O</td>
						<td class="n3">
							<input type="radio" name="docchiha" value="1" checked>�L��
							<input type="radio" name="docchiha" value="0">����
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
	Response.Redirect("docchi.asp")
End If
%>