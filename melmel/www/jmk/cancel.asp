<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�{�o�^���������}�KID����
'******************************************************

SetHeaderTitle "�{�o�^����", TC_HAKKOUSHA
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
    if (mag_id.value.length != 5) {
      alert("�������郁���}�KID�i�T���j����͂��Ă��������B");
      mag_id.focus();
      return false;
    }
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.mag_id.focus()">

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
  <form method="post" name="form1" action="cancel2.asp" onSubmit="return OnSubmit_form1();" ,>
    <p>�������郁���}�KID
    <input type="text" name="mag_id" maxlength=5>
    </p>
    <input type="submit" value=" ���� ">
  </form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
