<!--#INCLUDE FILE="inc/common.asp"-->
<!--#INCLUDE FILE="inc/database.asp"-->
<!--#INCLUDE FILE="inc/sub.asp"-->
<!--#INCLUDE FILE="inc/login.asp"-->
<!--#INCLUDE FILE="inc/point.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�|�C���g�ǉ�����
'******************************************************

Dim sql, ds
Dim member_id, point_type, point, message

member_id = Request.Form("member_id")
point_type = Request.Form("point_type")
point = Request.Form("point")

If Not IsEmpty(member_id) Then
	member_id = Right(String(10, "0") & member_id, 10)
	sql = "SELECT * from T_MEMBER WHERE MM_member_id='" & member_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		AddPoint member_id, point_type, CLng(point), Null
		message = "���ID[" & member_id & "]�� " & point & " �|�C���g��t�^���܂����B"
	Else
		message = "���ID[" & member_id & "]�͑��݂��܂���B"
	End If
Else
	message = ""
End If

SetHeaderTitle "�|�C���g�t�^�i�b��Łj"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	with (document.form1) {
		if (member_id.value == "") {
			alert("���ID����͂��Ă��������B");
			member_id.focus();
			return false;
		}
		if (point_type.value == "") {
			alert("�|�C���g��ނ�I�����Ă��������B");
			point_type.focus();
			return false;
		}
		if (point.value == "") {
			alert("�|�C���g����I�����Ă��������B");
			point.focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="inc/header.asp"-->

<center>
<%If message = "" Then%>
<form method="post" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���|�C���g�t�^���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">���ID</td>
						<td class="n3"><input type="text" name="member_id"></td>
					</tr>
					<tr>
						<td class="m3">�|�C���g���</td>
						<td class="n3">
							<select name="point_type">
								<option value="">- �I�����Ă������� -</option>
								<option value="01">��Љ�|�C���g(01)</option>
								<option value="02">���߂�Ȃ����|�C���g(02)</option>
								<option value="03">�Љ�|�C���g(03)</option>
								<option value="04">�l�A�t�B���G�C�g�|�C���g(04)</option>
								<option value="10">��M�|�C���g(10)</option>
								<option value="11">�A���P�[�g�|�C���g(11)</option>
								<option value="12">���z���|�C���g(12)</option>
								<option value="12">�蓮���Z�|�C���g(13)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="m3">�|�C���g��</td>
						<td class="n3"><input type="text" name="point"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �t�^ ">
	<input type="reset" value="���Z�b�g">
</form>
<%Else%>
<form>
	<p><%=message%></p>
	<input type="button" value="�@�߂�@" onclick="location.href='addpoint.asp'">
</form>
<%End If%>
</center>

<!--#INCLUDE FILE="inc/footer.asp"-->

</body>
</html>
