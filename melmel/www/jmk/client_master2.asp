<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�N���C�A���g�}�X�^�o�^���
'******************************************************

SetHeaderTitle "�N���C�A���g�}�X�^�o�^", TC_MASTER
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
  with (document.form1) {
    if (client_id.value == "") {
      alert("�N���C�A���gID����͂��Ă��������B");
      client_id.focus();
      return false;
    }
    if (client_id.value.length != 5) {
      alert("�N���C�A���gID�͂T���œ��͂��Ă��������B");
      client_id.focus();
      return false;
    }
    if (client_name.value == "") {
      alert("�N���C�A���g������͂��Ă��������B");
      client_name.focus();
      return false;
    }
  }
  return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.client_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<form method="post" action="client_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>���N���C�A���g���</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="25%">�N���C�A���gID</td>
            <td class="n3"><input type="text" name="client_id" size=5 maxlength=5> <font size="-1">�i�V�����h�c���T���̐����œ��͂��Ă��������j</font></td>
          </tr>
          <tr>
            <td class="m3">�N���C�A���g��</td>
            <td class="n3"><input type="text" name="client_name" size=90 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">�g�D��</td>
            <td class="n3"><input type="text" name="company" size=90 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">�S���Җ�</td>
            <td class="n3"><input type="text" name="tantousha" size=50 maxlength=50></td>
          </tr>
          <tr>
            <td class="m3">�p�X���[�h</td>
            <td class="n3"><input type="text" name="password" size=20 maxlength=20></td>
          </tr>
          <tr>
            <td class="m3">�A���惁�[���A�h���X</td>
            <td class="n3"><input type="text" name="mail_adr" size=50 maxlength=100></td>
          </tr>
          <tr>
            <td class="m3">�A����d�b�ԍ�</td>
            <td class="n3"><input type="text" name="tel_no" size=20 maxlength=20></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="hidden" name="new" value="1">
  <input type="submit" value=" �o�^ ">
  <input type="reset" value="���Z�b�g">
  <input type="button" value=" �߂� " onClick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
