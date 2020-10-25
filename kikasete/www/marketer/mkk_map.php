<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
?>

<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_MAP) ?>


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

<? marketer_menu(M_SITEMAP); ?>

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
<td><img src="images/mkk_sitemap/title01.gif" alt="sitemap" width="120" height="28"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mkk_sitemap/title02.gif" alt="ＭｙMenu" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="footer_text"> 
<table width="553" border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<a href="mkk_myenq.php">Ｍｙアンケート</a></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_MY_MONITOR)) {
?>
<a href="mp_pjt_list.php">Ｍｙパートナー</a>
<?
}
?></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_MY_SEARCH)) {
?>
<a href="mkk_mysearch.php">Myサーチ</a>
<?
}
?></td>
</tr>
<tr> 
<td colspan="6"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>

</table></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr> 
<td><img src="images/mkk_sitemap/title03.gif" alt="Pro Menu" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td> 
<table border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td width="24"><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160"><a href="pe_index.php">Proアンケート</a></td>
</tr>
</table></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20"></td>
</tr>
<tr>
  <td><img src="images/mkk_sitemap/title05.gif" alt="My Setting" width="553" height="24" /></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td class="footer_text"> 
<table width="553" border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td colspan="6"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_MY_AGENT)) {
?>
<a href="mkk_myagent.php">Myエージェント</a>
<?
}
?></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_REGIST_INF)) {
?>
<a href="mkk_update.php">登録情報の更新</a>
<?
}
?></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_ASSISTANT)) {
?>
<a href="mkk_assistant.php">アシスタントの設定</a>
<?
}
?></td>
</tr>
<tr> 
<td colspan="6"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td>
<?
if (check_permission(PMR_CHG_AGENT)) {
?>
<a href="mkk_achange.php">エージェントの変更</a>
<?
}
?></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table></td>
</tr>
<tr>
  <td>&nbsp;</td>
</tr>
<tr> 
<td><img src="images/mkk_sitemap/title04.gif" alt="COMMON" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td> 
<table border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td width="24"><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160"><a href="mkk_kkstfrom.php">事務局より</a></td>
</tr>
</table></td>
</tr>
<tr> 
<td> 
<table border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr>
<td>&nbsp;</td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
  <td><a href="mkk_monitor.php">モニタープロファイル</a></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
  <td><a href="mkk_news_1.php">事務局からのお知らせ</a></td>
  <td rowspan="8">&nbsp;</td>
</tr>
<tr> 
<td colspan="5"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="100" height="10"></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="200"><a href="mkk_pastmn_1.php">メールニュースバックナンバーリスト</a></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="200"><a href="mkk_privacy.php">Privacy Policy</a></td>
</tr>
<tr> 
<td colspan="5"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td><a href="mkk_rules.php">規約</a></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td><a href="mkk_faq.php">FAQ</a></td>
</tr>
<tr> 
<td colspan="5"><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td><a href="mkk_resign.php">退会</a></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="25"></td>
</tr>
<tr> 
<td> 
<table border="0" cellspacing="0" cellpadding="0" class="nor_text">
<tr> 
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160"><a href="mkk_map.php">SITE MAP</a></td>
<td><img src="images/mkk_sitemap/img_yajirushi.gif" width="24" height="12"></td>
<td width="160">
<?
if (check_permission(PMR_MY_AGENT)) {
?>
<a href="mkk_contact.php">CONTACT US</a>
<?
}
?></td>
</tr>
</table></td>
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
