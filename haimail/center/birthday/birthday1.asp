<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�a�������[���o�^��ʏ���
'******************************************************

Dim ds, sql, seq_no, priority, seibetsu, age_from, age_to, from_adr, subject, body, valid_flag

SetHeaderTitle "�a�������[���o�^"

valid_flag = "1"

seq_no = Request.QueryString("id")
If Not IsEmpty(seq_no) Then
	sql = "SELECT *" & vbCrLf & _
				"FROM T_BIRTHDAY_MAIL" & vbCrLf & _
				"WHERE BM_seq_no=" & seq_no
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		priority = ds("BM_priority").Value
		seibetsu = NVL(ds("BM_seibetsu").Value, "")
		age_from = NVL(ds("BM_age_from").Value, "")
		age_to = NVL(ds("BM_age_to").Value, "")
		from_adr = ds("BM_from_adr").Value
		subject = ds("BM_subject").Value
		body = ReadCLOB(ds("BM_body").Value)
		valid_flag = ds("BM_valid_flag").Value
	End If
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
function OnSubmit_form1(c) {
	if (c.priority.value == "") {
		alert("�D�揇�ʂ���͂��Ă��������B");
		c.priority.focus();
		return false;
	}
	if (c.from_adr.value == "") {
		alert("���M�҃A�h���X����͂��Ă��������B");
		c.from_adr.focus();
		return false;
	}
	if (c.subject.value == "") {
		alert("��������͂��Ă��������B");
		c.subject.focus();
		return false;
	}
	if (c.body.value == "") {
		alert("�{������͂��Ă��������B");
		c.body.focus();
		return false;
	}
	return true;
}
function OnClick_sakujo() {
	if (confirm("���̃f�[�^���폜���܂����H"))
		location.href = "birthday3.asp?id=<%=seq_no%>";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="birthday2.asp" name="form1" onSubmit="return OnSubmit_form1(this)">
<%If Not IsEmpty(seq_no) Then%>
	<input type="hidden" name="seq_no" value="<%=seq_no%>">
<%End If%>
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">���a�������[�����</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3">�D�揇��</td>
						<td class="n3">
							<input type="text" name="priority" size=5 value="<%=priority%>">
							<font class="note">�i1����999�܂ł̐��l����͂��Ă��������j</font>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3">
							<input type="radio" name="seibetsu"<%ValueChecked "", seibetsu%>>�w�薳��
							<input type="radio" name="seibetsu"<%ValueChecked "0", seibetsu%>>�j��
							<input type="radio" name="seibetsu"<%ValueChecked "1", seibetsu%>>����
						</td>
					</tr>
					<tr>
						<td class="m3">�N��</td>
						<td class="n3">
							<input type="text" name="age_from" size=5 value="<%=age_from%>">�ˁ`
							<input type="text" name="age_to" size=5 value="<%=age_to%>">�˂܂�
							<font class="note">�i�N����w�肵�Ȃ��ꍇ�̓u�����N�ɂ��Ă��������j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���M�҃A�h���X</td>
						<td class="n3">
							<input type="text" name="from_adr" size=50 maxlength=50 value="<%=HTMLEncode(from_adr)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
							</td>
					</tr>
					<tr>
						<td class="m3">����</td>
						<td class="n3">
							<input type="text" name="subject" size=80 maxlength=50 value="<%=HTMLEncode(subject)%>">
							<font class="note">�i�S�p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�{��</td>
						<td class="n3">
							<textarea cols=80 rows=30 name="body"><%=body%></textarea><br>
							<font class="note">�i�N��� %AGE%�C�|�C���g�� %POINT% �Ɠ��͂��Ă��������j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�L���t���O</td>
						<td class="n3">
							<input type="radio" name="valid_flag"<%ValueChecked "1", valid_flag%>>�L��
							<input type="radio" name="valid_flag"<%ValueChecked "0", valid_flag%>>����
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
<%If IsEmpty(seq_no) Then%>
	<input type="submit" value=" �o�^ ">
<%Else%>
	<input type="submit" value=" �X�V ">
	<input type="button" value=" �폜 " onclick="OnClick_sakujo()">
<%End If%>
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
