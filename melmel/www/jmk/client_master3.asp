<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�N���C�A���g�}�X�^�C�����
'******************************************************

SetHeaderTitle "�N���C�A���g�}�X�^�C��", TC_MASTER

Dim client_id

client_id = Request.QueryString("id")
If IsEmpty(client_id) Then
  Response.Redirect("index.asp")
End If
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
  return confirm("�X�V���܂��B��낵���ł����H");
}
function OnClick_sakujo() {
  if (confirm("���̃N���C�A���g���폜���Ă�낵���ł����H")) {
    location.href = "client_master5.asp?id=<%=client_id%>";
  }
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
SQL = "SELECT * FROM T_CLIENT WHERE CM_client_id='" & client_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="client_master4.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="client_id" value="<%=RS("CM_client_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>���N���C�A���g���</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="25%">�N���C�A���gID</td>
            <td class="n3"><%=RS("CM_client_id")%></td>
          </tr>
          <tr>
            <td class="m3">�N���C�A���g��</td>
            <td class="n3"><input type="text" name="client_name" size=90 maxlength=100 value="<%=RS("CM_client_name")%>"></td>
          </tr>
          <tr>
            <td class="m3">�g�D��</td>
            <td class="n3"><input type="text" name="company" size=90 maxlength=100 value="<%=RS("CM_company")%>"></td>
          </tr>
          <tr>
            <td class="m3">�S���Җ�</td>
            <td class="n3"><input type="text" name="tantousha" size=50 maxlength=50 value="<%=RS("CM_tantousha")%>"></td>
          </tr>
          <tr>
            <td class="m3">�p�X���[�h</td>
            <td class="n3"><input type="text" name="password" size=20 maxlength=20 value="<%=RS("CM_password")%>"></td>
          </tr>
          <tr>
            <td class="m3">�A���惁�[���A�h���X</td>
            <td class="n3"><input type="text" name="mail_adr" size=50 maxlength=100 value="<%=RS("CM_mail_adr")%>"></td>
          </tr>
          <tr>
            <td class="m3">�A����d�b�ԍ�</td>
            <td class="n3"><input type="text" name="tel_no" size=20 maxlength=20 value="<%=RS("CM_tel_no")%>"></td>
          </tr>
          <tr>
            <td class="m3">�o�^��</td>
            <td class="n3"><%=TimeFormat(RS("CM_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m3">�ŏI�X�V��</td>
            <td class="n3"><%=TimeFormat(RS("CM_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" �X�V ">
  <input type="reset" value="���Z�b�g">
  <input type="button" value=" �폜 " onClick="OnClick_sakujo()">
  <input type="button" value=" �߂� " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
