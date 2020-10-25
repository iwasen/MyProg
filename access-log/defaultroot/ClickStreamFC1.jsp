<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*,java.util.Date,java.text.SimpleDateFormat,java.util.StringTokenizer" %>
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<html>
<head>
<title>xxx Business Analyst 発見くん</title>
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
      クリックストリームを新規に集計中．．．
<%
} else {
%>
      クリックストリームを同一条件で再集計中．．．
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
    <td align="center">処理番号：<%=SelectCondBeanId.getStreamTableID()%></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td align="center">
      <font size="-1">
※処理に時間がかかる場合、一旦ブラウザを閉じ、しばらくたってから、<br>
同一条件または上記処理番号を入力して、表示ボタンをクリックしてください。
      </font>
    </td>
  </tr>
</table>
<%

// リクエストからcookieを取得する
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

// 処理番号の二重登録はしない
if(b_execute){
// 履歴は２０件表示する
  int cnt = 0;
  int left = 0;
  while(sCookieValue.indexOf("$",left) != -1){
    left = sCookieValue.indexOf("$",left) + 1;
    cnt++;
    if (cnt == 19)
      sCookieValue = sCookieValue.substring(0,left);
  }

  SimpleDateFormat sdf = new SimpleDateFormat("yyyy年MM月dd日 HH時mm分");
  String sNewValue = SelectCondBeanId.getStreamTableID() + " " + sdf.format(new Date());

  sCookieValue = sNewValue + "$" + sCookieValue;

  Cookie cookie = new Cookie("STREAM_HISTORY",sCookieValue);
  cookie.setMaxAge(7776000); // ９０日間
  response.addCookie(cookie);
}
%>
</body>
</html>
