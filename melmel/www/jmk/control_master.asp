<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�R���g���[���}�X�^�C�����
'******************************************************

SetHeaderTitle "�R���g���[���}�X�^", TC_MASTER
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
  return confirm("�R���g���[���}�X�^���X�V���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->
<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_CONTROL"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="control_master2.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width="700">
    <tr>
      <td>�����[���T�[�o���</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width="40%">���C��SMTP�T�[�o��</td>
            <td class="n3"><input type="text" name="smtp_server1" size=50 value="<%=RS("CT_smtp_server1")%>"></td>
          </tr>
          <tr>
            <td class="m3">�o�b�N�A�b�vSMTP�T�[�o��</td>
            <td class="n3"><input type="text" name="smtp_server2" size=50 value="<%=RS("CT_smtp_server2")%>"></td>
          </tr>
          <tr>
            <td class="m3">���C��POP3�T�[�o��</td>
            <td class="n3"><input type="text" name="pop3_server1" size=50 value="<%=RS("CT_pop3_server1")%>"></td>
          </tr>
          <tr>
            <td class="m3">�o�b�N�A�b�vPOP3�T�[�o��</td>
            <td class="n3"><input type="text" name="pop3_server2" size=50 value="<%=RS("CT_pop3_server2")%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>�����[���A�J�E���g���</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width="40%">���o�^�����}�K���[�U��</td>
            <td class="n3"><input type="text" name="pop3_kmag_user" size=50 value="<%=RS("CT_pop3_kmag_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">���o�^�����}�K�p�X���[�h</td>
            <td class="n3"><input type="password" name="pop3_kmag_pass" size=50 value="<%=RS("CT_pop3_kmag_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">�{�o�^�����}�K���[�U��</td>
            <td class="n3"><input type="text" name="pop3_hmag_user1" size=50 value="<%=RS("CT_pop3_hmag_user1")%>"></td>
          </tr>
          <tr>
            <td class="m3">�{�o�^�����}�K�p�X���[�h</td>
            <td class="n3"><input type="password" name="pop3_hmag_pass1" size=50 value="<%=RS("CT_pop3_hmag_pass1")%>"></td>
          </tr>
          <tr>
            <td class="m3">�{�o�^�����}�K���[�U��(Backup)</td>
            <td class="n3"><input type="text" name="pop3_hmag_user2" size=50 value="<%=RS("CT_pop3_hmag_user2")%>"></td>
          </tr>
          <tr>
            <td class="m3">�{�o�^�����}�K�p�X���[�h(Backup)</td>
            <td class="n3"><input type="password" name="pop3_hmag_pass2" size=50 value="<%=RS("CT_pop3_hmag_pass2")%>"></td>
          </tr>
          <tr>
            <td class="m3">�f�ڕ񍐃��[�����[�U��</td>
            <td class="n3"><input type="text" name="pop3_report_user" size=50 value="<%=RS("CT_pop3_report_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">�f�ڕ񍐃��[���p�X���[�h</td>
            <td class="n3"><input type="password" name="pop3_report_pass" size=50 value="<%=RS("CT_pop3_report_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">�f�ڈ˗��e�X�g���[�����[�U��</td>
            <td class="n3"><input type="text" name="pop3_test_user" size=50 value="<%=RS("CT_pop3_test_user")%>"></td>
          </tr>
          <tr>
            <td class="m3">�f�ڈ˗��e�X�g���[���p�X���[�h</td>
            <td class="n3"><input type="password" name="pop3_test_pass" size=50 value="<%=RS("CT_pop3_test_pass")%>"></td>
          </tr>
          <tr>
            <td class="m3">�f�ڈ˗��e�X�g���[�����M��A�h���X</td>
            <td class="n3"><input type="text" name="test_mail" size=50 value="<%=RS("CT_test_mail")%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td>�����������ݒ���</td>
    </tr>
    <tr>
      <td>
        <table border=0 align="center" width="100%">
          <tr>
            <td class="m3" width=280>�����������s�C���^�[�o��</td>
            <td class="n3"><input type="text" name="auto_interval" value="<%=RS("CT_auto_interval")%>"> �b</td>
          </tr>
          <tr>
            <td class="m3" width=280>���O�ۑ�����</td>
            <td class="n3"><input type="text" name="log_limit" value="<%=RS("CT_log_limit")%>"> ��</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  </br>
  <input type="submit" value=" �X�V " name="submit1">
  <input type="reset" value="���Z�b�g">
  <input type="button" value=" �߂� " onClick="history.back()">
</form>
</center>
<%
RS.Close
%>
<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
