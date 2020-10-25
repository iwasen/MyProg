<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.ClickStreamFCBean" />
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<jsp:useBean id="db" scope="request" class="base.DataBase" />
<html>
<head>
<title>xxx Business Analyst ”­Œ©‚­‚ñ</title>
<meta http-equiv="Pragma" content="no-cache">
</head>
<body>
<% SearchDbBeanId.searchDB(db, SelectCondBeanId); %>
<script language="JavaScript">
<!--
parent.closeWindow();
//-->
</script>
</body>
</html>
