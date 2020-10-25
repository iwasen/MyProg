<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ƌ��ʃy�[�W
' Content:�ڍ׏��o�^��ʏ���
'******************************************************

Const SEPARATOR1 = "$$1$$"
Const SEPARATOR2 = "$$2$$"

'=== ���M��ƑI���� ===
Private Function CompanyList()
	Dim sql, ds, list

	sql = "SELECT CM_company_id,CM_name" & vbCrLf & _
				"FROM T_COMPANY,T_OPERATOR" & vbCrLf & _
				"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id" & vbCrLf & _
				"ORDER BY CM_company_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		list = list & ds("CM_company_id").Value & SEPARATOR1 & ds("CM_name").Value & SEPARATOR2
		ds.MoveNext
	Loop

	CompanyList = HTMLEncode(list)
End Function

'=== �S���҃��X�g�o�� ===
Private Function TantoushaList()
	Dim sql, ds, list, name

	sql = "SELECT TS_company_id,TS_tantousha_id,TS_name1,TS_name2" & vbCrLf & _
				"FROM T_TANTOUSHA,T_COMPANY,T_OPERATOR" & vbCrLf & _
				"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id AND TS_company_id=CM_company_id" & vbCrLf & _
				"ORDER BY TS_company_id,TS_tantousha_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If IsNull(ds("TS_name1").Value) Then
			name = ds("TS_tantousha_id").Value
		Else
			name = ds("TS_name1").Value & " " & ds("TS_name2").Value
		End If

		list = list & ds("TS_company_id").Value & SEPARATOR1 & ds("TS_tantousha_id").Value & SEPARATOR1 & name & SEPARATOR2
		ds.MoveNext
	Loop

	TantoushaList = HTMLEncode(list)
End Function

'=== ��Ɩ��A�S���Җ��擾 ===
Private Sub GetCompanyTantousha()
	Dim sql, ds

	sql = "SELECT CM_name,TS_name1,TS_name2" & vbCrLf & _
				"FROM T_COMPANY,T_TANTOUSHA" & vbCrLf & _
				"WHERE TS_tantousha_id='" & g_login_id & "' AND CM_company_id=TS_company_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		company_name = ds("CM_name").Value
		tantousha_name = ds("TS_name1").Value & " " & ds("TS_name2").Value
	End If
End Sub

'=== ���C������ ===
Dim send_num, mail_format, mail_type, search, company_name, tantousha_name

send_num = Request.Form("send_num")
mail_format = Request.Form("mail_format")
mail_type = Request.Form("mail_type")
Set search = New CSearch
search.ReadCondition

If g_login_type = LOGIN_CLIENT Then
	GetCompanyTantousha
End If

SetHeaderTitle "�ڍ׏��o�^"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
<%If g_login_type = LOGIN_AGENT Then%>
	SetCompanyList(document.form1.company_sel.value);
	SetTantoushaList(document.form1.tantousha_sel.value);
<%End If%>
}
function OnSubmit_form1(f) {
	if (f.start_date_y.value == "") {
		alert("���M������͂��Ă��������B");
		f.start_date_y.focus();
		return false;
	}
<%If g_login_type = LOGIN_AGENT Then%>
	if (f.company_id.value == "") {
		alert("���M��Ɩ���I�����Ă��������B");
		f.company_id.focus();
		return false;
	}
	if (f.tantousha_id.value == "") {
		alert("�S���Җ���I�����Ă��������B");
		f.tantousha_id.focus();
		return false;
	}
<%End If%>
	if (f.job_name.value == "") {
		alert("�W���u������͂��Ă��������B");
		f.job_name.focus();
		return false;
	}
	if (f.subject.value == "") {
		alert("��������͂��Ă��������B");
		f.subject.focus();
		return false;
	}
	if (f.subject.value == "�i�O�O�j�I") {
		alert("��������͂��Ă��������B");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("�{������͂��Ă��������B");
		f.body.focus();
		return false;
	}
	if (!f.click_counter[0].checked && !f.click_counter[1].checked) {
		alert("�N���b�N�J�E���^�̗L����I�����Ă��������B");
		f.click_counter[0].focus();
		return false;
	}
<%If g_login_type = LOGIN_AGENT Then%>
	f.company_sel.value = f.company_id.value;
	f.tantousha_sel.value = f.tantousha_id.value;
<%End If%>
	return confirm("�z�M�\����s���܂��B��낵���ł����H");
}
function OnClick_cancel() {
	if (confirm("�z�M�\����L�����Z�����܂��B��낵���ł����H"))
		location.href = "../top/index.asp";
}
<%If g_login_type = LOGIN_AGENT Then%>
function OnChange_company_id() {
	SetTantoushaList("");
}
function SetCompanyList(def) {
	var txt, arry1, arry2, i, sel = 0;
	txt = "<select name='company_id' onchange='OnChange_company_id()'><option value=''>- �I�����Ă������� -</option>\n";
	arry1 = document.form1.company_list.value.split("<%=SEPARATOR2%>");
	for (i = 0; i < arry1.length; i++) {
		if (arry1[i] != "") {
			arry2 = arry1[i].split("<%=SEPARATOR1%>");
			txt += "<option value='" + arry2[0] + "'>" + arry2[1] + "</option>\n";
			if (arry2[0] == def)
				sel = i + 1;
		}
	}
	txt += "</select>";
	document.form1.company_id.outerHTML = txt;
	document.form1.company_id.selectedIndex = sel;
}
function SetTantoushaList(def) {
	var txt, i, j = 0, arry1, arry2, sel = 0;
	txt = "<select name='tantousha_id'><option value=''>- �I�����Ă������� -</option>\n";
	arry1 = document.form1.tantousha_list.value.split("<%=SEPARATOR2%>");
	for (i = 0; i < arry1.length; i++) {
		if (arry1[i] != "") {
			arry2 = arry1[i].split("<%=SEPARATOR1%>");
			if (arry2[0] == document.form1.company_id.value) {
				txt += "<option value='" + arry2[1] + "'>" + arry2[2] + "</option>\n";
				j++;
				if (arry2[1] == def)
					sel = j;
			}
		}
	}
	txt += "</select>";
	document.form1.tantousha_id.outerHTML = txt;
	document.form1.tantousha_id.selectedIndex = sel;
}
function AddCompany(company_id, company_name, tantousha_id, tantousha_name) {
	document.form1.company_list.value += company_id + "<%=SEPARATOR1%>" + company_name + "<%=SEPARATOR2%>";
	SetCompanyList(company_id);
	document.form1.tantousha_list.value += company_id + "<%=SEPARATOR1%>" + tantousha_id + "<%=SEPARATOR1%>" + tantousha_name + "<%=SEPARATOR2%>";
	SetTantoushaList(tantousha_id);
	document.form1.job_name.focus();
}
<%End If%>
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="reserve2.asp" onsubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">���z�M���e�������͂̏�A�u�R���e���c�\���v�{�^���������Ă��������B�K��̃w�b�_�E�t�b�_�}����̍ŏI�R���e���c���v���r���[����܂��B</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					 <tr>
						<td class="m3" width="200">�z�M��</td>
						<td class="n3" width="600"><input type="hidden" name="send_num" value="<%=send_num%>"><%=NumFormat(send_num)%> ��</td>
					</tr>
					<tr>
						<td class="m3" width="200">���M���@���P</td>
						<td class="n3" width="600">����
							<select name="start_date_y"><%SelectYear2 ""%></select>�N&nbsp;
							<select name="start_date_m"><%SelectMonth ""%></select>��&nbsp;
							<select name="start_date_d"><%SelectDay ""%></select>��&nbsp;
						</td>
					</tr>
<%If g_login_type = LOGIN_AGENT Then%>
					<tr>
						<td class="m3" width="200">���M��Ɩ��@���T</td>
						<td class="n3" width="600"><select name="company_id"></select>
<%If Not CheckPermission(PM_REGIST_CLIENT) Then%>
							<input type="button" value="�V�K�o�^" onclick="window.open('regist_client.asp?id=<%=g_login_id%>', 'regist_client')">
<%End If%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="200">�S����</td>
						<td class="n3" width="600"><select name="tantousha_id"></select></td>
					</tr>
<%ElseIf g_login_type = LOGIN_CLIENT Then%>
					<tr>
						<td class="m3" width="200">���M��Ɩ�</td>
						<td class="n3" width="600"><%=company_name%></td>
					</tr>
					<tr>
						<td class="m3" width="200">�S����</td>
						<td class="n3" width="600"><input type="hidden" name="tantousha_id" value="<%=g_login_id%>"><%=tantousha_name%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3" width="200">�W���u���@���V</td>
						<td class="n3" width="600"><input type="text" name="job_name" size=80 maxlength=50></td>
					</tr>
					<tr>
						<td class="m3" width="200">�����i�S�p20�����ȓ��j�@���W</td>
						<td class="n3" width="600"><input type="text" name="subject" size=50 maxlength=25 value="(^^)�I"></td>
					</tr>
					<tr>
						<td class="m3" width="200">
							<p>�{���@���X</p>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td class="m3">
										<div align="right">��</div>
									</td>
									<td class="m3">�`���V�^�C�v</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right"></div>
									</td>
									<td class="m3">�i�S�p36�����~200�s�ȓ��j</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right">��</div>
									</td>
									<td class="m3">�d��^�C�v</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right"></div>
									</td>
									<td class="m3">�i�S�p36�����~20�s�ȓ��j</td>
								</tr>
							</table>
						</td>
						<td class="n3" width="600">
<%If mail_format = "0" Then%>
							<textarea name="body" cols=78 rows=30 class="np"></textarea>
<%Else%>
							�e�L�X�g�g�s�l�k�A�g�s�l�k���[���̂����e�̍ۂɂ́A<br>
							<a href="mailto:ad@haimail.net">ad@haimail.net</a>�܂Ń��[���Y�t�ɂĂ����e���������B
							<input type="hidden" name="body" value="[���[���ɂē��e]">
<%End If%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="200"><nobr>�N���b�N�J�E���^�̗L���@��10</nobr></td>
						<td class="n3" width="600">
								<input type="radio" name="click_counter" value="1">����
							<input type="radio" name="click_counter" value="0">�Ȃ�
						</td>
				</table>
			</td>
		</tr>
	</table>
	<p>�@</p>
    <table width="800" border="3" cellspacing="0" cellpadding="3" bordercolor="cd853f">
      <tr>
        <td>
          <table border="0" cellspacing="5" cellpadding="0" width="100%">
            <tr> 
              <td valign="top" nowrap class="np" colspan="2"> <font color="336699"><b>���z�M�\��Y�؎���/�z�M����</b></font></td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�P�D</div>
              </td>
              <td class="info"><b>�z�M�\��́A<font color="#FF0000">�z�M��]���̂R�c�Ɠ��O�P�V�F�O�O�܂�</font>�ƂȂ�܂��B�z�M�\��Y�؎��Ԉȍ~�����e���������܂��Ă��A����]���̔z�M�͌����ł����˂܂��B 
                ���ꂮ��������Ӊ������B </b>�i���߂ɂ��\�񂢂��������ɂ́A���ɖ�育�����܂���j</td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�Q�D</div>
              </td>
              <td class="info">�z�M�J�n���Ԃ́A�����z�M��]���̌ߑO���ɔz�M�����Ă��������܂��B���G�󋵂ɂ��܂��ẮA���̌���ł͂Ȃ����Ƃ�\�߂������������B 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�R�D</div>
              </td>
              <td class="info">�z�M��]���ɖ�����A�����̃N���C�A���g�l���炲�������������Ă���ꍇ�A�O�����Ă����������������N���C�A���g�l����D��I�ɔz�M�����Ă��������܂��B�Z���^�[�܂ŋ�g�󋵂����⍇���������B 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�S�D</div>
              </td>
              <td class="info">�y���̔z�M�ɂ��܂��ẮA�s�Ƃ����Ă��������Ă���܂��B<br>�@<br></td>
            </tr>
            <tr> 
              <td valign="top" nowrap class="np" colspan="2"><font color="336699"><b>�����e�o�^�ɂ���</b></font></td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�T�D</div>
              </td>
              <td class="info">���[���̃w�b�_�ւ�<B>�u���M��Ɩ��v</B>���\������܂��B���m�ɂ��L���������B�܂��A����Ă��o�^�����������ꍇ�͑�ς��萔�ł����A�Z���^�[�܂ł��A���������B </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <p align="right">�U�D</p>
              </td>
              <td class="info">�����o�[�Ɂu�͂��߁[��Enet�v����̃��[�����ƔF�m�����邽�߂ɁA<B>�����̕����ɂ́u(^^)�I�v���K�{�ő}������܂��B</B>
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�V�D</div>
              </td>
              <td class="info">�͂��߁[��Enet�ł́A�u���[���z�M�v�̃v���W�F�N�g���������邽�сu�W���u���v��o�^���Ă��������Ă��܂��B�u�W���u�v�Ƃ́A������������v���W�F�N�g���Ǘ����邽�߂̋@�\�ł��B 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�W�D</div>
              </td>
              <td class="info"><B>�����͑S�p�Q�O�����ȓ���</B>���o�^�������B�i�u(^^)�I�v�܂܂��j</td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">�X�D</div>
              </td>
              <td class="info">�{���͉��R�U�����ȓ��ł��肢���Ă���܂��B���e�����o�^���������ۂɂ͗�̍Ō��<font color="red"><b>�u�K�����s�ݒ�v</b></font>���s���Ă��������B</td>
            </tr>
            <tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">10�D</div>
              </td>
              <td class="info">�t�q�k�̌��ɓ��ɐ����݂͐��Ă���܂���B�{���̍s���͈͓��ł��o�^�������B</td>
            </tr>
            <tr>  
              <td valign="top" width="15" class="info"> 
                <div align="right">11�D</div>
              </td>
              <td class="info">�N���b�N�J�E���^�͒ʏ�P�����v�����Ă���܂��B�����v��������]�̍ۂɂ́A�Z���^�[�܂ł��\���t���������B 
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
	<p>
		<input type="submit" value="�@�R���e���c�\���@">
		<input type="button" value="�L�����Z��" onclick="OnClick_cancel()">
		<%search.WriteCondition%>
		<input type="hidden" name="mail_format" value="<%=mail_format%>">
		<input type="hidden" name="mail_type" value="<%=mail_type%>">
		<input type="hidden" name="company_list" value="<%=CompanyList%>">
		<input type="hidden" name="company_sel">
		<input type="hidden" name="tantousha_list" value="<%=TantoushaList%>">
		<input type="hidden" name="tantousha_sel">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
