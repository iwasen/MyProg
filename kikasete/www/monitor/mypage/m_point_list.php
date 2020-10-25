<?
$top = './..';
$inc = "$top/../inc";
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
?>
<? monitor_menu() ?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<?
$monitor_id = $_SESSION['ss_monitor_id'];

$sql = "SELECT mn_name1,mp_curr_point,mp_enq_history,mp_point_period - interval '1 day' AS point_period"
		. " FROM t_monitor JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
		. " WHERE mn_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$name1 = htmlspecialchars($fetch->mn_name1);
	get_chara_condition($fetch->mp_enq_history, $chara_text);

	$curr_point = number_format($fetch->mp_curr_point);

	sscanf($fetch->point_period, "%d-%d-%d", &$year, &$month, &$day);
}
?>
<!-- ポイントリスト -->
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　ポイント</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<?
// ポイント数取得
$sql = "SELECT mp_curr_point,mp_total_point,mp_haimail_point FROM t_monitor_point WHERE mp_monitor_id=$monitor_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$curr_point = number_format($fetch->mp_curr_point);
	$total_point = number_format($fetch->mp_total_point);
	$kikasete_point = number_format($fetch->mp_total_point - $fetch->mp_haimail_point);
	$haimail_point = number_format($fetch->mp_haimail_point);
} else {
	$curr_point = 0;
	$total_point = 0;
	$kikasete_point = 0;
	$haimail_point = 0;
}
?>
<?
$sql = "SELECT mn_name1,mp_curr_point,mp_enq_history,mp_point_period - interval '1 day' AS point_period"
		. " FROM t_monitor JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
		. " WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	sscanf($fetch->point_period, "%d-%d-%d", &$year, &$month, &$day);
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>■現在のポイント：<SPAN class=point_b><?=$curr_point?></SPAN></td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan=2>◆累計ポイント：<SPAN class=point_b><?=$total_point?></SPAN>ポイント</td>
				</tr>
				<tr>
					<td colspan=2>◆累計ポイント内訳</td>
				</tr>
				<tr>
					<td>　きかせて・netポイント</td>
					<td>：<SPAN class=point_b><?=$kikasete_point?></SPAN>ポイント</td>
				</tr>
				<tr>
					<td>　はいめーるポイント</td>
					<td>：<SPAN class=point_b><?=$haimail_point?></SPAN>ポイント</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0">
<!--
  <tr> 
    <td width="100%">
    <TABLE cellSpacing=0 cellPadding=1 bgColor=#ff6600 border=0>
            <TD align=middle> 
              <TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
                <TBODY>
                  <TR bgColor=#fffaea>
                    <TD bgColor=#fffaea><FONT color=black size=2>ポイキャラ<b><?=htmlspecialchars($_SESSION['ss_chara_name'])?></b>（いまは<b>【<?=$chara_text?>】</b>）からのお知らせ<br><br>現在<?=$name1?>さんのポイントは≪<?=$curr_point?>ポイント≫で、<br>ポイントの有効期限は<?=$year?>年<?=$month?>月末日までだよ。<br><br>あ、ポイントの有効期限を延ばすには<a href="faq.php#17" target="_blank">ここ</a>をみてね。<br><br>２０００ポイント以上だったら景品に変えられるから、<br><a href="m_point.php">こっち</a>から申請してね。</FONT></TD>
                    <TD align=middle><IMG height=104 src="images/<?=$_SESSION['ss_chara_image']?>" width=69></TD>
                  </TR>
              </TBODY>
            </TABLE>
          </TD>
    </TABLE>
    </td>
  </tr>
-->
  <tr> 
    <td><font color="#8584d2"><strong>ポイント履歴</strong></font></td>
  </tr>
  <tr>
    <td colspan="2">
    <table width="100%" border="1" align="center" bordercolor="#8584d2" bgcolor="#e8f8ff">
        <tr bgcolor="#8E94DB">
          <td width="10%" align="center"><font size="2" color="#FFFFFF">日付</font></td>
          <td width="10%" align="center"><font size="2" color="#FFFFFF">タイプ</font></td>
          <td width="20%" align="center"><font size="2" color="#FFFFFF">ポイント種類</font></td>
          <td width="35%" align="center"><font size="2" color="#FFFFFF">アンケート内容</font></td>
          <td width="15%" align="center"><font size="2" color="#FFFFFF">ポイント数</font></td>
          <td width="10%" align="center"><font size="2" color="#FFFFFF">失効日</font></td>
        </tr>
<?
function decode_point_kind($point_kind) {
	switch ($point_kind) {
	case PT_ENQUETE:
		return 'アンケートポイント';
	case PT_MAIL_RECV:
	case PT_MAIL_PJT:
		return 'メール受信ポイント';
	case PT_MY_PARTNER:
		return 'Myパートナーポイント';
	case PT_IMPORT:
	case PT_JIMUKYOKU:
		return '事務局ポイント';
	case PT_AFFILIATE:
		return 'アフィリエイトポイント';
	case PT_KIRIN:
		return 'お酒と買い物ポイント';
	case PT_CAMPAIGN:
		return '更新キャンペーンポイント';
	case PT_EXPIRE:
		return '有効期限切れによるポイント失効';
	case PT_BIRTHDAY:
		return '登録情報更新ポイント';
	case PT_SEISAN:
		return '精算によるポイント利用';
	case PT_SEISAN_CANCEL:
		return '精算取消によるポイント返却';
	}
	return 'その他のポイント';
}

$offset = $page * 10;
$sql = "SELECT lp_point_kind,lp_point,lp_date,en_title,jb_category,lp_expire_date"
		. " FROM l_point"
		. " LEFT JOIN t_enquete ON en_enquete_id=lp_enquete_id"
		. " LEFT JOIN t_job ON jb_enquete_id=lp_enquete_id"
		. " WHERE lp_member_kind=1 AND lp_member_id=$monitor_id AND (lp_point>0 OR lp_point_kind IN (" . PT_EXPIRE . "," . PT_SEISAN . "))"
		. " ORDER BY lp_seq_no DESC"
		. " LIMIT 11 OFFSET $offset";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow && $i < 10; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
        <tr<?=$i % 2 ? ' bgcolor="#FFFFFF"' : ''?>> 
          <td align="center"><font size="2"><?=format_date($fetch->lp_date)?></font></td>
          <td align="center"><font size="2"><?=$fetch->jb_category == 2 ? 'はいめーる' : 'きかせて'?></font></td>
          <td><font size="2"><?=decode_point_kind($fetch->lp_point_kind)?></font></td>
          <td><font size="2"><?=$fetch->en_title == '' ? '<div align="center">-</div>' : htmlspecialchars($fetch->en_title)?></font></td>
          <td align="center"><font size="2"><?=number_format($fetch->lp_point)?></font></td>
          <td align="center"><font size="2"><?=$fetch->lp_expire_date ? format_date($fetch->lp_expire_date) : '-'?></font></td>
        </tr>
<?
}
?>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><div align="right">
<?
if ($page > 0) {
?>
			<a href="m_point_list.php?page=<?=$page - 1?>"><font color="#8584d2"><strong>前の１０件</strong></font></a>
<?
}
?>
			&nbsp;&nbsp;
<?
if ($nrow > 10) {
?>
			<a href="m_point_list.php?page=<?=$page + 1?>"><font color="#8584d2"><strong>次の１０件</strong></font></a>
<?
}
?>
		</div></td>
  </tr>
  <tr> 
    <td colspan="2"><font color="#8584d2"><strong>精算履歴</strong></font>（本日より過去3ヶ月分）</td>
  </tr>
  <tr> 
    <td colspan="2"><table width="100%" border="1" align="center" bordercolor="#8584d2" bgcolor="#e8f8ff">
        <tr bgcolor="#8E94DB">
          <td width="25%" align="center"><font size="2" color="#FFFFFF">申請日</font></td>
          <td width="25%" align="center"><font size="2" color="#FFFFFF">発送予定日</font></td>
          <td width="25%" align="center"><font size="2" color="#FFFFFF">交換アイテム</font></td>
          <td width="25%" align="center"><font size="2" color="#FFFFFF">交換ポイント数</font></td>
        </tr>
<?
$sql = "SELECT si_req_date,si_point,si_item"
		. " FROM t_seisan"
		. " WHERE si_member_kind=1 AND si_member_id=$monitor_id AND si_status<>2 AND si_req_date>=current_date - interval '3 month'"
		. " ORDER BY si_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	sscanf($fetch->si_req_date, "%d-%d-%d", &$year, &$month, &$day);
	if ($day > 20) {
		if (++$month > 12) {
			$year++;
			$month = 1;
		}
	}
?>
        <tr<?=$i % 2 ? ' bgcolor="#FFFFFF"' : ''?>> 
          <td width="25%" align="center"><font size="2"><?=format_date($fetch->si_req_date)?></font></td>
<?
if ($fetch->si_item == 5) {
?>
          <td width="25%" align="center"><font size="2">-</td>
<?
} else {
?>
          <td width="25%" align="center"><font size="2"><?=$year?>.<?=$month?>月末日予定</font></td> <!-- 発送は２０日締めの同月末日発送です。4.23日に申請の場合は５月末日発送となりますので、そのように表示をお願いします。 -->
<?
}
?>
          <td width="25%" align="center"><font size="2"><?=decode_seisan_item($fetch->si_item)?></font></td>
          <td width="25%" align="center"><font size="2"><?=number_format($fetch->si_point)?></font></td>
        </tr>
<?
}
?>
      </table></td>
  </tr>
</table>

<!-- コンテンツ本体のソースはこの間に表記 -->
		</TR>
	</TD>
	</TBODY>
</TABLE>
</BODY>
</HTML>