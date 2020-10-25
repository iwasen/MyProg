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
<? marketer_header('事務局からのお知らせ', PG_CENTER) ?>

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
// 事務局からのお知らせ
$sql = 'SELECT cs_date,cs_title'
		. ' FROM t_center_msg'
		. " WHERE cs_marketer2_mypage AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) ORDER BY cs_date DESC,cs_seq_no DESC limit 11 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < 10; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title = htmlspecialchars($fetch->cs_title);
		$date = format_date($fetch->cs_date);
	} else {
		$title = '<br>';
		$date = '<br>';
	}
?>
                    <tr bgcolor="#FFFFFF"> 
                      <td align="center">
                        <?=$date?>
                      </td>
                      <td width="300"><a href="mkk_newsd.php?no=<?=$no?>">
                        <?=$title?>
                        </a></td>
                    </tr>
<?
}
?>
                  </table>
                </td>
              </tr>
              <tr> 
                <td align="right" class="footer_text"> 
			<table width="553" border="0" cellspacing="0" cellpadding="0" valign="middle">
				<tr>
					  <td width="50%" valign="middle" class="footer_text"> 
                        <?
// 前のお知らせ
if ($no > 0) {
?>
                        <a href="mkk_pastnews.php?no=<?=max($no - 10, 0)?>">&lt;&lt;前の10件</a> 
                        <?
}
?>
                      </td>
					  <td width="50%" align="right" valign="middle" class="footer_text"> 
                        <?
// 次のお知らせ
if ($nrow > 10) {
?>
                        <a href="mkk_pastnews.php?no=<?=$no + 10?>">次の10件&gt;&gt; 
                        </a> 
                        <?
}
?>
                      </td>
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
