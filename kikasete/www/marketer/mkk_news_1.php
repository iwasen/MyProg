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

<?
// 最新のお知らせ
$sql = 'SELECT cs_date,cs_title,cs_message'
		. ' FROM t_center_msg'
		. ' WHERE cs_marketer_top AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) ORDER BY cs_date DESC,cs_seq_no DESC limit 1';
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->cs_title);
	$date = format_date($fetch->cs_date);
	$message = $fetch->cs_message;
}
?>

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
<td><img src="images/mkk_news/title02.gif" alt="事務局からのお知らせ" width="553" height="24"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10"></td>
</tr>
<tr> 
<td> 
<p>きかせて・net からのお知らせです。</p>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td><img src="images/mkk_news/title03.gif" alt="最新のお知らせ" width="176" height="15"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td> 
<table width="100%" border="0">
<tr> 
<td class="right_menu_title"> <?=$title?> </td>
<td align="right" class="nor_text"> <?=$date?> </td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#e5eaf0" class="nor_text" align="center"> 
<table width="100%" border="0" cellspacing="0" cellpadding="10" class="nor_text">
<tr> 
<td><?=nl2br($message)?></td>
</tr>
</table>
</td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="25" /></td>
</tr>
<tr> 
<td><img src="images/mkk_news/title04.gif" alt="過去のお知らせ" width="176" height="15"></td>
</tr>
<tr> 
<td><img src="images/common/spacer.gif" width="10" height="10" /></td>
</tr>
<tr> 
<td bgcolor="#dadada"> 
<table width="100%" border="0" cellspacing="1" cellpadding="5" class="nor_text">
<tr> 
<td width="100" bgcolor="#e5eaf0">日時</td>
<td width="300" bgcolor="#e5eaf0">タイトル</td>
</tr>

<?
// 過去のお知らせ
$sql = 'SELECT cs_date,cs_title'
		. ' FROM t_center_msg'
		. ' WHERE cs_marketer_top AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) ORDER BY cs_date DESC,cs_seq_no DESC limit 6 OFFSET 1';
$result = db_exec($sql);
$nrow = pg_numrows($result);
$no = 0;
for ($i = 0; $i < 5; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title = htmlspecialchars($fetch->cs_title);
		$date = format_date($fetch->cs_date);
	} else {
		$title = '<br>';
		$date = '<br>';
	}
	$no++;
?>
<tr bgcolor="#FFFFFF"> 
<td align="center"><?=$date?></font></td>
<td width="300"><a href="mkk_newsd.php?no=<?=$no?>"><?=$title?></a></td>
</tr>
<?
}
?>

</table>
</td>
</tr>
<tr> 
<td align="right" class="footer_text"> 
<?
if ($nrow > 5) {
?>
<a href="mkk_pastnews.php?no=5">>>次の10件</a> 
<?
}
?>

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
