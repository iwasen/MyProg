<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/count.php");
include("$inc/format.php");
include("$inc/enq_graph.php");
include("$inc/mr_permission.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_disp.php");


$marketer_id = $_SESSION['ss_marketer_id'];
$owner_id = $_SESSION['ss_owner_id'];
?>

<? marketer_header('きかせて・net ネットリサーチにプロのエージェントを', PG_HOME) ?>


<!--contents start-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr> 
<td width="788"> 
<table width="788" border="0" cellpadding="0" cellspacing="0">
<tr> 
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15" /></td>
<td bgcolor="#dbdbdb"></td>
<td width="12" rowspan="2"><img src="images/common/spacer.gif" width="18" height="10"></td>
<td valign="top"><img src="images/common/spacer.gif" width="10" height="15" /></td>
</tr>
<tr> 
<td width="170" valign="top" bgcolor="#e5eaf0"> 

<? marketer_menu(M_NULL); ?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
<td valign="top"><table width="553" border="0" cellpadding="0" cellspacing="0">

<!--２００８年締め切り用表示	 ＳＳ須藤
<img src = images/news1222.jpg>-->

  <tr>
    <td width="553"><img src="images/common_my/title_01.gif" alt="今週の1万人アンケート" width="553" height="25" /></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="8" /></td>
  </tr>
  <tr>
    <td><p>当社が毎週自主調査として実施しているきかせて・net会員向けのアンケートです。</p></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td align="center"><table width="524" border="0" cellpadding="0" cellspacing="0">
      <?
// １万人アンケート
$sql = "SELECT fe_seq_no,fe_top_page,en_enquete_id,en_title"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=5 ORDER BY en_end_date LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$fno = $fetch->fe_seq_no;
	$top_page = $fetch->fe_top_page;
	$enquete_id = $fetch->en_enquete_id;
	$title = htmlspecialchars($fetch->en_title);
?>
      <tr>
        <td align="left" colspan="3" class="description"><?=$title?></td>
      </tr>
      <tr>
        <td colspan="3"><img src="images/common/spacer.gif" width="5" height="5" border="0" /></td>
      </tr>
      <?
	$scale = 120;
	$html = '<td width="190"align="right" nowrap class="description">%TEXT%</td>'
				. '<td width="160" align="left" class="description"><img src="images/gbar.gif" width="%WIDTH%" height="10" border="0"></td>'
				. '<td width="174" align="left" nowrap class="description">%COUNT%&nbsp;</td>';
	enq_graph_all($enquete_id, $top_page, $scale, $html, true);
} else {
	echo '<tr><td>　アンケートは現在ありません。</td></tr>';
}
?>
    </table></td>
  </tr>
  <tr>
    <td align="right"><table border="0" cellspacing="0" cellpadding="3" class="header_text">
      <?
if($fno) {
	echo("
<tr> 
<td><img src='images/common_my/bt_03.gif' width='12' height='11'></td>
<td><a href='mk/survey/enq/mk_enqresult.php' target='_blank'>性・未既婚別の結果を見る</a></td>
</tr>
	");
}
?>

     <tr>
        <td><img src="images/common_my/bt_03.gif" width="12" height="11" /></td>
        <td><a href="mk/survey/enq/mk_pastenq_1.php" target="_blank">過去の１万人アンケートを見る</a></td>
      </tr>
    </table></td>
  </tr>
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

?>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="20" /></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td><img src="images/mkk_news/title02.gif" alt="事務局からのお知らせ" width="553" height="24" /></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td><p>きかせて・net からのお知らせです。</p></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td><img src="images/mkk_news/title03.gif" alt="最新のお知らせ" width="176" height="15" /></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
        <tr>
          <td class="right_menu_title"><?=$title?>
          </td>
          <td align="right" class="nor_text"><?=$date?>
          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td bgcolor="#e5eaf0" class="nor_text" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="10" class="nor_text">
        <tr>
          <td><?=nl2br($message)?></td>
        </tr>
    </table></td>
  </tr>
<?
}
?>
  <tr>
    <td><img src="images/common/spacer.gif" width="10" height="25" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table></td>
</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="10"></td>
</tr>
</table>
<!--footer start-->

<? marketer_footer(BLUE) ?>

