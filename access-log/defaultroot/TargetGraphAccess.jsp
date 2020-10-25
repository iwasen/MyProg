<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.TargetGraphAccessBean" />
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<jsp:useBean id="db" scope="request" class="base.DataBase" />
<html>
<head>
<title>xxx Business Analyst 発見くん</title>
<meta name="author" content="Kuni Tojima/tojima@xxxx.co.jp">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="table.css" type="text/css">
<link rel="stylesheet" href="size.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10">
<form>
<!---　ヘッダ部分　--->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left"><img src="images/lgo_top.jpg" width="100" height="51"></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="5">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_header_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
<!---　ヘッダ部分 ここまで　--->
<!---　表示部分　--->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="ＭＳ Ｐゴシック, Osaka">指定ページ・グラフ表示</font></b></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="3">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---　表示切替ボタン　--->
<table width="232" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
<tr>
<td bgcolor="#FFFFFF" height="30"> <table width="230" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="10">　</td>
<td width="100"> <input type="submit" name="Back" value="前の<jsp:getProperty name="SearchDbBeanId" property="pageSize" />件" style="width:100"<jsp:getProperty name="SearchDbBeanId" property="disableBackPage" />>
</td>
<td width="10">　</td>
<td width="100"> <input type="submit" name="Foward" value="次の<jsp:getProperty name="SearchDbBeanId" property="pageSize" />件" style="width:100"<jsp:getProperty name="SearchDbBeanId" property="disableForwardPage" />>
</td>
<td width="10">　</td>
</tr>
</table></td>
</tr>
</table>
<!---　表示切替ボタン　ここまで　--->
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="2" bgcolor="#FFCC00">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="39" align="left" class="size09"><img src="images/txt_bumon.gif" width="39" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getGroupName(db)%></b></td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_taisyou_sentakuS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getTargetCornerName(db)%></b></td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_kigyou_zokuseiS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><%=SelectCondBeanId.getCondAttribute(db)%></td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_kikan_shiteiS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getDateSpec()%>で表示</b>┃期間：<%=SelectCondBeanId.getDateSpan()%></td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="39" align="left" class="size09"><img src="images/txt_tatejjiku.gif" width="39" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getDateSpec2()%></b></td>
<td width="5"> </td>
<td width="39" align="left" class="size09"><img src="images/txt_yokojjiku.gif" width="39" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b>グラフ</b></td>
</tr>
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<jsp:getProperty name="SearchDbBeanId" property="dbdata" />
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---　表示部分　ここまで　--->
<!---　フッタ部分　--->
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center">
<input type="button" name="back" value="閉じる" onClick="window.close()">
</td>
</tr>
</table>
<!---　フッタ部分　ここまで　--->
<!-- hidden部分 -->
<input type="hidden" name="category" value="<jsp:getProperty name="SelectCondBeanId" property="category" />">
<input type="hidden" name="page" value="<jsp:getProperty name="SelectCondBeanId" property="page" />">
<input type="hidden" name="table" value="<jsp:getProperty name="SelectCondBeanId" property="table" />">
<input type="hidden" name="nosession" value="1">
<!-- hidden部分 ここまで -->
</form>
</body>
</html>