<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mn_tbl.php");
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
$cell = &$pro_enq->cell;

$send_flag = false;
$assumption_flag = false;

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

// 返信係数取得
$hour_a = round((time() - sql_time($pro_enq->first_send_date)) / (60 * 60));
if ($hour_a < 0)
	$hour_a = 0;
$sql = "SELECT mas_factor FROM m_addition_send WHERE mas_hour<=$hour_a ORDER BY mas_hour DESC LIMIT 1";
$reply_factor = db_fetch1($sql) / 100;

// ファインディングアンケート現サンプル数取得
$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$pro_enq->enquete_id AND an_valid_flag";
$finding_sample_num = db_fetch1($sql, 0);

// 本アンケート現サンプル数取得
$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$pro_enq->enquete2_id AND an_valid_flag";
$sample_num = db_fetch1($sql, 0);

// 現セル別ファインでリング数取得
$finding_sample_cell = $cell->get_sample_cell($pro_enq->enquete_id, $enquete->start_date);

// 現セル別サンプル数取得
$current_sample_cell = $cell->get_sample_cell($pro_enq->enquete2_id, $enquete->start_date);

// 設定セル別サンプル数取得
$set_sample_cell = $cell->send_num;

// 本アンケート現サンプル率計算
$answer_rate = $sample_num / $pro_enq->real_send;

if ($finding_sample_num != 0 && $sample_num != 0) {
	// 保障返信率チェック
	$send_ary = $cell->get_send_cell(true, $pro_enq->appearance_ratio);
	$finding_sample_ary = $cell->get_sample_cell($pro_enq->enquete_id, $pro_enq->enquete->start_date);
	$reply_rate_ary = $cell->get_reply_rete();
	$auto_send_ary = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			if ($send_ary[$sex][$age][0] != 0) {
				if ($finding_sample_ary[$sex][$age] / $reply_factor / $send_ary[$sex][$age][0] >= $reply_rate_ary[$sex][$age]) {
					$auto_send_ary[$sex][$age] = false;
				} else {
					$auto_send_ary[$sex][$age] = true;
				}
			}
		}
	}

	// 最終想定サンプル数,不足サンプル数,追加発信必要数計算
	$final_sample_cell = array();
	$deficit_sample_cell = array();
	$add_send_cell = array();
	$color_cell = array();
	$total_deficit_sample_num = 0;
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			// 最終想定サンプル数
			$final_sample_num = round($current_sample_cell[$sex][$age] / $reply_factor);
			$final_sample_cell[$sex][$age] = $final_sample_num;

			// 不足サンプル数
			$deficit_sample_num = $set_sample_cell[$sex][$age] - $final_sample_num;
			if ($deficit_sample_num < 0)
				$deficit_sample_num = 0;
			$deficit_sample_cell[$sex][$age] = $deficit_sample_num;

			// 追加発信必要数
			$add_send_cell[$sex][$age] = round($deficit_sample_num / $answer_rate);

			if ($deficit_sample_num)
				$send_flag = true;

			if (!$auto_send_ary[$sex][$age])
				$total_deficit_sample_num += $deficit_sample_num;

			if ($deficit_sample_num == 0)
				$color_cell[$sex][$age] = '#eeeeee';
			else {
				if ($auto_send_ary[$sex][$age])
					$color_cell[$sex][$age] = '#ccccff';
				else
					$color_cell[$sex][$age] = '#ffcccc';
			}
		}
	}

	// 前回配信より１２時間以内または実施中以外では追加配信不可
	if ($enquete->status != 5 || $pro_enq->send_count <= 1 || $pro_enq->send_count == 9)
		$send_flag = false;

	// 現出現率計算
	$appearance_rate = $sample_num / $finding_sample_num;

	// 最終想定ファインディング数計算
	$final_finding_num = round($finding_sample_num / $reply_factor);

	// 最終想定本アンケートサンプル数計算
	$final_sample_num = round($sample_num / $reply_factor);

	// ファインディング質問数取得
	$finding_question_num = $pro_enq->enquete->get_question_num();

	// 本アンケート質問数取得
	$question_num = $pro_enq->enquete2->get_question_num();

	// 想定料金計算
	$price1 = $pro_enq->get_price($final_finding_num, $finding_question_num, $final_sample_num, $question_num);

	// 追加発信後の料金計算
	$price2 = $pro_enq->get_price($final_finding_num + $total_deficit_sample_num / $appearance_rate, $finding_question_num, $final_sample_num + $total_deficit_sample_num, $question_num);

	$assumption_flag = true;
}

// 追加料金
$add_price = $price2 - $price1;

// 二重実行を防ぐためのフラグ
if ($send_flag)
	$_SESSION['ss_pe_send_judg'] = true;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

<script type="text/javascript">
<!--
function add_send(send_flag) {
	var f = document.form1;
	f.send_flag.value = send_flag;
	f.add_send_btn.disabled = true;
	f.no_add_send_btn.disabled = true;
	f.submit();
}
//-->
</script>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
			</table>

			<form method="post" name="form1" action="pe_send_judg2.php">
			<table border="0" cellspacing="0" cellpadding="0" width="99%">
				<tr>
					<td bgcolor="#eff7e8">■追加発信</td>
				</tr>
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
				<tr>
					<td>
						<table border="0" cellspacing="1" cellpadding="0" width=100% frame="box">
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">現在のファインディング回収数</td>
								<td><? disp_cell($cell, $finding_sample_cell); ?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">現在の本アンケート回収数</td>
								<td><? disp_cell($cell, $current_sample_cell); ?></td>
							</tr>
<?
if ($assumption_flag) {
?>
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">最終想定サンプル数</td>
								<td><? disp_cell($cell, $final_sample_cell); ?></td>
							</tr>
<?
}

if ($send_flag) {
?>
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">追加発信必要数</td>
								<td><? disp_cell($cell, $add_send_cell, $color_cell); ?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc" width="20%" align="center">回収にかかる費用</td>
								<td>&nbsp;<?=number_format($add_price)?> 円　※赤色のセルが追加料金の対象となります。</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
			<br>
<?
if ($send_flag) {
?>
			<input type="button" name="add_send_btn" value="追加発信をする" onclick="add_send(1)">
			<input type="button" name="no_add_send_btn" value="追加発信をしない" onclick="add_send(0)">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="add_send_cell" <?=value(serialize($add_send_cell))?>>
			<input type="hidden" name="add_send_count" <?=value($add_send_count)?>>
			<input type="hidden" name="auto_send_ary" <?=value(serialize($auto_send_ary))?>>
			<input type="hidden" name="color_cell" <?=value(serialize($color_cell))?>>
			<input type="hidden" name="add_price" <?=value($add_price)?>>
			<input type="hidden" name="send_flag">
<?
} else {
?>
			<a href="javascript:window.close();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image19','','images/chart/bt01_ov.gif',1)"><img src="images/chart/bt01.gif" alt="閉じる" name="Image19" width="108" height="31" border="0"></a>
<?
}
?>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
