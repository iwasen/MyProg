<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�R���g���[���}�X�^�C�����
'******************************************************

SetHeaderTitle "�R���g���[���}�X�^"

Dim sql, ds

sql = "SELECT * FROM T_CONTROL WHERE CT_control_id='0'"
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
function OnSubmit_form1() {
	return confirm("�R���g���[���}�X�^���X�V���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="control2.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">�����[���T�[�o���</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">���C��SMTP�T�[�o��</td>
						<td class="n3">
							<input type="text" name="smtp_server1" size=50 maxlength=50 value="<%=ds("CT_smtp_server1").Value%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�o�b�N�A�b�vSMTP�T�[�o��</td>
						<td class="n3">
							<input type="text" name="smtp_server2" size=50 maxlength=50 value="<%=ds("CT_smtp_server2").Value%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���C��POP3�T�[�o��</td>
						<td class="n3">
							<input type="text" name="pop3_server" size=50 maxlength=50 value="<%=ds("CT_pop3_server").Value%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">�����[���A�J�E���g���</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">���o�^���O�C�����[�U��</td>
						<td class="n3">
							<input type="text" name="pop3_regist_user" size=50 maxlength=50 value="<%=ds("CT_pop3_regist_user").Value%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���o�^���O�C���p�X���[�h</td>
						<td class="n3">
							<input type="password" name="pop3_regist_pass" size=50 maxlength=50 value="<%=ds("CT_pop3_regist_pass").Value%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">�����������ݒ���</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">�����������s�C���^�[�o��</td>
						<td class="n3">
							<input type="text" name="auto_interval" maxlength=4 value="<%=ds("CT_auto_interval").Value%>"> �b
							<font class="note">�i�����S���܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">���O�ۑ�����</td>
						<td class="n3">
							<input type="text" name="log_limit" maxlength=4 value="<%=ds("CT_log_limit").Value%>"> ��
							<font class="note">�i�����S���܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">���e��ʒm�惁�[���A�h���X</td>
		</tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">���M���[������ʒm��A�h���X</td>
						<td class="n3">
							<input type="text" name="sokuhou_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_sokuhou_adr").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�z�M�\��ʒm��A�h���X</td>
						<td class="n3">
							<input type="text" name="reserve_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_reserve_adr").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�z�M�󋵒ʒm��A�h���X</td>
						<td class="n3">
							<input type="text" name="send_report_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_send_report_adr").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
				</table>
			</td>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">���s�B���[���ݒ�</td>
		</tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">�s�B���[����M�A�J�E���g</td>
						<td class="n3">
							<input type="text" name="ud_user" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_ud_user").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�s�B���[����M�p�X���[�h</td>
						<td class="n3">
							<input type="password" name="ud_pass" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_ud_pass").Value)%>">
							<font class="note">�i���p�T�O�����܂Łj</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�����폜�J�E���^</td>
						<td class="n3">
							<input type="text" name="ud_count" size=2 maxlength=4 value="<%=HTMLEncode(ds("CT_ud_count").Value)%>">��
							<font class="note">�i0�͎����폜�����j</font>
						</td>
					</tr>
				</table>
			</td>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">�����̑��ݒ�</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">�������o�^�G���[���[������</td>
						<td class="n3">
							<input type="radio" name="error_mail" value="0"<%=RadioChecked(ds("CT_error_mail").Value, "0")%>>�����Ǐ���&nbsp;
							<input type="radio" name="error_mail" value="1"<%=RadioChecked(ds("CT_error_mail").Value, "1")%>>�����ԐM
							</td>
					</tr>
					<tr>
						<td class="m3">���{�o�^�҂ւ̍Ñ����[��</td>
						<td class="n3">
							<input type="text" name="reminder" size=2 maxlength=2 value="<%=ds("CT_reminder").Value%>">����
							<font class="note">�i0�͑��M�����j</font>
						</td>
					</tr>
					<tr>
						<td class="m3">�A���P�[�g���񓚃��[������</td>
						<td class="n3">
							<input type="radio" name="enq_error" value="0"<%=RadioChecked(ds("CT_enq_error").Value, "0")%>>�������Ȃ�&nbsp;
							<input type="radio" name="enq_error" value="1"<%=RadioChecked(ds("CT_enq_error").Value, "1")%>>�t�H���[���[�������ԐM
							</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" �X�V " name="submit1">
	<input type="reset" value="���Z�b�g">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
