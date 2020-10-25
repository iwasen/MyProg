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
include("$inc/pro_enquete.php");
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

// ファインディング有無
function decode_finding_flag($flag) {
	switch ($flag) {
	case DBTRUE:
		return 'あり';
	case DBFALSE:
		return 'なし';
	}
	return '';
}

// アンケート内容表示
function disp_enquete($enquete) {
	if (is_array($enquete->question)) {
		$page_break = '';

		foreach ($enquete->question as $qno => $question) {
			// 全角の質問番号
			$qno_zenkaku = 'Ｑ' . mb_convert_kana($qno, 'N');

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
		<td bgcolor="#eeeeee" width="20%" rowspan="4"><?=$qno_zenkaku?>
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
		<td bgcolor="#eeeeee" width="20%" rowspan="3"><?=$qno_zenkaku?>
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
		<td bgcolor="#eeeeee" width="20%" rowspan="4"><?=$qno_zenkaku?>
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
			<table border="0" cellspacing="2" cellpadding="2" width="100%" class="honbun2">
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
}

if (isset($pro_enq_no)) {
	$pro_enq  = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;
$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
$cleaning = &$pro_enq->cleaning;

// 編集可能フラグ取得
$edit_flag = ($enquete->status == 0 || $enquete->status == 8) && !$show;
//$edit_flag = true;///////////////////////////////////////////////////////////////

// 作成完了フラグ取得
if ($enquete->title != '')
	$make_flag |= 0x01;
if ($pro_enq->finding_flag == DBTRUE) {
	if ($enquete->get_question_num() != 0)
		$make_flag |= 0x02;
	if ($pro_enq->res_cond)
		$make_flag |= 0x04;
} else
	$make_flag |= 0x06;
if ($enquete2 && $enquete2->get_question_num() != 0)
	$make_flag |= 0x08;
if ($cleaning->exist_flag)
	$make_flag |= 0x10;
if ($pro_enq->search_id)
	$make_flag |= 0x20;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

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
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td align="left">

下記１〜４および６が全て「設定済み」または「設定不要」になっていれば「作成完了」ボタンが現れます。<br>
「作成完了」を押すと「申請」が可能になります。<br>
<br>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100% class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>１．アンケート概要</b></td>
								<td align="right">
<?
if ($edit_flag) {
?>
									 <?=$make_flag & 0x01 ? '<span class="footer_text">※　「既存のアンケートから複製」をした場合、タイトル・日時の確認をしてください。</span>' : ''?> <input type="button" name="" value="<?=$make_flag & 0x01 ? '　修正　' : '　設定　'?>" onclick="location.href='pe_edit1.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x01 ? '<b>設定済み</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" bgcolor="#eeeeee">アンケートタイトル</td>
								<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?>についてのアンケート</td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee">ファインディング有無</td>
								<td width="80%" bgcolor="#ffeecc"><?=decode_finding_flag($pro_enq->finding_flag)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">開始日時</td>
								<td bgcolor="#ffeecc"><? disp_date1($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">終了日時</td>
								<td bgcolor="#ffeecc"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
							</tr>
<!--
							<tr>
								<td bgcolor="#eeeeee">回答条件</td>
								<td bgcolor="#ffeecc">
<?
$ans_cond_ary = explode("\n", $pro_enq->ans_cond);
foreach ($ans_cond_ary as $ans_cond) {
	if ($ans_cond != '') {
?>
								・<?=htmlspecialchars($ans_cond)?><br>
<?
	}
}
?>								</td>
							</tr>
-->
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>２．ファインディングアンケート内容</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
	if ($pro_enq->finding_flag == DBTRUE) {
		if ($make_flag & 0x02) {
?>
									<input type="button" value="分岐・改ページの設定" onclick="location.href='pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
		}
?>
									<input type="button" value="<?=$make_flag & 0x02 ? '　修正　' : '　設定　'?>" onclick="location.href='pe_edit2.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x02 ? '<b>設定済み</b>' : ''?>
<?
	} elseif ($pro_enq->finding_flag == DBFALSE) {
?>
									<b>設定不要</b>
<?
	}
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE)
	disp_enquete($enquete);
?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>３．本アンケート対象条件</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x03) == 0x03) {
	if ($pro_enq->finding_flag == DBTRUE) {
?>
									<input type="button" value="<?=$make_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='pe_edit_res.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x04 ? '<b>設定済み</b>' : ''?>
<?
	} elseif ($pro_enq->finding_flag == DBFALSE) {
?>
									<b>設定不要</b>
<?
	}
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE && is_array($pro_enq->res_cond)) {
?>
							<tr>
								<td width="20%" bgcolor="#eeeeee">設問間結合条件</td>
								<td width="80%" bgcolor="#ffeecc" colspan=3><?=decode_and_or($pro_enq->res_and_or)?></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee" rowspan="<?=count($pro_enq->res_cond)?>">回答条件</td>
<?
	$first = true;
	foreach ($pro_enq->res_cond as $qno => $res_cond) {
		$question = &$enquete->question[$qno];

		$sno_ary = my_explode(',', $res_cond->select_no);
		$sel_text_ary = array();
		foreach ($sno_ary as $sno) {
			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
				$sel_text_ary[] = $question->sel_text[$sno];
			elseif ($question->question_type == 4 || $question->question_type == 5) {
				list($sno, $tno) = explode(':', $sno);
				$sel_text_ary[] = $question->hyousoku[$sno] . '：' . $question->hyoutou[$tno];
			}
		}
		$sel_text = join('，', $sel_text_ary);

		if ($first)
			$first = false;
		else {
?>
							<tr>
<?
		}
?>
								<td width="10%" bgcolor="#ffeecc" align="center">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td width="10%" bgcolor="#ffeecc" align="center"><?=decode_and_or($res_cond->and_or)?></td>
								<td width="60%" bgcolor="#ffeecc"><?=htmlspecialchars($sel_text)?></td>
							</tr>
<?
	}
?>
							<tr>
								<td width="20%" bgcolor="#eeeeee">想定出現率</td>
								<td width="80%" bgcolor="#ffeecc" colspan=3><?=number_format($pro_enq->appearance_ratio)?> %</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>４．本アンケート内容</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
	if ($make_flag & 0x08) {
?>
									<input type="button" value="分岐・改ページの設定" onclick="location.href='pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>&main=1'">
<?
	}
?>
									<input type="button" value="<?=$make_flag & 0x08 ? '　修正　' : '　設定　'?>" onclick="location.href='pe_edit2.php?pro_enq_no=<?=$pro_enq_no?>&main=1'"> <?=$make_flag & 0x08 ? '<b>設定済み</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<? disp_enquete($enquete2); ?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>５．対象条件・サンプル割付</b>
                                  <div class="footer_text"></div></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
									<input type="button" value="<?=$make_flag & 0x20 ? '　修正　' : '　設定　'?>" onclick="location.href='pe_edit3.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x20 ? '<b>設定済み</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE) {
	if ($pro_enq->appearance_ratio != 0 && $pro_enq->sample_num != 0)
		$finding_sample_num = number_format($pro_enq->sample_num / ($pro_enq->appearance_ratio / 100));
	else
		$finding_sample_num = '';
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%"><span style="font-size:12px;">ファインディングサンプル数</span></td>
								<td bgcolor="#ffeecc"><?=$finding_sample_num?></td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%">本アンケート<br>回収目標サンプル数</td>
								<td bgcolor="#ffeecc">
								<?=$pro_enq->sample_num?>								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">セル指定</td>
								<td bgcolor="#ffeecc">
									<? if ($make_flag & 0x20){
											if ($cell->cell_option == 1)
												echo('しない');
											else
												 echo('する');
										}
									?>								</td>
							</tr>
<?
if ($cell->cell_option == 1) {
?>
							<tr>
								<td bgcolor="#eeeeee">性別</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x20)
											echo(decode_sex($search->sex, '指定なし'))
									?>									</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">年代</td>
								<td bgcolor="#ffeecc"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
							<tr>
								<td bgcolor="#eeeeee">性別／年代</td>
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
			</table>								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">未既婚</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x20)
											echo(decode_mikikon($search->mikikon, '指定しない'));
									?>								</td>
							</tr>

<?
if ($cell->rate_option == 2) {
?>
							<tr>
								<td bgcolor="#eeeeee">発信割合設定</td>
								<td bgcolor="#ffeecc">未婚<?=$cell->send_rate[1]?>% ／ 既婚<?=$cell->send_rate[2]?>% ／ その他<?=$cell->send_rate[3]?>%</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">居住地域</td>
								<td bgcolor="#ffeecc"><? disp_area($search->jitaku_area) ?></td>
							</tr>
<?
if ($cell->rate_option == 3) {
?>
							<tr>
								<td bgcolor="#eeeeee">発信割合設定</td>
								<td bgcolor="#ffeecc">
<?
	if (is_array($cell->send_rate)) {
		foreach ($cell->send_rate as $area_cd => $rate)
			$rate_area_ary[] = decode_area3($area_cd) . $rate . '%';

		echo join(' ／ ', $rate_area_ary);
	}
?>								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">職業</td>
								<td bgcolor="#ffeecc"><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">業種</td>
								<td bgcolor="#ffeecc"><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">職種</td>
								<td bgcolor="#ffeecc"><? disp_shokushu($search->shokushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">子供の有無</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">住居形態</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">車の所有</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_car, $search->have_car)?></td>
							</tr>

<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
							<tr>
								<td bgcolor="#eeeeee"><?=htmlspecialchars($profile_name)?></td>
								<td bgcolor="#ffeecc"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">よく行くコンビニ</td>
								<td bgcolor="#ffeecc"><? disp_conveni($search->conveni) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">よく行くスーパー</td>
								<td bgcolor="#ffeecc"><? disp_super($search->super) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">興味ジャンル</td>
								<td bgcolor="#ffeecc"><? disp_genre($search->genre) ?></td>
							</tr>
*/ ?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td><b>６．マーケター・アシスタントへの同報通知</b></td>
								<td align="right">&nbsp;</td>
							</tr>
						</table>					</td>
				</tr>
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
				<tr>
					<td>&nbsp;</td>
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
									<form method="post" action="pe_update_notify.php">
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
									<input type="checkbox" name="notify_assistant[]" <?=value_checked_multi($fetch->mr_marketer_id, $pro_enq->notify_assistant)?><?=disabled(!$edit_flag)?>><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?> （<?=htmlspecialchars($fetch->mr_mail_addr)?>）<br>
<?
	}
?>
									<input type="checkbox" name="notify_others" value="1", <?=checked($pro_enq->notify_mail_addr)?><?=disabled(!$edit_flag)?>>追加（アシスタント以外に通知を共有されたい方はチェックしてください。）<br>
									　　<span class="footer_text">※複数の方を設定する場合は、1行1メールアドレスで入力してください。</span><br>
									<textarea name="notify_mail_addr" cols=40 rows=5<?=disabled(!$edit_flag)?>><?=str_replace(',', "\n", $pro_enq->notify_mail_addr)?></textarea>
<?
	if ($edit_flag) {
?>
									<input type="submit" value="設定">
<?
	}
?>
									<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
									</form>								</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
<?
if ($edit_flag) {
?>
<?
}
?>
			</table>
			<br>
<?
if ($pro_enq_no == '') {
?>
<? /*			<input type="button" value="既存のアンケートから複製" onclick="location.href='pe_copy.php'">
*/ ?>
<?
}
if ($make_flag & 0x01) {
?>
			<input type="button" value="ＷＥＢ動作確認" onclick="window.open('pe_remlenq3a.php?enquete_id=<?=$pro_enq->enquete_id?>&pro_enq_no=<?=$pro_enq_no?>', 'webtest')">
			<input type="button" value="ＷＥＢプレビュー" onclick="location.href='pe_webprev.php?pro_enq_no=<?=$pro_enq_no?>'">
			<?
}
if (!$show) {
	if ($enquete->status == 0 && ($make_flag & 0x2f) == 0x2f) {
?>
			<input type="button" value="　作成完了　" onclick="location.href='pe_status.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
	if ($enquete->status == 1) {
?>
			<input type="button" value="作成中に戻す" onclick="location.href='pe_status.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="button" value="　申請　" onclick="location.href='pe_app1.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
	if ($enquete->status == 8) {
?>
			<input type="button" value="　再申請　" onclick="location.href='pe_app1.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
}
?>
			<input type="button" value="　戻る　" onclick="location.href='pe_index.php'">

		</td>
	</tr>
<tr><td height="20"><img src="images/common/spacer.gif" width="1" height="20"></td></tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
