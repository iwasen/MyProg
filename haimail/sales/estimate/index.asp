<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:���ς��ʏ���
'******************************************************

'=== ���C������ ===
Dim send_num
Dim search

Set search = New CSearch
search.ReadCondition

send_num = Request.Form("send_num")

SetHeaderTitle "���ς���"
'Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(id) {
 with (document.form1) {
		if (send_num.value == "") {
			alert("���M������͂��Ă��������B");
			send_num.focus();
			return false;
		}
		if (isNaN(Number(send_num.value))) {
			alert("���������M������͂��Ă��������B");
			send_num.focus();
			return false;
		}
		if (Number(send_num.value) > <%=send_num%>) {
			alert("���M���͔��M�\���ȉ��ɂ��Ă��������B");
			send_num.focus();
			return false;
		}
		if (mail_format[0].checked == false && mail_format[1].checked == false && mail_format[2].checked == false) {
			alert("���[���`����I�����Ă��������B");
			mail_format[0].focus();
			return false;
		}
		if (mail_type[0].checked == false && mail_type[1].checked == false) {
			alert("���[���^�C�v��I�����Ă��������B");
			mail_type[0].focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body onload="document.form1.send_num.focus()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="estimate1.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�������ς���e����͂��Ă�������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">���M�\��</td>
						<td class="n3"><input type="hidden" name="kanou_suu" value="<%=send_num%>"><%=NumFormat(send_num)%> ��</td>
					</tr>
					<tr>
						<td class="m3" width="25%">���M��]��</td>
						<td class="n3"><input type="text" name="send_num">
								��</td>
					</tr>
					<tr>
						<td class="m3">���[���`��</td>
						<td class="n3">
							<input type="radio" name="mail_format" value="0">�e�L�X�g
							<input type="radio" name="mail_format" value="1">�e�L�X�gHTML
							<input type="radio" name="mail_format" value="2">HTML
						</td>
					</tr>
					<tr>
						<td class="m3">���[���^�C�v</td>
						<td class="n3">
							<input type="radio" name="mail_type" value="CR">
								�`���V�i�{��200�s�ȓ��j
								<input type="radio" name="mail_type" value="TG">
								�d��i�{��20�s�ȓ��j
						</td>
					</tr>
				</table>
				<br>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value="�@�����ς�v�Z�@">
	<input type="button" value="�@�߂�@" onclick="history.back()">
    <p class="info">�͂��߁[��̗����̌n�́A�z�M�^�C�v���ƂɈꗥ�����ƂȂ��Ă���܂��B</p>
    
  <table width="500" cellspacing="3" cellpadding="3">
    <tr bgcolor="#000000"> 
      <td colspan="2" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>Mail to Web</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>�ʏ�z�M</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>�e�L�X�gHTML</b></font></div>
      </td>
      <td width="100" class="info"> 
        <div align="center"><font color="#FFFFFF"><b>HTML</b></font></div>
      </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td width="50" class="info"> 
        <div align="right">�P</div>
      </td>
      <td width="150" class="info"> 
        <div align="center">�`���V�i200�s�j</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\20</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\25</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\30</div>
      </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td width="50" class="info"> 
        <div align="right">�Q</div>
      </td>
      <td width="150" class="info"> 
        <div align="center">�d��i20�s�j</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">\8</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">�|</div>
      </td>
      <td width="100" class="info"> 
        <div align="center">�|</div>
      </td>
    </tr>
  </table>
  <p>�@</p><table width="500" border="0" cellspacing="3" cellpadding="3">
    <tr> 
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">��</font></div>
        </td>
        <td valign="top" class="info">�ʏ��DM�z�M�́u�`���V�v�^�C�v�ƂȂ��Ă���܂��B</td>
      </tr>
      <tr> 
        
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">��</font></div>
        </td>
        <td valign="top" class="info">�z�M���Ɋւ�炸�A�Œ�L��������5���~����Ƃ����Ă��������Ă���܂��B</td>
      </tr>
      <tr> 
        
      <td nowrap valign="top" class="info"> 
        <div align="right"><font color="336699">��</font></div>
        </td>
        <td valign="top" class="info">���[�������̂����ς���A�����e�͕ʓr���A���������B</td>
      </tr>
      <tr valign="top"> 
        
      <td nowrap class="info"> 
        <div align="right"><font color="336699">��</font></div>
        </td>
        <td class="info"> 
          <p>�N���b�N�J�E���^�ݒ�́AURL�̐��Ɋւ�炸�S�Ė����ƂȂ�܂��B</p>
        </td>
      </tr>
      <tr valign="top"> 
        
      <td nowrap class="info"> 
        <div align="right"><font color="336699">��</font></div>
        </td>
        <td class="info">���̑��A�^��_�Ȃǂ������܂�����ʓr�����k�������B</td>
      </tr>
    </table>
<%search.WriteCondition%>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
