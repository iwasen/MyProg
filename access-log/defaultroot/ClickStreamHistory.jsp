<%@ page contentType="text/html; charset=Shift_JIS"
%><%@ page import="javax.servlet.http.Cookie,java.util.StringTokenizer"
%><html>
<head>
<title>xxx Business Analyst ��������</title>
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="size.css" type="text/css">
<script language="JavaScript">
<!--

function selectHistory(sId) {
  if (opener.document.form1.streamTableID != null) {
    opener.document.form1.streamTableID.value = sId;
    opener.document.form1.ToStreamTableID.click();
  } else {
    alert("��ʑJ�ڂ��s���ł��B\n�u�N���b�N�X�g���[�������L���O�v��I�����Ă��������B");
  }
}

//-->
</script>
</head>
<body  bgcolor="#FFFFFF" text="#000000" >
<%
Cookie[] cookies = request.getCookies();
Cookie cClone = null;

String sCookieValue = "";

if(cookies!=null){
for(int i=0;i < cookies.length;i++) {
  if(cookies[i].getName().equals("STREAM_HISTORY")){
    cClone = (Cookie)cookies[i].clone();
      sCookieValue = (String)cClone.getValue();
    }
  }
}
%><div align="center">
<b class="size18">�N���b�N�X�g���[���W�v����</b><br>
<br>
  <table border="1" bordercolor="#000000"  cellpadding="0" cellspacing="0">
    <tr bordercolor="#000000" bgcolor="#CCFFCC" class="size11">
      <td width="90" height="22" align="center"><b> �����ԍ� </b></td>
      <td width="170" align="center"><b> �������� </b></td>
    </tr>
<%
StringTokenizer st = new StringTokenizer(sCookieValue,"$");

while(st.hasMoreTokens()) {
  String psHistory = st.nextToken().trim();

  String sId = psHistory.substring(0,psHistory.indexOf(" "));
  String sDate = psHistory.substring(psHistory.indexOf(" ") + 1);

%>  <tr class="size09">
      <td  height="19">�@�@<a href="#" onClick="javascript:selectHistory('<%= sId %>');"><%= sId %></a> </td>
      <td align="center"><%= sDate %></td>
    </tr>
<% }
%>  </table>
<br>
<input type="button" value="����" style="width:80px" onClick="window.close();">
</div>
</body>
</html>
