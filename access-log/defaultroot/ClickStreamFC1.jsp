<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*,java.util.Date,java.text.SimpleDateFormat,java.util.StringTokenizer" %>
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<html>
<head>
<title>xxx Business Analyst ��������</title>
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="size.css" type="text/css">
</head>
<body bgcolor="#e0e0e0">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="center">
      <font size="+2" color="red">
<%
if (SelectCondBeanId.getStreamCounting() == 1) {
%>
      �N���b�N�X�g���[����V�K�ɏW�v���D�D�D
<%
} else {
%>
      �N���b�N�X�g���[���𓯈�����ōďW�v���D�D�D
<%
}
%>
      </font>
    </td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td align="center">�����ԍ��F<%=SelectCondBeanId.getStreamTableID()%></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td align="center">
      <font size="-1">
�������Ɏ��Ԃ�������ꍇ�A��U�u���E�U����A���΂炭�����Ă���A<br>
��������܂��͏�L�����ԍ�����͂��āA�\���{�^�����N���b�N���Ă��������B
      </font>
    </td>
  </tr>
</table>
<%

// ���N�G�X�g����cookie���擾����
Cookie[] cookies = request.getCookies();
Cookie cClone = null;

String sHistory = "";
String sCookieValue = "";

if(cookies!=null){
	for(int i=0;i < cookies.length;i++) {
		if(cookies[i].getName().equals("STREAM_HISTORY")){
			cClone = (Cookie)cookies[i].clone();
			sCookieValue = (String)cClone.getValue();
		}
	}
}

StringTokenizer st = new StringTokenizer(sCookieValue,"$");

boolean b_execute = true;
while(st.hasMoreTokens()) {
  String psHistory = st.nextToken().trim();
  String sId = psHistory.substring(0,psHistory.indexOf(" ")).trim();

  if (sId.equals(SelectCondBeanId.getStreamTableID())){
    b_execute = false;
  }
}

// �����ԍ��̓�d�o�^�͂��Ȃ�
if(b_execute){
// �����͂Q�O���\������
  int cnt = 0;
  int left = 0;
  while(sCookieValue.indexOf("$",left) != -1){
    left = sCookieValue.indexOf("$",left) + 1;
    cnt++;
    if (cnt == 19)
      sCookieValue = sCookieValue.substring(0,left);
  }

  SimpleDateFormat sdf = new SimpleDateFormat("yyyy�NMM��dd�� HH��mm��");
  String sNewValue = SelectCondBeanId.getStreamTableID() + " " + sdf.format(new Date());

  sCookieValue = sNewValue + "$" + sCookieValue;

  Cookie cookie = new Cookie("STREAM_HISTORY",sCookieValue);
  cookie.setMaxAge(7776000); // �X�O����
  response.addCookie(cookie);
}
%>
</body>
</html>
