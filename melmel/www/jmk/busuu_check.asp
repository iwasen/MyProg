<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���Ȑ\�������`�F�b�NCSV�t�@�C������
'******************************************************

SetHeaderTitle "���Ȑ\�������`�F�b�N", TC_HAKKOUSHA
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
  if (document.form1.csv.value != "") {
    document.all("msg").style.display = "";
  }
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
  <form method="post" name="form1" action="busuu_check2.asp" ENCtype="multipart/form-data" onSubmit="OnSubmit_form1()">
    <p>���܂��܂����ԃ��O��CSV�t�@�C������͂��Ă��������B<font size="-1">�i���͂��Ȃ��Ă����s�͂ł��܂��j</font></p>
    <p>�܂��܂����ԃ��O <input type="file" name="csv" size=50></p>
    <input type="submit" name="form1" value=" ���s ">
  <div id="msg" style="display:none"><br><font color="red"><b>�t�@�C���𑗐M���ł��B���΂炭���҂����������E�E�E</b></font></div>
  </form>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
