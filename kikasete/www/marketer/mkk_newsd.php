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
<? marketer_header('事務局からの過去のお知らせ', PG_CENTER) ?>

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
                <td> 
                  <?
// 事務局からのお知らせ
$sql = 'SELECT cs_date,cs_title,cs_message'
		. ' FROM t_center_msg'
		. " WHERE cs_marketer2_mypage AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) ORDER BY cs_date DESC,cs_seq_no DESC limit 2 OFFSET $no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
	$title = htmlspecialchars($fetch->cs_title);
	$date = format_date($fetch->cs_date);
	$message = $fetch->cs_message;
}
?>
                  <table width="100%" border="0">
                    <tr> 
                      <td class="right_menu_title"> 
                        <?=$title?>
                      </td>
                      <td align="right" class="nor_text"> 
                        <?=$date?>
                      </td>
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
                      <td> 
                        <?=nl2br($message)?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td><img src="images/common/spacer.gif" width="10" height="25" /></td>
              </tr>
              <tr> 
                <td align="right" class="footer_text"> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" valign="middle">
                    <tr> 
                      <td width="150" align="center" valign="middle" class="footer_text"> 
                        <?
// 前のお知らせ
if ($no > 0) {
?>
                        <a href="mkk_newsd.php?no=<?=$no - 1?>">&lt;&lt;前のお知らせ</a> 
                        <?
}
?>
                      </td>
                      <td width="253" align="center" valign="middle" class="footer_text"> 
                        <a href="mkk_pastnews.php?no=<?=$no?>">一覧</a> </td>
                      <td width="150" align="center" valign="middle" class="footer_text"> 
                        <?
// 次のお知らせ
if ($nrow > 1) {
?>
                        <a href="mkk_newsd.php?no=<?=$no + 1?>">次のお知らせ&gt;&gt;</a> 
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
