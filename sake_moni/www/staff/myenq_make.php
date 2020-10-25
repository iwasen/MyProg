<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// 日付表示
function disp_date($year, $month, $day, $hour) {
	if ($year)
		echo sprintf('%d年%d月%d日 %d時', $year, $month, $day, $hour);
}

if (isset($my_enq_no)) {
	$myenq  = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;
$search = &$myenq->search;

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
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form>

			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td align="left">
<font class="honbun2">
下記１〜３が全て「設定済み」になっていれば「作成完了」ボタンが現れます。<br>
「作成完了」を押すと「申請」が可能になります。<br>
<br>
※「４．メールとＷｅｂのカスタマイズ」は必要な場合のみ、ご利用ください。<br><br>
</font>
					</td>
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
									<input type="button" name="" value="<?=$make_flag & 0x01 ? '　修正　' : '　設定　'?>" onclick="location.href='myenq_edit1.php?my_enq_no=<?=$my_enq_no?>'"> <?=$make_flag & 0x01 ? '<b>設定済み</b>' : ''?>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" bgcolor="#eeeeee">アンケートタイトル</td>
								<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">開始日時</td>
								<td bgcolor="#ffeecc"><? disp_date($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d, $enquete->start_date_h)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">終了日時</td>
								<td bgcolor="#ffeecc"><? disp_date($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
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
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>２．アンケート内容</b></td>
								<td align="right">
<?
if ($edit_flag) {
?>
									<input type="button" value="<?=$make_flag & 0x02 ? '　修正　' : '　設定　'?>" onclick="location.href='myenq_edit2.php?my_enq_no=<?=$my_enq_no?>'" > <?=$make_flag & 0x02 ? '<b>設定済み</b>' : ''?>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$question->question_type == 1 ? '単一選択(SA)' : '複数選択(MA)'?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
<?
			}
?>
								</td>
							</tr>
<?
		} elseif ($question->question_type == 3) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="2">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">自由回答(FA)</td>
							</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$question->question_type == 4 ? 'マトリックス(SA)' : 'マトリックス(MA)'?></td>
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
		}
	}
}
?>
						</table>
					</td>
				</tr>

				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>３．対象条件</b></td>
								<td align="right">
<?
if ($edit_flag) {
?>
									<input type="button" value="<?=$make_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='myenq_edit3.php?my_enq_no=<?=$my_enq_no?>'"> <?=$make_flag & 0x04 ? '<b>設定済み</b>' : ''?>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%">最大発信数</td>
								<td bgcolor="#ffeecc"><?=$myenq->send_num?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">性別</td>
								<td bgcolor="#ffeecc"><?=decode_sex($search->sex, '<br>')?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">年代</td>
								<td bgcolor="#ffeecc"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">未既婚</td>
								<td bgcolor="#ffeecc"><?=decode_mikikon($search->mikikon, '<br>')?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">子供の有無</td>
								<td bgcolor="#ffeecc"><?=decode_child($search->child, '<br>')?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">居住地域</td>
								<td bgcolor="#ffeecc"><? disp_area($search->jitaku_area) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">職業</td>
								<td bgcolor="#ffeecc"><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">チェーン</td>
								<td bgcolor="#ffeecc"><? disp_chain($search->chain) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">関連調査への参加</td>
								<td bgcolor="#ffeecc"><?=decode_research($search->research, '<br>') ?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
if ($edit_flag) {
?>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td><b>４．アンケートＷＥＢ画面とメール文のカスタマイズ</b></td>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%">アンケートＷＥＢ画面のカスタマイズ</td>

								<td bgcolor="#ffeecc" width="80%">
<?
	if ($make_flag & 0x01) {
?>
									<input name="button" type="button" onClick="location.href='myenq_edit_web.php?my_enq_no=<?=$my_enq_no?>'" value="ＷＥＢ画面の手動修正">
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
									<input name="button2" type="button" onClick="location.href='myenq_edit_mail.php?my_enq_no=<?=$my_enq_no?>'" value="メール文面の手動修正">
<?
	}
?>
								</td>
							</tr>
						</table>
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
			<input type="button" value="既存のアンケートから複製" onclick="location.href='myenq_copy.php'">
<?
}
if ($make_flag & 0x01) {
?>
			<input type="button" value="ＷＥＢプレビュー" onClick="location.href='myenq_webprev.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="button" value="メールプレビュー" onclick="location.href='myenq_mailprev.php?my_enq_no=<?=$my_enq_no?>'">
<?
}
if (!$show) {
	if ($enquete->status == 0 && $make_flag == 0x07) {
?>
			<input type="button" value="　作成完了　" onclick="location.href='myenq_status.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
	if ($enquete->status == 1) {
?>
			<input type="button" value="作成中に戻す" onclick="location.href='myenq_status.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="button" value="　申請　" onclick="location.href='myenq_app1.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
	if ($enquete->status == 8) {
?>
			<input type="button" value="　再申請　" onclick="location.href='myenq_app1.php?my_enq_no=<?=$my_enq_no?>'">
<?
	}
}
?>
			<input type="button" value="　戻る　" onclick="location.href='myenq.php'">
			</form>


<? staff_footer () ?>
