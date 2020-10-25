<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>
<? marketer_header('メールニュースバックナンバーリスト', PG_CENTER) ?>

<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
<td bgcolor="#dbdbdb"> 
<td width="12" rowspan="2"> <img src="images/common/spacer.gif" width="18" height="10"> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15"></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_OFFICE); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10" /> 
<td valign="top"> 
<table width="553" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td width="553"><img src="images/common_my/title_06.gif" alt="COMMON" width="553" height="25"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_news/title01.gif" alt="事務局より" width="164" height="28"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mkk_news/title05.gif" alt="メールニュースバックナンバーリスト" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td bgcolor="#dadada">
<?
// メールニュース
$sql = 'SELECT ns_subject,ns_body'
		. ' FROM t_mail_news'
		. " WHERE ns_status<=1 AND ns_target IN (2,3) ORDER BY ns_regist_date DESC limit 2 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->ns_subject);
	$body = $fetch->ns_body;
}
?>

<table width="100%" border="0" cellpadding="5" cellspacing="1" class="nor_text">
<tr bgcolor="#FFFFFF"> 
<td width="100%"><?=nl2br($body)?></td>
</tr>
</table>

</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td>
<table width="100%" border="0">
<tr> 
<td class="footer_text"> 

<?
if ($no > 0) {
?>
						<a href="mkk_pastmnd.php?no=<?=$no - 1?>">＜＜ 前へ</a>
<?
}
?></td>
<td align="right" class="footer_text"> 
<?
if ($nrow > 1) {
?>
						<a href="mkk_pastmnd.php?no=<?=$no + 1?>">次へ ＞＞</a>
<?
}
?> </td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>

<tr> 
<td align="right"><a href="#top"><img src="images/common/go_top.gif" alt="GO TOP" width="57" height="11" border="0"></a></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="30"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>
