<%@ page contentType="text/html; charset=Shift_JIS" %>
<html>
<head>
<title>xxx Business Analyst 発見くん</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<script language="JavaScript">
<!--
function closeWindow() {
  var f;
  if (window.dialogArguments)
    f = window.dialogArguments.document.form1;
  else
    f = opener.document.form1;
  f.mode.value = 3;
  f.submit();
  window.close();
}
//-->
</script>
</head>

<frameset rows="100%,*" frameborder="0" framespacing="0">
<frame src="ClickStreamFC1.jsp" name="message">
<frame src="ClickStreamFC2.jsp" name="count">
</frameset>

</html>