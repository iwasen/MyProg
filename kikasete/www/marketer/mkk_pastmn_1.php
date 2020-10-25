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
<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_CENTER) ?>

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
<td><img src="images/mkk_news/title01.gif" alt="事務局より" width="164" height="28"> 
</td>
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
<td> 
<p> これまでに、発行したメールニュースの一覧です。<br>
メールニュース購読の登録をしていただくか、Myページ登録をして頂いた方にお届けしています。<br>
<br>
メールニュースの配信は、毎週水曜日です。 </p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="20" /></td>
</tr>
<tr> 
<td> 
<table width="100%" border="0">
<tr> 
<td class="footer_text"> 

<?
// メールニュース一覧表示
if ($no == '')
	$no = 0;
$sql = 'SELECT ns_regist_date,ns_subject'
		. ' FROM t_mail_news'
		. " WHERE ns_status<=1 AND ns_target IN (2,3) ORDER BY ns_regist_date DESC limit 11 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<?
if ($no > 0) {
?>
<a href="mkk_pastmn_1.php?no=<?=max($no - 10, 0)?>"><< 前の10件</a>
<?
}
?>
</td>
<td align="right" class="footer_text"> 
<?
if ($nrow > 10) {
?>
<a href="mkk_pastmn_1.php?no=<?=$no + 10?>">次の10件 >></a>
<?
}
?>
</td>
</tr>
</table>
</td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="nor_text">
<tr bgcolor="#e5eaf0"> 
<td width="80%">タイトル</td>
<td width="20%">発行日</td>
</tr>

<?
for ($i = 0; $i < 10; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title = htmlspecialchars($fetch->ns_subject);
		$date = format_date($fetch->ns_regist_date);
	} else {
		$title = '<br>';
		$date = '<br>';
	}
?>
<tr bgcolor="#FFFFFF">
<td width="80%"><a href="mkk_pastmnd.php?no=<?=$no + $i?>"><?=$title?></a></td>
<td width="20%" align="center"><?=$date?></td>
</tr>
<?
}
?>


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
