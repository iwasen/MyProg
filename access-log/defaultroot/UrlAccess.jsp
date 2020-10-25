<%@ page contentType="text/html; charset=Shift_JIS" %>
<jsp:useBean id="UrlAccessBeanId" scope="page" class="xxx.UrlAccessBean" />
<% UrlAccessBeanId.SearchDB(request); %>
<html>
<head>
<title>xxx Business Analyst 発見くん</title>
<meta name="author" content="Kuni Tojima/tojima@xxxx.co.jp">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="table.css" type="text/css">
<link rel="stylesheet" href="size.css" type="text/css">
<!---　リンク文字スタイル定義　--->
<style>
.linkcolor_blue { color:blue;font-weight:bold;text-decoration:none; }
span a:hover { color:red }
</style>
<!---　リンク文字スタイル定義　ここまで　--->
<!---　別ウィンドウオープンJS　--->
<script language="JavaScript">
<!--
function open_url(url) {
  window.open(url, "_blank");
}
//-->
</script>
<!---　別ウィンドウオープンJS　ここまで　--->
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10">
<form method="post">
<!---　ヘッダ部分　--->
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left"><img src="images/lgo_top.jpg" width="100" height="51"></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0" height="5">
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
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="ＭＳ Ｐゴシック, Osaka">画面毎のアクセスと表示</font></b></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="3">
<tr>
<td> </td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_corner_meiS.gif" width="65" height="18"></td>
<td width="5" align="center">　</td>
<td class="size09" align="left"><jsp:getProperty name="UrlAccessBeanId" property="cornerName" /></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_gyousyu_mei.gif" width="65" height="18"></td>
<td width="5" align="center">　</td>
<td class="size09" align="left"><jsp:getProperty name="UrlAccessBeanId" property="categoryName" /></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<tr align="center" bgcolor="#FFDFB0" class="size10">
<td>順位</td>
<td>ＵＲＬ</td>
<td valign="middle">アクセス</td>
</tr>
<jsp:getProperty name="UrlAccessBeanId" property="dbdata" />
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="size08" align="left">※コーナー名もしくはＵＲＬをクリックすると実際のページが表示されます。</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="70">
<input type="submit" name="Back" value="前の20件" style="width:70"<jsp:getProperty name="UrlAccessBeanId" property="disableBackPage" />>
</td>
<td width="10">　</td>
<td width="70">
<input type="submit" name="Forward" value="次の20件" style="width:70"<jsp:getProperty name="UrlAccessBeanId" property="disableForwardPage" />>
</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
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
<table width="720" border="0" cellspacing="0" cellpadding="0">
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
<!--- hidden部分 --->
<input type="hidden" name="date" value="<jsp:getProperty name="UrlAccessBeanId" property="date" />">
<input type="hidden" name="corner" value="<jsp:getProperty name="UrlAccessBeanId" property="cornerCode" />">
<input type="hidden" name="category" value="<jsp:getProperty name="UrlAccessBeanId" property="categoryCode" />">
<input type="hidden" name="pageno" value="<jsp:getProperty name="UrlAccessBeanId" property="pageNo" />">
<!--- hidden部分 ここまで --->
</form>
</body>
</html>
