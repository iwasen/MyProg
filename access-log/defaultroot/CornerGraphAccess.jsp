<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.CornerGraphAccessBean" />
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
<!--　表示部分　-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="ＭＳ Ｐゴシック, Osaka">コーナー別グラフ表示</font></b></td>
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
<td width="5">　</td>
<td class="size09" align="left"><img src="images/txt_corner_meiS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getCondCornerName(db)%></b></td>
</tr>
</table>
<table width="480" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<jsp:getProperty name="SearchDbBeanId" property="dbdata" />
</table>
<table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<!---　表示部分　ここまで　--->
<!---　フッタ部分　--->
<div align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
</div>
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
</form>
</body>
</html>