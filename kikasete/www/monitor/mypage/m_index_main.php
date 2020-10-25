<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
include("$inc/mn_header2.php");
include("$inc/mn_mypage.php");
include("$inc/mn_enquete.php");
include("$inc/mn_define.php");
$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];
?>
<?monitor_menu()?>

<?//jeon_start seq=ad2
$sql = "select mn_status,mn_mail_addr from t_monitor where mn_monitor_id=$monitor_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
//jeon_end seq=ad2
?>
<!-- コンテンツ本体のソースはこの間に表記 -->
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<!--jeon_start seq=ad2-->
			<?if($fetch->mn_status==2){?>
			<tr>
				<td>
				<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
				<tr>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<td  bgColor=#e9edf1>
				<font color="red">現在ご登録いただいているメールアドレスへのメールが届かない状態となっています。</font><br>
				Ｍｙページにアクセスいただきありがとうございます。<br>
				現在ご登録いただいているメールアドレスは<?=$fetch->mn_mail_addr?>です。<p>

				こちらのアドレスは、きかせて・netからのご案内メールが届かない状態となっております。<br>
				現在ご登録されているメールアドレスをご確認いただき、情報の更新を行ってください。<p>
				
				現在のメールをお送りできない状態が現在より半年が経過した場合、モニター継続の意志がないとみなし、誠に勝手ながら退会処理をさせていただきます。<br>
				</td>
			</tr>
			</table>	
				</td>
			</tr>
			<?}?>
			<!--jeon_end seq=ad2-->
			<TR bgColor=#c0e2fa>
				<TD><IMG height=29 alt=現在参加できるアンケート src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
					<SPAN class=mysttl> 現在参加できるアンケート </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<?
// 1万人アンケート表示
$sub1 = "SELECT em_sum FROM t_enquete_sum WHERE em_enquete_id=en_enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0";
$sql = "SELECT en_enquete_id,en_title,en_end_date,an_monitor_id,($sub1) AS an_count"
		. " FROM t_enquete LEFT JOIN t_answer ON en_enquete_id=an_enquete_id AND an_monitor_id=$monitor_id"
		. " WHERE en_enq_kind=1 AND en_status=5"
		. " ORDER BY en_start_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$enquete_exist = true;
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>１万人アンケート</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/bg_blue.gif>お題</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>回答期限</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>参加人数</TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->en_enquete_id;
		$count = number_format($fetch->an_count);
		if ($fetch->an_monitor_id) {
			$title = htmlspecialchars($fetch->en_title);
			$end_date = '回答済み';
		} else {
			$title = "<a href='m_repltop.php?enquete_id=$enquete_id'>" . htmlspecialchars($fetch->en_title) . '</a>';
			$end_date = format_date($fetch->en_end_date) . 'まで';
		}
?>
			<TR>
				<TD width=42><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD>Q：<?=$title?></TD>
				<TD align=middle><?=$end_date?></TD>
				<TD align=middle><?=$count?>人 </TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
<?
	}
?>
			</TR>
		</TBODY>
	</TABLE>
<?
}
// Myパートナー表示
$sql = "SELECT ppj_pjt_id,ppj_room_name,ppj_room_start,ppj_room_end"
		. " FROM t_mp_monitor"
		. " JOIN t_mp_pjt ON ppj_pjt_id=pmn_pjt_id"
		. " WHERE pmn_monitor_id=$monitor_id AND pmn_admin_choice=2 AND pmn_icp_member_id IS NOT NULL AND ppj_status=11"
		. " ORDER BY ppj_pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>会議室アンケート</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/bg_blue.gif>会議室名</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>開催期間</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>Webで発言する</TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>

<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
			<TR>
				<TD width=42><?=htmlspecialchars($fetch->ppj_room_name)?></TD>
				<TD background=<?=$img?>/bg_blue.gif><?=format_date($fetch->ppj_room_start)?>〜<?=substr(format_date($fetch->ppj_room_end), 5)?></TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif><a href="m_mp_room.php?pjt_id=<?=$fetch->ppj_pjt_id?>">会議室へ</a></TD>
			</TR>

<?=format_date($fetch->ppj_room_start)?>〜<?=substr(format_date($fetch->ppj_room_end), 5)?>
<a href="m_mp_room.php?pjt_id=<?=$fetch->ppj_pjt_id?>">会議室へ</a>// 会議室URL
<?
	}
?>
			</TR>
		</TBODY>
	</TABLE>
<?
}
// Myアンケート表示
$sql = "SELECT en_enquete_id,en_status,en_enq_kind,en_enq_type,en_title,en_start_date,en_end_date,an_enquete_id,en_myenq_eid"
		. " FROM (t_enquete JOIN t_enquete_list ON en_enquete_id=el_enquete_id)"
		. " LEFT JOIN t_answer ON el_enquete_id=an_enquete_id AND el_monitor_id=an_monitor_id"
		. " WHERE (en_status=5 AND en_enq_kind IN (2,3,4,5,6,7) OR en_enq_kind=7 AND en_status=7 AND en_end_date + interval '3 days'>=current_date) AND en_enq_type IN (1,2) AND el_monitor_id=$monitor_id AND el_status=1"
		. " ORDER BY en_start_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);

// PROアンケートサンプル数上限チェック
$enquete_ary = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if (!$fetch->an_enquete_id) {
		if ($fetch->en_enq_kind == 6) {
			if (!check_pro_enquete($monitor_id, $fetch->en_enquete_id, $fetch->en_start_date))
				continue;
		}
	}
	$enquete_ary[] = $fetch;
}

if (count($enquete_ary)) {
?>
<BR>
<form>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
			  <TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>通常アンケート<div align="right"><button onClick="javascript:location.reload()">更新</button></div></TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/bg_blue.gif>アンケートタイトル</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>アンケートの種類</TD>
				<TD align=middle width=100 background=<?=$img?>/bg_blue.gif>回答期限</TD>
			</TR>

<?
	foreach ($enquete_ary as $fetch) {
		$enquete_id = $fetch->en_enquete_id;

		$enq_type = decode_enq_type($fetch->en_enq_type);
		if ($fetch->an_enquete_id) {
			$title = htmlspecialchars($fetch->en_title);
			$end_date = '回答済み';
		} else {
			/*$url = ($fetch->en_enq_type == 1) ? 'm_remlenq2.php' : 'm_remlenq3a.php';
			if ($fetch->en_enq_kind == 3) { // Projectアンケート 2006/06/07
			    if ($fetch->en_enq_type == 1) { // メールアンケート
			        $title = "<a href='$url?enquete_id=$enquete_id'>" . htmlspecialchars($fetch->en_title) . '</a>';
			    } else { //　ＷＥＢアンケート
                    $title = "<a href='m_index.php' target='_top'>" . htmlspecialchars($fetch->en_title) . '</a>';
			    }
			} else
			    $title = '<a href="#" onClick="javascript:window.open('."'$url?enquete_id=$enquete_id', '_blank', 'resizable=yes, scrollbars=yes, toolbar=no, menubar=no')".'">' . htmlspecialchars($fetch->en_title) . '</a>';
			*/
			
			// --- SEQ AD3 START --------------------------------------------------------------------------------
			// WEBアンケートをメールアンケートの画面へ送るためアドレスを修正
			$url = ($fetch->en_enq_type == 1) ? 'm_remlenq2.php' : 'm_remlenq3a.php';
			
			if ($fetch->en_enq_kind == 3) { // Projectアンケート 2006/06/07
			    
			    if ($fetch->en_enq_type == 1) { // メールアンケート
			        $title = "<a href='$url?enquete_id=$enquete_id'>". htmlspecialchars($fetch->en_title) . '</a>';
			    } else if ($fetch->en_enq_type == 2){ //　ＷＥＢアンケート
                    //Original CODE : $title = "<a href='m_index.php' target='_top'>" . htmlspecialchars($fetch->en_title) . '</a>';
			    	$url='m_remlenq2.php';
                    $title = "<a href='$url?enquete_id=$enquete_id&en_type=web'>". htmlspecialchars($fetch->en_title) . '</a>';
			    }
			    
			} else {
			    if ($fetch->en_enq_type == 1) { // メールアンケート
			        $title = "<a href='$url?enquete_id=$enquete_id'>". htmlspecialchars($fetch->en_title) . '</a>';
			    }else{
					// myアンケート(DRS)の処理
					if ($fetch->en_enq_kind == 7) {
						$sql = "SELECT mn_sex, mn_age, mn_mikikon, mn_jitaku_area"
							. " FROM t_enq_monitor"
							. " WHERE mn_enquete_id = $enquete_id AND mn_monitor_id = $monitor_id";
						$result2 = db_exec($sql);
						if (pg_numrows($result2) == 0) {
							$sql = "SELECT mn_sex, DATE_PART('Y', AGE('$fetch->en_start_date', mn_birthday)) AS mn_age, mn_mikikon, mn_jitaku_area"
								. " FROM t_monitor"
								. " WHERE mn_monitor_id = $monitor_id";
							$result2 = db_exec($sql);
						}
						$fetch2 = pg_fetch_object($result2, 0);
						$close_url = urlencode (($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/monitor/mypage/m_close.php');
						$url = MYENQ_URL . "?eid=$fetch->en_myenq_eid&monitorId=$monitor_id&enqId=$enquete_id&sex=$fetch2->mn_sex&age=$fetch2->mn_age&marriage=$fetch2->mn_mikikon&pref=$fetch2->mn_jitaku_area&close_url=$close_url";
					} else {
						$url .= "?enquete_id=$enquete_id";
					}
					if ($fetch->en_status == 5)
				    	$title = "<a href=\"#\" onClick=\"javascript:window.open('$url', '_blank', 'resizable=yes, scrollbars=yes, toolbar=no, menubar=no')\">" . htmlspecialchars($fetch->en_title) . '</a>';
					else
						$title = htmlspecialchars($fetch->en_title);
			    }
			}
			// --- SEQ AD3 END ----------------------------------------------------------------------------------
			if ($fetch->en_status == 7)
				$end_date = '終了';
			else
				$end_date = format_date($fetch->en_end_date) . 'まで';
		}
?>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
			<TR>
				<TD width=42><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD>Q：<?=$title?></TD>
				<TD align=middle><?=$enq_type?></TD>
				<TD align=middle><?=$end_date?></TD>
			</TR>
<?
		$enquete_exist = true;
	}
?>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
			</TR>
		</TBODY>
	</TABLE>
    <div align="right">※回答したにもかかわらず、回答済みにならない場合は更新ボタンをクリックしてください。</div>
    <p>&nbsp; </p>
</form>
<?
}

// アンケートが無い場合

if (!$enquete_exist) {
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>現在参加できるアンケートはありません</TD>
			</TR>
		</TBODY>
	</TABLE>
<?
}
?>

<?
// 事務局からのお知らせ
$sql = "SELECT cs_date,cs_title,cs_message FROM t_center_msg WHERE cs_monitor_mypage AND cs_status=0 AND (cs_start_date IS NULL OR cs_start_date<=CURRENT_TIMESTAMP) AND (cs_end_date IS NULL OR cs_end_date>=DATE_TRUNC('day', CURRENT_TIMESTAMP)) ORDER BY cs_date DESC,cs_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
?>
<br>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD><IMG height=29 alt=事務局からのお知らせ src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
					<SPAN class=mysttl> 事務局からのお知らせ </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<?
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle><?=htmlspecialchars($fetch->cs_title)?></TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/bg_blue.gif><?=nl2br($fetch->cs_message)?></TD>
			</TR>
<?
	}
?>
			</TR>
		</TBODY>
	</TABLE>
<?
}

// アフィリエイト表示
$sql = "SELECT afm_affiliate_mgr_id,afm_affiliate_type,afm_condition,afm_appl_end_date,afm_affiliate_end_date,afm_appl_count,af_affiliate_id,af_status,af_parent_affiliate_id"
		. " FROM t_affiliate_mgr"
		. " LEFT JOIN t_affiliate ON af_affiliate_mgr_id=afm_affiliate_mgr_id AND af_monitor_id=$monitor_id AND af_status<>3"
		. " WHERE (af_affiliate_id IS NULL AND current_date BETWEEN afm_appl_start_date AND afm_appl_end_date)"
		. " OR (af_affiliate_id IS NOT NULL AND current_date BETWEEN afm_affiliate_start_date AND afm_affiliate_end_date)"
		. " ORDER BY afm_affiliate_mgr_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if($nrow > 0) {
?>
<br>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD>
					<IMG height=29 alt=アフィリエイト src="<?=$img?>/my_affiliate.gif" width=45 align=absMiddle>
					<SPAN class=mysttl>アフィリエイト </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<?
}
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$affiliate_mgr_id = $fetch->afm_affiliate_mgr_id;

	if ($fetch->af_affiliate_id == null) {
		// アフィリエイト未申請または差戻し
		if ($fetch->afm_affiliate_type == AFFILIATE_WEB) {
			// アフィリエイト未申請または差戻し（Web）
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>Webで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>先日のきかせて・net通信で紹介しました、「Webで紹介」を募集しています。<br>応募条件は「<?=htmlspecialchars($fetch->afm_condition)?>」です。<BR>新たなモニターさんを紹介いただいた場合、１人につき１００ポイント差し上げます。<BR>ぜひぜひ、ご応募ください。</TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD>応募は<a href="introduction.php?id=<?=$affiliate_mgr_id?>">こちら</a>から。募集期間は<?=format_date($fetch->afm_appl_end_date)?>までです。</TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
<?
		} elseif ($fetch->afm_affiliate_type == AFFILIATE_MAIL) {
			// アフィリエイト未申請または差戻し（メール）
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>メールで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>先日のきかせて・net通信で紹介しました、「メールでお友達を紹介」いただける方を募集しています。<br>応募条件は「<?=htmlspecialchars($fetch->afm_condition)?>」です。<br>新たなモニターさんを紹介いただいた場合、１人につき１００ポイント差し上げます。<br>ぜひぜひ、ご応募ください。<br></TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD>応募は<a href="m_affi_mail1.php?id=<?=$affiliate_mgr_id?>">こちら</a>から。募集期間は<?=format_date($fetch->afm_appl_end_date)?>までです。</TD>
			</TR>
<?
		}
?>
		</TBODY>
	</TABLE>
<?
	} elseif ($fetch->af_status == 0) {
		// アフィリエイト審査中
?>
<?
		if ($fetch->afm_affiliate_type == AFFILIATE_WEB) {
			// アフィリエイト審査中（Web）
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>Webで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>ご応募ありがとうございます。只今審査中です。アフィリエイトをご依頼する方のみ、後日Myページ内にてお知らせをさせて頂きます。<br></TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
<?
		} elseif ($fetch->afm_affiliate_type == AFFILIATE_MAIL) {
			// アフィリエイト審査中（メール）
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>メールで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>ご応募ありがとうございます。只今審査中です。<BR>アフィリエイトをご依頼する方のみ、後日Myページ内にてお知らせをさせて頂きます。<br>審査結果を今しばらくお待ち下さい。<br></TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
			<TR>
				<TD width=42 height=3><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD background=<?=$img?>/my_bg_dot.gif height=8><IMG height=3 src="<?=$img?>/spacer.gif" width=42></TD>
			</TR>
<?
		}
?>
		</TBODY>
	</TABLE>
<?
	} elseif ($fetch->af_status == 1) {
?>
<?
		if ($fetch->afm_affiliate_type == AFFILIATE_WEB) {
			// アフィリエイト承認済み（Web）
			$fetch2 = get_system_info('sy_url_monitor');
			$url_monitor = $fetch2->sy_url_monitor;
?>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>Webで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>アフィリエイトプログラムにご参加いただきありがとうございました。<br><br><?=htmlspecialchars($monitor_name)?>さんにきかせて・netのご紹介をお願いしたいと思っております。<br>どうぞよろしくお願いいたします。</TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
		<BR>
			<TR>
				<TD width=45><IMG height=8 src="<?=$img?>/spacer.gif" width=45></TD>
				<TD align=middle bgColor=#f5f5f5>
					<TABLE cellSpacing=0 cellPadding=0 width="95%" border=0>
						<TBODY>
							<TR>
								<TD vAlign=top align=left width=10 bgColor=#e9edf1 height=10><IMG height=10 src="<?=$img?>/my_lt.gif" width=10></TD>
								<TD bgColor=#e9edf1 colSpan=2></TD>
								<TD vAlign=top align=right width=10 bgColor=#e9edf1 height=10><IMG height=10 src="<?=$img?>/my_rt.gif" width=10></TD>
							</TR>
							<TR>
								<TD bgColor=#e9edf1></TD>
								<TD vAlign=top width=8 bgColor=#e9edf1><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=2><BR></TD>
								<TD class=fs12_lh130 bgColor=#e9edf1><?=htmlspecialchars($monitor_name)?>さんの紹介用URL<BR>
								<?=$url_monitor?>affiliate.php?id=<?=$fetch->af_affiliate_id?></A><BR></TD>
								<TD bgColor=#e9edf1></TD></TR>
							<TR>
								<TD bgColor=#e9edf1 height=10></TD>
								<TD bgColor=#e9edf1></TD>
								<TD bgColor=#e9edf1></TD>
								<TD bgColor=#e9edf1></TD>
							</TR>
							<TR>
								<TD bgColor=#e9edf1></TD>
								<TD vAlign=top bgColor=#e9edf1><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=2><BR><BR></TD>
								<TD class=fs12_lh130 bgColor=#e9edf1>ご利用いただけるバナー <BR><IMG height=45 alt=きかせて・netバナー src="<?=$img?>/new_kkstbanner2.gif" width=135><BR>もしくは　<A href="http://www.kikasete.net/monitor/image/new_kkstbanner2.gif" target=_blank>http://www.kikasete.net/monitor/image/new_kkstbanner2.gif</A>　をお使いください。<BR></TD>
								<TD bgColor=#e9edf1></TD>
							</TR>

							<TR>
								<TD bgColor=#e9edf1 height=10></TD>
								<TD bgColor=#e9edf1></TD>
								<TD bgColor=#e9edf1></TD>
								<TD bgColor=#e9edf1></TD>
							</TR>
							<TR>
								<TD bgColor=#e9edf1></TD>
								<TD vAlign=top width=8 bgColor=#e9edf1><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=7 align=absMiddle vspace=2><BR></TD>
								<TD class=fs12_lh130 bgColor=#e9edf1>紹介でポイントを獲得するには？
								<BR>新たなモニターさんを紹介いただいた場合、１人につき１００ポイント差し上げます。</TD>
								<TD bgColor=#e9edf1></TD></TR>
							<TR>
								<TD vAlign=bottom align=left bgColor=#e9edf1 height=10><IMG height=10 src="<?=$img?>/my_lb.gif" width=10></TD>
								<TD bgColor=#e9edf1 colSpan=2></TD>
								<TD vAlign=bottom align=right bgColor=#e9edf1><IMG height=10 src="<?=$img?>/my_rb.gif" width=10></TD>
							</TR>
						</TBODY>
					</TABLE>
				<BR>
				</TD>
			</TR>
		</TBODY>
	</TABLE>
<?
		} elseif ($fetch->afm_affiliate_type == AFFILIATE_MAIL) {
			// アフィリエイト承認済み（メール）
			sscanf($fetch->afm_affiliate_end_date, "%d-%d-%d", &$year, &$month, &$day);

			if (!$fetch->af_parent_affiliate_id) {
				// 親
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>メールで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>先日はきかせて・net紹介の紹介制度に応募いただきありがとうございます。<br>あなた様にきかせて・netのご紹介をお願いしたと思っておりますので、<br><a href="m_affi_mail_new.php?id=<?=$fetch->af_affiliate_id?>" target="_blank">こちら</a>より、ご紹介いただく方へメールを送信ください。<br><br>※　なお、<?=$month?>月<?=$day?>日にこのメール送信フォームを削除します。</TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
		</TBODY>
	</TABLE>
<?
			} else {
				// 子以降
				$sql = "SELECT mn_name1 FROM t_affiliate JOIN t_monitor ON mn_monitor_id=af_monitor_id WHERE af_affiliate_id=$fetch->af_parent_affiliate_id";
				$parent_monitor_name = db_fetch1($sql);
?>
<BR>
	<TABLE cellSpacing=2 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD bgColor=#e9edf1 colSpan=3 height=30><IMG height=8 hspace=2 src="<?=$img?>/blue_arrow2.gif" width=8 align=absMiddle>メールで紹介</TD>
			</TR>
			<TR>
				<TD width=42><IMG height=8 src="<?=$img?>/spacer.gif" width=42></TD>
				<TD colspan="3" background=<?=$img?>/bg_blue.gif>先日<?=htmlspecialchars($parent_monitor_name)?>さんにご紹介いただきましたが、もしご友人の方で<br><a href="m_affi_mail_new.php?id=<?=$fetch->af_affiliate_id?>" target="blank">こちら</a>より、ご紹介いただく方へメールを送信ください。<br><br>※　なお、<?=$month?>月<?=$day?>日にこのメール送信フォームを削除します</TD>
				<TD><IMG height=8 src="<?=$img?>/bg_blue.gif"></TD>
			</TR>
		</TBODY>
	</TABLE>
<?
			}
		}
?>
<?
	}
}
?>
<!-- アフィリエイト ここまで -->
	</TABLE>
</TD>
<!-- コンテンツ本体のソースはこの間に表記 -->
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
