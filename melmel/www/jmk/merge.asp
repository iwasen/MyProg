<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���s��ID�������
'******************************************************

SetHeaderTitle "���s��ID����", TC_HAKKOUSHA
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
  var rt;
  with (document.form1) {
    if (mel_id.value.length != 5) {
      alert("���s��ID�P�i�T���j����͂��Ă��������B");
      mel_id.focus();
      return false;
    }
    if (mel_id2.value.length != 5) {
      alert("���s��ID�Q�i�T���j����͂��Ă��������B");
      mel_id2.focus();
      return false;
    }
    if (mel_id.value == mel_id2.value) {
      alert("���s��ID�������ł��B");
      mel_id.focus();
      return false;
    }
    rt = confirm("���s��ID[" + mel_id.value + "]��[" + mel_id2.value + "]�𓝍����܂��B��낵���ł����H");
  }
  return rt;
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.mel_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
  <form method="post" name="form1" action="merge2.asp" onSubmit="return OnSubmit_form1();">
    <p>�����s��ID1�ɔ��s��ID2�𓝍����܂��B</p>
    <table border=0 cellspacing=0 cellpadding=4>
      <tr>
        <td>���s��ID�P</td>
        <td><input type="text" name="mel_id" maxlength=5></td>
      </tr>
      <tr>
        <td>���s��ID�Q</td>
        <td><input type="text" name="mel_id2" maxlength=5></td>
      </tr>
    </table>
    <br>
    <input type="submit" value=" ���� ">
  </form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
