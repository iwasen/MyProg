<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�{�o�^���������}�K�̊m�F���
'******************************************************

SetHeaderTitle "�{�o�^�����m�F", TC_HAKKOUSHA
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, mag_id

mag_id = Request.Form("mag_id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM V_MELMAG WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If Not RS.EOF Then
%>
<center>
<form method="post" action="cancel3.asp" name="form1">
  <input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>�����̃����}�K�̖{�o�^���������܂��B</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="23%">�����}�KID</td>
            <td class="n1"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">�����}�K��</td>
            <td class="n1"><%=ReplaceTag(RS("MG_m_name"))%></td>
          </tr>
          <tr>
            <td class="m1">���s��ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">���s�Җ�</td>
            <td class="n1"><%=RS("MM_h_name")%></td>
          </tr>
          <tr>
            <td class="m1">e-mail�A�h���X</td>
            <td class="n1"><%=RS("MM_mail_adr")%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <center>
  <input type="submit" value=" ���s ">
  <input type="button" value=" ��ݾ� " onClick="location.href='cancel.asp'">
  </center>
</form>
</center>

<%
Else
%>
<center>
<p>�w�肵�������}�KID�͓o�^����Ă��܂���B</p>
<input type="button" value=" �߂� " onClick="location.href='cancel.asp'">
</center>
<%
End If

RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
