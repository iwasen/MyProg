<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�L���r���}�X�^�o�^���
'******************************************************

SetHeaderTitle "�L���r���}�X�^�o�^", TC_MASTER
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
  return confirm("�o�^���܂��B��낵���ł����H");
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.keisen_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<form method="post" action="keisen_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>���L���r����� <font size="-1">�iKKKKKK�ɂ͍L��ID�AMMMMM�ɂ̓����}�K�h�c�����ߍ��܂�܂��j</font></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">�r��ID</td>
            <td class="n3"><input type="text" name="keisen_id" size=1 maxlength=1></td>
          </tr>
          <tr>
            <td class="m3">�J�n�r��</td>
            <td class="n3"><input type="text" name="start_line" size=78 maxlength=100 class="np"></td>
          </tr>
          <tr>
            <td class="m3">�I���r��</td>
            <td class="n3"><input type="text" name="end_line" size=78 maxlength=100 class="np"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" �o�^ ">
  <input type="reset" value="���Z�b�g">
  <input type="button" value=" �߂� " onClick="history.back()">

</form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
