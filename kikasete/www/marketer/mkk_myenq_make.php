<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 日付表示（年月日）
function disp_date1($year, $month, $day) {
	if ($year)
		echo sprintf('%d年%d月%d日', $year, $month, $day);
}

// 日付表示（年月日時）
function disp_date2($year, $month, $day, $hour) {
	if ($year)
		echo sprintf('%d年%d月%d日 %d時', $year, $month, $day, $hour);
}

// 地域名
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT ar3_area3_name FROM m_area3 WHERE ar3_area3_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

if (isset($my_enq_no)) {
	$myenq  = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
} else
	redirect('mkk_myenq.php');

$enquete = &$myenq->enquete;
$search = &$myenq->search;
$cell = &$myenq->cell;

// 編集可能フラグ取得
$edit_flag = ($enquete->status == 0 || $enquete->status == 8) && !$show;

// 作成完了フラグ取得
if ($enquete->title != '')
	$make_flag |= 0x01;
if ($enquete->get_question_num() != 0)
	$make_flag |= 0x02;
if ($myenq->search_id)
	$make_flag |= 0x04;
?>
<? marketer_header('Myアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>

			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td align="left">

下記１〜３が全て「設定済み」になっていれば「作成完了」ボタンが現れます。<br>
「作成完了」を押すと「申請」が可能になります。<br>
<br>

					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>１．アンケート概要</b></td>
								<td align="right">
<?
if ($edit_flag) {
?>
									<input type="button" name="" value="<?=$make_flag & 0x01 ? '　修正　' : '　設定　'?>" onclick="location.href='mkk_myenq_edit1.php?my_enq_no=<?=$my_enq_no?>'"> <?=$make_flag & 0x01 ? '<b>設定済み</b>' : ''?>
<?
}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" bgcolor="#eeeeee">アンケートタイトル</td>
								<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?>　についてのアンケート</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">開始日時</td>
								<td bgcolor="#ffeecc"><? disp_date1($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">終了日時</td>
								<td bgcolor="#ffeecc"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">回答条件</td>
								<td bgcolor="#ffeecc">
<?
$ans_cond_ary = explode("\n", $myenq->ans_cond);
foreach ($ans_cond_ary as $ans_cond) {
	if ($ans_cond != '') {
?>
								・<?=htmlspecialchars($ans_cond)?><br>
<?
	}
}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>２．アンケート内容</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
	if ($make_flag & 0x02) {
?>
									<input type="button" value="分岐・改ページの設定" onclick="location.href='mkk_myenq_edit_branch.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
?>
									<input type="button" value="<?=$make_flag & 0x02 ? '　修正　' : '　設定　'?>" onclick="location.href='mkk_myenq_edit2.php?my_enq_no=<?=$my_enq_no?>'" > <?=$make_flag & 0x02 ? '<b>設定済み</b>' : ''?>
<?
}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		// 画像・リンクの指定
		$image_msg = '画像・リンクの指定：';
		if ($question->url)
			$image_msg .= "URLを指定する [$question->url]";
		elseif ($question->image_id) {
			$sql = "SELECT im_name FROM t_image WHERE im_image_id=$question->image_id";
			$image_msg .= '画像を埋め込む [' . htmlspecialchars(db_fetch1($sql)) . ']';
		} else
			$image_msg .= 'なし';

			if (isset($enquete->branch_cond[$qno])) {
?>
							<tr>
								<td bgcolor="#eeeeee" rowspan="2">分岐条件</td>
								<td bgcolor="#ffeecc">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($enquete->branch_cond_text($qno, 'を選択した方のみ', true))?></td>
							</tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2" align="center">改ページ</td>
							</tr>
<?
			}

		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
									<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$image_msg?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' （フリー回答）' : ''?><?=$question->ex_sno == $sno ? ' （排他）' : ''?><br>
<?
			}
?>
								</td>
							</tr>
<?
		} elseif ($question->question_type == 3) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?>
									<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$image_msg?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
									<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$image_msg?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="2" cellpadding="2" width="100%">
										<tr>
											<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
											<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
										</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
										<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
											<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($question->hyoutou as $hno => $hyoutou) {
?>
											<td></td>
<?
				}
?>
										</tr>
<?
			}
?>
									</table>
								</td>
							</tr>
<?
		} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="4">Ｑ<?=mb_convert_kana($qno, 'N')?>
									<div><?=$question->must_flag == 't' ? '（必須回答）' : '（任意回答）'?></div></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$image_msg?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>回答欄の前に表示する文字列：<?=htmlspecialchars($question->pre_text)?></div>
									<div>回答欄の後に表示する文字列：<?=htmlspecialchars($question->post_text)?></div>
								</td>
							</tr>
<?
		}
	}
}
?>
						</table>
					</td>
				</tr>

				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>３．対象条件</b>　</td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
									<input type="button" value="<?=$make_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='mkk_myenq_edit3.php?my_enq_no=<?=$my_enq_no?>'"> <?=$make_flag & 0x04 ? '<b>設定済み</b>' : ''?>
<?
}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" colspan="2" width="40%">最大発信数</td>
								<td bgcolor="#ffeecc"><?=$myenq->send_num?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">発信数割付(セル指定)</td>
								<td bgcolor="#ffeecc">
									<? if ($make_flag & 0x04){
											if ($cell->cell_option == 1)
												echo('しない');
											else
												 echo('する');
										}
									?>
								</td>
							</tr>
<?
if ($cell->cell_option == 1) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">性別</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x04)
											echo(decode_sex($search->sex, '指定なし'))
									?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">年代</td>
								<td bgcolor="#ffeecc"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">性別／年代</td>
								<td bgcolor="#ffeecc">
										<?=($search->age_from || $search->age_to) ? "任意の年齢を指定する　{$search->age_from}歳 〜 {$search->age_to}歳" : "任意の年齢を指定しない"?>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
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
			$num = $send_cell[$sex][$age][0];
			$sum_col += $num;
			$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
		}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
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
					<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">未既婚</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x04)
											echo(decode_mikikon($search->mikikon, '指定しない'));
									?>
								</td>
							</tr>
<?
if ($cell->rate_option == 2) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">発信割合設定</td>
								<td bgcolor="#ffeecc">未婚<?=$cell->send_rate[1]?>% ／ 既婚<?=$cell->send_rate[2]?>% ／ その他<?=$cell->send_rate[3]?>%</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">居住地域</td>
								<td bgcolor="#ffeecc"><? disp_area($search->jitaku_area) ?></td>
							</tr>
							<!-- jeon_start seq=mp3-->
							<!-- commentout by namiki 20070807 <tr>
								<td bgcolor="#eeeeee" rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>郵便番号</td>
								<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
								<td bgcolor="#eeeeee" width='10%'>条件</td>
								<td bgcolor="#ffeecc"><? if($search->zip_kinmuchi_andor==1)echo "居住地域 AND 勤務地";
													else echo"居住地域  OR 勤務地";?></td>
								<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3' bgcolor="#ffeecc">&nbsp;</td><?}?>
							</tr> -->
								<?if($search->zip!=''){?>
							<tr>
								<td bgcolor="#eeeeee" width='10%'>居住地域</td>
								<td bgcolor="#ffeecc"><?=$search->zip;?></td>
							</tr>
								<?}?>
								<?if($search->zip_kinmuchi!=''){?>
							<tr>
								<td bgcolor="#eeeeee" width='10%'>勤務地</td>
								<td bgcolor="#ffeecc"><?=$search->zip_kinmuchi;?></td>
							</tr>
								<?}?>
							<!-- jeon_end seq=mp3-->
<?
if ($cell->rate_option == 3) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">発信割合設定</td>
								<td bgcolor="#ffeecc">
<?
	if (is_array($cell->send_rate)) {
		foreach ($cell->send_rate as $area_cd => $rate)
			$rate_area_ary[] = decode_area3($area_cd) . $rate . '%';

		echo join(' ／ ', $rate_area_ary);
	}
?>
								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">職業</td>
								<td bgcolor="#ffeecc"><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">業種</td>
								<td bgcolor="#ffeecc"><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
 							<? /*<tr>
								<td bgcolor="#eeeeee" colspan="2">職種</td>
								<td bgcolor="#ffeecc"><? disp_shokushu($search->shokushu) ?></td>
							</tr> */ ?>

							<tr>
								<td bgcolor="#eeeeee" colspan="2">子供の有無</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
<? /*
							<tr>
						<td bgcolor="#eeeeee" colspan="2">住居形態</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">車の所有</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_car, $search->have_car)?></td>
							</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2"><?=htmlspecialchars($profile_name)?></td>
								<td bgcolor="#ffeecc"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">よく行くコンビニ</td>
								<td bgcolor="#ffeecc"><? disp_conveni($search->conveni) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">よく行くスーパー</td>
								<td bgcolor="#ffeecc"><? disp_super($search->super) ?></td>
							</tr>

							<tr>
								<td bgcolor="#eeeeee" colspan="2">興味ジャンル</td>
								<td bgcolor="#ffeecc"><? disp_genre($search->genre) ?></td>
							</tr>
*/ ?>
<?
$sql = "SELECT pnc_category_id,pnc_category_name FROM t_panel_category WHERE pnc_display_order IS NOT NULL AND pnc_display_status=1 ORDER BY pnc_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$special_panel_name = array();
	if ($search->special_panel != '') {
		$sql = "SELECT pnl_panel_name FROM t_panel WHERE pnl_category_id={$fetch->pnc_category_id} AND pnl_display_order IS NOT NULL AND pnl_display_status=1 AND pnl_panel_id IN ($search->special_panel) ORDER BY pnl_display_order";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			$special_panel_name[] = $fetch2->pnl_panel_name;
		}
	}
?>
							<tr>
								<td bgcolor="#eeeeee" colspan="2"><?=htmlspecialchars($fetch->pnc_category_name)?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars(join('　', $special_panel_name))?></td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td><b>４．マーケター・アシスタントへの同報通知</b></td>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
				<tr>
					<td>
<span class="footer_text">※このアンケートに関する情報は、アシスタントの方が作成されたアンケートでもマーケターの方へ各種通知が届きます。</span>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($make_flag & 0x01) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%">各種通知の共有</td>
								<td bgcolor="#ffeecc" width="80%">
									<form method="post" action="mkk_myenq_update_notify.php">
<?
	$sql = "SELECT mr_marketer_id,mr_mail_addr,mr_name1,mr_name2"
		. " FROM t_marketer"
		. " WHERE mr_parent_id={$_SESSION['ss_owner_id']}"
		. " ORDER BY mr_marketer_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
									<input type="checkbox" name="notify_assistant[]" <?=value_checked_multi($fetch->mr_marketer_id, $myenq->notify_assistant)?><?=disabled(!$edit_flag)?>><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?> （<?=htmlspecialchars($fetch->mr_mail_addr)?>）<br>
<?
	}
?>
									<input type="checkbox" name="notify_others" value="1", <?=checked($myenq->notify_mail_addr)?><?=disabled(!$edit_flag)?>>追加（アシスタント以外に通知を共有されたい方はチェックしてください。）<br>
									　　<span class="footer_text">※複数の方を設定する場合は、1行1メールアドレスで入力してください。</span><br>
									<textarea name="notify_mail_addr" cols=40 rows=5<?=disabled(!$edit_flag)?>><?=str_replace(',', "\n", $myenq->notify_mail_addr)?></textarea>
<?
	if ($edit_flag) {
?>
									<input type="submit" value="設定">
<?
	}
?>
									<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
									</form>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
<?
if ($edit_flag) {
?>
				<tr bgcolor="#eff7e8">
					<td>
<!--
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td><b>５．アンケートＷＥＢ画面とメール文のカスタマイズ</b></td>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="20%">アンケートＷＥＢ画面のカスタマイズ</td>

								<td bgcolor="#ffeecc" width="80%">
<?
	if ($make_flag & 0x01) {
?>
									<input name="button" type="button" onClick="location.href='mkk_myenq_edit_web.php?my_enq_no=<?=$my_enq_no?>'" value="ＷＥＢ画面の手動修正">
<?
	}
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" width="20%">メール文のカスタマイズ</td>

								<td bgcolor="#ffeecc">
<?
	if ($make_flag & 0x01) {
?>
									<input name="button2" type="button" onClick="location.href='mkk_myenq_edit_mail.php?my_enq_no=<?=$my_enq_no?>'" value="メール文面の手動修正">　<span class="footer_text"><br>※　「既存のアンケートから複製」をした場合、メールに表示される「件名」の確認を行ってください。</span>
<?
	}
?>
								</td>
							</tr>
						</table>
-->
					</td>
				</tr>
<?
}
?>
			</table>
			<br>
<?
if ($my_enq_no == '') {
?>
<? /*			<input type="button" value="既存のアンケートから複製" onclick="location.href='mkk_myenq_copy.php'">
*/ ?>
<?
}
if ($make_flag & 0x01) {
?>
			<input type="button" value="ＷＥＢ動作確認" onclick="window.open('mkk_myenq_remlenq3a.php?enquete_id=<?=$myenq->enquete_id?>&my_enq_no=<?=$my_enq_no?>', 'webtest')">
			<input type="button" value="ＷＥＢプレビュー" onClick="location.href='mkk_myenq_webprev.php?my_enq_no=<?=$my_enq_no?>'">
<!--			
<input type="button" value="メールプレビュー" onclick="location.href='mkk_myenq_mailprev.php?my_enq_no=<?=$my_enq_no?>'">
-->
<?
}
if (!$show) {
	if ($enquete->status == 0 && $make_flag == 0x07) {
?>
			<input type="button" value="　作成完了　" onclick="location.href='mkk_myenq_status.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
	if ($enquete->status == 1) {
?>
			<input type="button" value="作成中に戻す" onclick="location.href='mkk_myenq_status.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="button" value="　申請　" onclick="location.href='mkk_myenq_app1.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
	if ($enquete->status == 8) {
?>
			<input type="button" value="　再申請　" onclick="location.href='mkk_myenq_app1.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
}
?>
			<input type="button" value="　戻る　" onclick="location.href='mkk_myenq.php'">

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
