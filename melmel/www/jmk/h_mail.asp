<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���s�҈��ă��[�����͉��
'******************************************************

SetHeaderTitle "���s�҈��ă��[��", TC_HAKKOUSHA
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
function OnClick_sentaku() {
  var win;
  win = window.open("h_mail3.asp", "h_mail3", "resizable=yes,width=700,height=600,scrollbars=yes");
  win.focus();
}
function OnSubmit_form1() {
  with (document.form1) {
    if (mailto.value == "") {
      alert("�������͂��Ă��������B");
      mailto.focus();
      return false;
    }
    if (mailfrom.value == "") {
      alert("���M������͂��Ă��������B");
      mailfrom.focus();
      return false;
    }
    if (subject.value == "") {
      alert("��������͂��Ă��������B");
      subject.focus();
      return false;
    }
    if (body.value == "") {
      alert("�{������͂��Ă��������B");
      body.focus();
      return false;
    }
  }
  if (confirm("���[���𑗐M���܂��B��낵���ł����H")) {
    document.all("msg").style.display = "";
    return true;
  } else
    return false;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim mailfrom, subject, body

Call GetSendMailInfo("HMAIL", subject, mailfrom, body)
%>

<center>
<form method="post" action="h_mail2.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <table border=0 cellspacing=0 cellpadding=0 width="700">
    <tr>
      <td>������͔��s��ID���J���}�ŋ�؂��ē��͂��邩�A�u�I���v�{�^�����N���b�N���Ă��������B</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="14%">����</td>
            <td class="n1"><textarea name="mailto" cols=70 rows=5></textarea> <input type="button" value="�I��" onClick="OnClick_sentaku()"></td>
          </tr>
          <tr>
            <td class="m1" width="14%">���M��</td>
            <td class="n1"><input type="text" name="mailfrom" size=80 value="<%=mailfrom%>"></td>
          </tr>
          <tr>
            <td class="m1" width="14%">����</td>
            <td class="n1"><input type="text" name="subject" size=80 value="<%=subject%>"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>��<small>%HAKKOUSHA%</small>�ɂ͔��s�Җ��A<small>%MELMELID%</small>�ɂ͔��s��ID�A<small>%POINT%</small>�ɂ͌��|�C���g�������ߍ��܂�܂��B</small></td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="14%">�{��</td>
            <td class="n1"><textarea name="body" cols=80 rows=30><%=body%></textarea></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" ���M ">
  <input type="reset" value="���Z�b�g">
</form>
<div id="msg" style="display:none"><font color="red"><b>���[���𑗐M���ł��B���΂炭���҂����������E�E�E</b></font></div>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
