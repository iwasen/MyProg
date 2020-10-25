<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp" />
<title>きかせて・netについて | きかせて・net</title>
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#ffffff">

<!-- ▼header -->
      <table width="720" border="0" cellpadding="0" cellspacing="0" class="footer_text" bgcolor="#ffffff">
      <tr>
        
    <td><a href="../../index.php"><img src="../../images/common/logo.gif" alt="きかせて・net" width="232" height="57" border="0" /></a></td>
        <td align="right" valign="bottom"><table border="0" cellpadding="0" cellspacing="10" class="header_text">
            <tr>
              
          <td><a href="../kkstfrom/mk_contact.php"><img src="../../images/common/icon_mail.gif" width="14" height="9" /> 
            CONTACT US</a> </td>
              <td bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="28" /></td>
              
          <td><a href="../kkstfrom/mk_map.php"><img src="../../images/common/icon_sitemap.gif" width="11" height="9" /> 
            SITE MAP</a></td>
              <td bgcolor="#dbdbdb"><img src="../../images/common/spacer.gif" width="1" height="28" /></td>
              <td><img src="../../images/common/icon_tel.gif" width="12" height="10" /> 03-5472-7903</td>
            </tr>
        </table></td>
      </tr>
      </table>
<!-- ▲header -->
<table width="720" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr> 
              <tr> 
                <td>

<?
$sql = "SELECT ag_agent_id,ag_name1,ag_self_introduction FROM t_agent WHERE ag_status=0 AND ag_disp_flag ORDER BY ag_agent_id DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
<br>
<a name="<?=$fetch->ag_agent_id?>">
<table width="640" border="0" cellpadding="0" cellspacing="0" Align="center">
  <tr> 
    <td bgcolor="#dadada"> 
      <table border=0 cellspacing=1 cellpadding=5 width="100%" class="nor_text">
        <tr bgcolor="#e5eaf0"> 
          <td><?=htmlspecialchars($fetch->ag_name1)?>です</td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"><?=nl2br($fetch->ag_self_introduction)?></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?
}
?>

		</td>
              </tr> 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="10"></td>
              </tr> 
              <tr> 
                <td><img src="../../images/common/spacer.gif" width="10" height="20"></td>
              </tr> 
</table>

<!-- ▼footer -->
<table width="720" border="0" cellpadding="0" cellspacing="0" class="footer_text">
        <tr>
          <td colspan="2" align="right">

	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
            <tr>
              <td width="170" bgcolor="#ffffff"><img src="../../images/common/spacer.gif" width="170" height="5"></td>
              <th width="1" bgcolor="#ffffff"><img src="../../images/common/spacer.gif" width="1" height="1"></th>
              
          <td align="right" class="footer_text"><a href="../../index.php">HOME</a>｜<a href="../about/mk_about.php">きかせて 
            .net について</a>｜<a href="../service/mk_service.php">サービスMENU</a>｜<a href="../regist/mk_regist.php">登録</a>｜<a href="../survey/mk_survey.php">きかせて 
            .net 調べ</a>｜<a href="../kkstfrom/mk_kkstfrom.php">事務局より</a>｜<a href="../kkstfrom/mk_map.php">SITE 
            MAP</a>｜<a href="../kkstfrom/mk_contact.php">CONTACT US</a><img src="../../images/common/spacer.gif" width="20" height="5"></td>
            </tr>
			<tr>
              <td width="170" bgcolor="#ffffff"><img src="../../images/common/spacer.gif" width="170" height="5"></td>
              <th width="1" bgcolor="#ffffff"><img src="../../images/common/spacer.gif" width="1" height="1"></th>
              <td align="right" class="footer_text"><img src="../../images/common/spacer.gif" width="1" height="5"></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#ffffff"><img src="../../images/common/spacer.gif" width="10" height="10" /></td>
        </tr>
	<tr>
          <td valign="top" bgcolor="#ffffff">Copyright(C) 2000-2005 <a href="http://www.xxxxxxx.co.jp/">xx xxxxx Inc.</a> All rights reserved. </td>
          <td align="right" bgcolor="#ffffff"><img src="../../images/common/footer_logo.gif" alt="人とネットとマーケティング" width="138" height="48" /></td>
        </tr>
      </table>


<!-- ▲footer -->
</body>
</html>