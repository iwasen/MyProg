<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="master_inc.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�悭�s���X�}�X�^�o�^
'******************************************************

SetHeaderTitle "�悭�s���X�}�X�^�o�^"
Response.CacheControl = "no-cache"

Dim  sql, ds, yokuiku_id, checked0, checked1, yokuiku, y_category, category_id

y_category = Request.QueryString("mg")
sql = "SELECT max(YM_item_no) FROM T_YOKUIKU_MISE WHERE YM_category='" & y_category & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
category_id = CInt(Int(ds(0)/10)*10) + 10

sql = "SELECT max(YM_index) FROM T_YOKUIKU_MISE"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
yokuiku_id = ds(0) + 1

If yokuiku_id < 1000 Then

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
<center>
<form method="post" action="yokuiku4.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<input type="hidden" name="yokuiku_id" value="<%=yokuiku_id%>">
	<input type="hidden" name="y_category" value="<%=y_category%>">
		<tr>
			<td class="m0">���悭�s���X�}�X�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�C���f�b�N�X�ԍ�</td>
						<td class="n3"><%=yokuiku_id%></td>
					</tr>
					<tr>
						<td class="m3">�J�e�S��</td>
						<td class="n3"><%=category_name(y_category)%></td>
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
							<input type="radio" name="yokuiku" value="1" checked>�L��
							<input type="radio" name="yokuiku" value="0">����
					</tr>

				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �o�^ ">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="location.href='yokuiku.asp'">
</form>
</center>
<!--#INCLUDE FILE="../inc/footer.asp"-->
</body>
</html>
<%
Else
	Response.Redirect("yokuiku.asp")
End If
%>