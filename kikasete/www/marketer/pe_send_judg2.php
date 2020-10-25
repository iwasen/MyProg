<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mail.php");
include("$inc/enq_mail.php");
include("$inc/enq_send.php");
include("$inc/encrypt.php");
include("$inc/pro_enq_target.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セル表示
function disp_cell(&$cell, &$disp_cell, $color_cell = false) {
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
<table width="100%" border="0" cellspacing="1" cellpadding="5" frame="box">
<?
	if ($cell->age_option == 2) {
?>
	<tr bgcolor="#eeeeee" align="center">
		<td nowrap width="5%" rowspan=2>&nbsp;</td>
		<td nowrap width="5%">10代</td>
		<td nowrap width="10%" colspan=2>20代</td>
		<td nowrap width="10%" colspan=2>30代</td>
		<td nowrap width="10%" colspan=2>40代</td>
		<td nowrap width="10%" colspan=2>50代</td>
		<td nowrap width="10%" colspan=2>60代</td>
		<td nowrap width="5%" rowspan=2>70代<br>以上</td>
		<td nowrap width="5%" rowspan=2>合計</td>
	</tr>
	<tr bgcolor="#eeeeee" align="center">
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
		<td>前</td>
		<td>後</td>
	</tr>
<?
	} elseif ($cell->age_option == 3) {
?>
	<tr bgcolor="#eeeeee" align="center">
		<td nowrap width="10%">&nbsp;</td>
		<td nowrap width="10%">10代</td>
		<td nowrap width="10%">20代</td>
		<td nowrap width="10%">30代</td>
		<td nowrap width="10%">40代</td>
		<td nowrap width="10%">50代</td>
		<td nowrap width="10%">60代</td>
		<td nowrap width="10%">70代以上</td>
		<td nowrap width="10%">合計</td>
	</tr>
<?
	} else {
?>
	<tr bgcolor="#eeeeee" align="center">
		<td nowrap width="10%">&nbsp;</td>
		<td nowrap width="10%">年代指定なし</td>
		<td nowrap width="10%">合計</td>
	</tr>
<?
	}

	$sum_row = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
	<tr bgcolor="#ffffff">
		<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
<?
		$sum_col = 0;
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = $disp_cell[$sex][$age];
			$sum_col += $num;
			$sum_row[$age] += $num;
?>
		<td bgcolor="<?=$color_cell ? $color_cell[$sex][$age] : '#eeeeee'?>" nowrap align="right"><?=number_format($num)?></td>
<?
		}
?>
		<td bgcolor="#eeeeee" nowrap align="right"><?=number_format($sum_col)?></td>
	</tr>
<?
	}
?>
	<tr bgcolor="#ffffff">
		<td bgcolor="#eeeeee" nowrap align="center">合計</td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sum_row[$age];
		$sum_col += $num;
?>
		<td bgcolor="#eeeeee" nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
		<td bgcolor="#eeeeee" nowrap align="right"><?=number_format($sum_col)?></td>
	</tr>
</table>
<?
}

$owner_id = $_SESSION['ss_owner_id'];

$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;
$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
$enquete_id = $pro_enq->enquete_id;
$enquete2_id = $pro_enq->enquete2_id;

if ($_SESSION['ss_pe_send_judg'] && $_POST['send_flag']) {
	unset($_SESSION['ss_pe_send_judg']);

	// 利用ネットワークの取得   2006/02/28 BTI
	$pro_enq_target = new pro_enq_target_class;
	$pro_enq_target->read_db($enquete_id);
	$target_flg = $pro_enq_target->target_flg;

	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

	$real_cell = $cell->get_send_cell(); // 目標サンプル数 2006/03/13 BTI
	$cell->send_num = unserialize($_POST['add_send_cell']);
	$send_cell = $cell->get_send_cell();
	$mail_cell = $cell->get_send_cell(); // BTI 2006/03/08
	$appearance_cell = $cell->get_sample_cell($pro_enq->enquete2_id, $pro_enq->enquete->start_date, $target_flg); // 現在のサンプル数 2006/03/13 BTI

	// きかせて
	if ($target_flg == 't') {
		// 追加発信候補メンバー抽出
		$member_ary = array();
		list($select, $join, $groupby) = $cell->get_sql($enquete->start_date);
		$where = $search->make_sql();
		$sql = "SELECT $select,mn_monitor_id"
				. " FROM t_monitor"
				. " $join"
				. " WHERE $where AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=mn_monitor_id)"
				. " ORDER BY RANDOM()";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sex = $fetch->sex;
			$age = $fetch->age;
			$rate = $fetch->rate;

			if ($send_cell[$sex][$age][$rate])
				$member_ary[$sex][$age][$rate][] = $fetch->mn_monitor_id;
		}

		// 足りないセルに割り当て
		$assign_ary = array();
		for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
			for ($age = $age_s; $age <= $age_e; $age++) {
				for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
					while ($send_cell[$sex][$age][$rate] > 0) {
						$member_id = 0;
						if (isset($member_ary[$sex][$age][$rate]) && count($member_ary[$sex][$age][$rate]) > 0)
							$member_id = array_pop($member_ary[$sex][$age][$rate]);
						else {
							for ($rate2 = $rate_s; $rate2 <= $rate_e; $rate2++) {
								if (isset($member_ary[$sex][$age][$rate2]) && count($member_ary[$sex][$age][$rate2]) > 0)
									$member_id = array_pop($member_ary[$sex][$age][$rate2]);
							}
						}
						if ($member_id) {
							$assign_ary[] = $member_id;
							$send_cell[$sex][$age][$rate]--;
						} else
							break;
					}
				}
			}
		}

		if (count($assign_ary) != 0) {
			db_begin_trans();

			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE mn_monitor_id IN (" . join(',', $assign_ary) . ")";

			// アンケートメール生成
			$mail_text = make_enquete_mail($enquete, $pro_enq->mail_header, $pro_enq->mail_contents, $pro_enq->mail_footer, $enquete2->point, $pro_enq->finding_flag == DBTRUE ? $enquete->point : 0);

			// モニターにアンケートメール配信
			$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $pro_enq->mail_title, $mail_text, $add_send_count ? $add_send_count : 1);

			// アンケート対象者モニター属性を保存
			save_enq_monitor_data($enquete_id, $enquete2_id);

			// 実配信数設定、処理日時、カウンタ設定
			$rec['mep_real_send'] = "mep_real_send+$send_count";
			$rec['mep_send_count'] = 9;
			$rec['mep_last_send_date'] = "date_trunc('hour', current_timestamp)";
			$rec['mep_add_send_date'] = 'CURRENT_TIMESTAMP';
			db_update('t_pro_enquete', $rec, "mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq->pro_enq_no");

			db_commit_trans();

			// エージェントに追加発信実行確認メール送信
			get_mail_template('pe_judg2', $subject, $from, $cc, $bcc, $repty_to, $body);
			if ($subject != '') {
				$sql = "SELECT mr_mail_addr,mr_name1,ag_mail_addr"
					. " FROM t_marketer"
					. " LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
					. " WHERE mr_marketer_id=$pro_enq->marketer_id";
				$result = db_exec($sql);
				if (pg_numrows($result)) {
					$fetch = pg_fetch_object($result, 0);

					$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1, $body);
					$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
					$body = str_replace('%TITLE%', $enquete->title, $body);
					$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
					$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
					send_mail($subject, $fetch->ag_mail_addr, $from, $body, $cc, $bcc, $reply_to);
				}
			}

			// 事務局に追加発信実行確認メール送信
			get_mail_template('pe_judg3', $subject, $from, $cc, $bcc, $repty_to, $body);
			if ($subject != '') {
				$sql = "SELECT mr_mail_addr,mr_name1"
					. " FROM t_marketer"
					. " WHERE mr_marketer_id=$pro_enq->marketer_id";
				$result = db_exec($sql);
				if (pg_numrows($result)) {
					$fetch = pg_fetch_object($result, 0);

					$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1, $body);
					$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
					$body = str_replace('%TITLE%', $enquete->title, $body);
					$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
					$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);

					$fetch = get_system_info('sy_myenq_to');
					send_mail($subject, $fetch->sy_myenq_to, $from, $body, $cc, $bcc, $reply_to);
				}
			}
		}
	} else {    // GMOなら追加発信せず、事務局・エージェントにアラートメール配信
		// 追加発信申請ボタン押下時、事務局及びエージェント宛にアラーとメールを送信 200/03/08 BTI
		$FINDING_ADDSAMPLE = "";
		$isFirst = true;
		for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
			if($sex == 0) $sex_txt = "";
			else {
				if($sex == 1) $sex_txt = "男性";
				if($sex == 2) $sex_txt = "女性";
			}

			for ($age = $age_s; $age <= $age_e; $age++) {
				if($age == 0) $age_txt = "";
				else {
					if($age_e == 8) {
						if($age == 1) $age_txt = "10代未満";
						if($age == 2) $age_txt = "10代";
						if($age == 3) $age_txt = "20代";
						if($age == 4) $age_txt = "30代";
						if($age == 5) $age_txt = "40代";
						if($age == 6) $age_txt = "50代";
						if($age == 7) $age_txt = "60代";
						if($age == 8) $age_txt = "70代以上";
					}else {
						if($age == 1 )  $age_txt = "10代未満";
						if($age == 2 )  $age_txt = "10代前";
						if($age == 3 )  $age_txt = "10代後";
						if($age == 4 )  $age_txt = "20代前";
						if($age == 5 )  $age_txt = "20代後";
						if($age == 6 )  $age_txt = "30代前";
						if($age == 7 )  $age_txt = "30代後";
						if($age == 8 )  $age_txt = "40代前";
						if($age == 9 )  $age_txt = "40代後";
						if($age == 10) $age_txt = "50代前";
						if($age == 11) $age_txt = "50代後";
						if($age == 12) $age_txt = "60代前";
						if($age == 13) $age_txt = "60代後";
						if($age == 14) $age_txt = "70代以上";
					}
				}
				for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
					if( $mail_cell[$sex][$age][$rate] > 0) {
						if ( $isFirst ) {
							$isFirst = false;
						}else {
							$FINDING_ADDSAMPLE .= "  ";
						}
						 // 2006/03/13 BTI
						$real_rate = $real_cell[$sex][$age][0] ? ($appearance_cell[$sex][$age] / $real_cell[$sex][$age][0]) * 100 : 0;
						$real_rate = round($real_rate, 2);

						if( $age_txt.$sex_txt != "" ) {
							$FINDING_ADDSAMPLE .= $age_txt.$sex_txt." : ".$mail_cell[$sex][$age][$rate]."(". $real_rate . "%)\n";
						}else {
							$FINDING_ADDSAMPLE .= "\n  ".$mail_cell[$sex][$age][$rate]."(". $real_rate . "%)\n";
						}
					}
				}
			}
		}

		// 事務局のメールアドレス
		$fetch = get_system_info('sy_mail_notify_to');
		$to_addr = $fetch->sy_mail_notify_to;

		get_mail_template('pe_alrt', $subject, $from, $cc, $bcc, $repty_to, $body);
		if ($subject != '') {
			$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$pro_enq->marketer_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$body = str_replace('%DATE_TIME%',  date("Y/m/d H:i"), $body);
				$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1." ".$fetch->mr_name2, $body);
				$body = str_replace('%TITLE%', $enquete->title, $body);
				$body = str_replace('%COMPANY_NAME%', $fetch->mr_kinmu_name, $body);
				$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
				$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
				$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
				$body = str_replace('%APPEARANCE_RATIO%', $pro_enq->appearance_ratio, $body);
				$body = str_replace('%FINDING_ADDSAMPLE%', $FINDING_ADDSAMPLE, $body);
				send_mail($subject, $to_addr, $from, $body, $cc, $bcc, $reply_to);
			}
		}
	}

	$add_send_flag = true;
}
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="538" valign="top"><img src="images/mkk_pe/title_06.gif" width="200" height="28" border="0"><br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="4" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
<?
if ($add_send_flag) {
?>
	<tr>
		<td><span style="font-weight:bold;">Ｐｒｏアンケートの追加発信を実行しました。</span></td>
	</tr>
	<tr>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
<?
}
?>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" width="99%">
				<tr>
					<td>
						<table border="0" cellspacing="1" cellpadding="0" width=100% frame="box">
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">追加発信必要数</td>
								<td><? disp_cell($cell, unserialize($_POST['add_send_cell']), unserialize($_POST['color_cell'])); ?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">回収にかかる費用</td>
								<td>&nbsp;<?=number_format($_POST['add_price'])?> 円　※赤色のセルが追加料金の対象となります。</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
