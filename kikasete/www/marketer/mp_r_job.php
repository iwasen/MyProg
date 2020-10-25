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
include("$inc/my_partner.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$search = &$myp->search;

set_return_page('mp_require_update.php', "pjt_id=$pjt_id");
set_return_page('mp_select_monitor.php', "pjt_id=$pjt_id");
set_return_page('mp_select_update.php', "pjt_id=$pjt_id");
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<script type="text/javascript">
<!--
function apply() {
	if (confirm("この内容で申請しますか？"))
		location.href= "mp_r_job_apply.php?pjt_id=<?=$pjt_id?>";
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

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>

			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_1.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_0.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			</div>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>

			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><strong>■<?=htmlspecialchars($myp->room_name)?></strong></td>
				</tr>
			</table>
			<br>

			<form>

			<table border=0 cellspacing=2 cellpadding=3 width="95%">
<?
if ($myp->real_send != 0) {
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
	$reply_count = db_fetch1($sql);

	if ($myp->select_sql == '') {
		$sel_count = '未設定';
		$sel_percent = '未設定';
	} else {
		$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE $myp->select_sql";
		$sel_count = db_fetch1($sql);
		$sel_percent = format_percent($sel_count, $myp->real_send, 1);
	}
?>
				<tr>
					<td bgcolor="#eff7e8">■発信／返信状況</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee" width="20%">発信数</td>
								<td bgcolor="#ffeecc"colspan="2" align="center"><?=number_format($myp->real_send)?></td>
								<td bgcolor="#ffeecc"><input type="button" value="　CSV　" onclick="location.href='mp_csv_send.php?enquete_id=<?=$myp->enquete_id?>'"></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">返信数</td>
								<td bgcolor="#ffeecc" width="20%" align="center"><?=number_format($reply_count)?></td>
								<td bgcolor="#ffeecc" width="20%" align="center"><?=format_percent($reply_count, $myp->real_send, 1)?></td>
								<td bgcolor="#ffeecc" width="60%">
<?
	if ($reply_count != 0) {
?>
									<input type="button" value="　CSV　" onclick="location.href='mp_csv_recv.php?enquete_id=<?=$myp->enquete_id?>'">
									<input name="button4" type="button" onClick="location.href='mp_total.php?pjt_id=<?=$pjt_id?>'" value="リアルタイム集計">
<?
	}
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">該当者数</td>
								<td bgcolor="#ffeecc" width="20%" align="center"><?=$sel_count?></td>
								<td bgcolor="#ffeecc" width="20%" align="center"><?=$sel_percent?></td>
								<td bgcolor="#ffeecc" width="60%">
<?
	if ($reply_count != 0 && $myp->select_sql != '') {
?>
									<input type="button" value="　CSV　" onclick="location.href='mp_csv_sel.php?pjt_id=<?=$pjt_id?>'">
<?
	}
?>
									<input name="button6" type="button" onClick="location.href='mp_require_edit.php?pjt_id=<?=$pjt_id?>'" value="該当者条件変更">
									<input name="button5" type="button" onClick="location.href='mp_select_monitor.php?pjt_id=<?=$pjt_id?>'" value="該当者選定">
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
}
?>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td bgcolor="#eff7e8" colspan="2">■リクルートアンケートの実施概要</td>
								<td align="right">
<?
if ($myp->status == MPS_REC_JUNBI) {
?>
									<input type="button" name="" value="<?=$myp->recruit_flag & 0x01 ? '　修正　' : '　設定　'?>" onclick="location.href='mp_r_job_edit1.php?pjt_id=<?=$pjt_id?>'">
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
								<td width="20%" bgcolor="#eeeeee">開始日時</td>
								<td width="80%" bgcolor="#ffeecc"><?=sprintf('%d年%d月%d日 %d時', $myp->recruit_start_y, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_h)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">終了日時</td>
								<td bgcolor="#ffeecc"><?=sprintf('%d年%d月%d日 %d時', $myp->recruit_end_y, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_h)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">リクルート条件</td>
								<td bgcolor="#ffeecc">
<?
foreach ($myp->recruit_jouken as $recruit_jouken) {
?>
								・<?=htmlspecialchars($recruit_jouken)?><br>
<?
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
								<td bgcolor="#eff7e8" colspan="2">■リクルートアンケートの内容</td>
								<td align="right">
<?
if ($myp->status == MPS_REC_JUNBI) {
?>
									<input type="button" value="<?=$myp->recruit_flag & 0x02 ? '　修正　' : '　設定　'?>" onclick="location.href='mp_r_job_edit2.php?pjt_id=<?=$pjt_id?>'" >
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
								<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?></td>
							</tr>
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
								<td bgcolor="#eff7e8" colspan="2">■リクルートアンケートの対象条件
<div class="footer_text">※　<span style="color:#FF0000;">セル指定、子供の有無、同居家族の指定は、<a href="myp_price.php" target="_blank">オプション料金</a>がかかります。</span></div>
</td>
								<td align="right">
<?
if ($myp->status == MPS_REC_JUNBI) {
?>
									<input type="button" value="<?=$myp->recruit_flag & 0x04 ? '　修正　' : '　設定　'?>" onclick="location.href='mp_r_job_edit3.php?pjt_id=<?=$pjt_id?>'">
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
								<td bgcolor="#eeeeee" colspan="2" width="20%">対象モニター</td>
								<td bgcolor="#ffeecc"><?=$search->decode_monitor_type($search->type, '<br>')?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">性別</td>
								<td bgcolor="#ffeecc"><?=decode_sex($search->sex, '<br>')?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">年代</td>
								<td bgcolor="#ffeecc"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">未既婚</td>
								<td bgcolor="#ffeecc"><?=decode_mikikon($search->mikikon, '<br>')?></td>
							</tr>
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
							<tr>
								<?if($search->zip!=''){?>
								<td bgcolor="#eeeeee" width='10%'>居住地域</td>
								<td bgcolor="#ffeecc"><?=$search->zip;?></td>
								<?}?>								
							</tr>
							<tr>
								<?if($search->zip_kinmuchi!=''){?>
								<td bgcolor="#eeeeee" width='10%'>勤務地</td>
								<td bgcolor="#ffeecc"><?=$search->zip_kinmuchi;?></td>
								<?}?>
								
							</tr>
							<!-- jeon_end seq=mp3-->
							<tr>
								<td bgcolor="#eeeeee" colspan="2">職業</td>
								<td bgcolor="#ffeecc"><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">業種</td>
								<td bgcolor="#ffeecc"><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">職種</td>
								<td bgcolor="#ffeecc"><? disp_shokushu($search->shokushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" colspan="2">子供の有無</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
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
						</table>
					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%">
							<tr>
								<td>■アンケートＷＥＢ画面とメール文のカスタマイズ</td>
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
if ($myp->status == MPS_REC_JUNBI && ($myp->recruit_flag & 0x01)) {
?>
									<input name="button" type="button" onClick="location.href='mp_edit_web.php?pjt_id=<?=$pjt_id?>'" value="ＷＥＢ画面の手動修正">
<?
}
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee" width="20%">メール文のカスタマイズ</td>

								<td bgcolor="#ffeecc">
<?
if ($myp->status == MPS_REC_JUNBI && ($myp->recruit_flag & 0x01)) {
?>
									<input name="button2" type="button" onClick="location.href='mp_edit_mail.php?pjt_id=<?=$pjt_id?>'" value="メール文面の手動修正">
<?
}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" onClick="location.href='mp_preveiw_web.php?pjt_id=<?=$pjt_id?>'" value="ＷＥＢプレビュー">
			<input type="button" value="メールプレビュー" onclick="location.href='mp_preveiw_mail.php?pjt_id=<?=$pjt_id?>'">
<?
if ($myp->status == MPS_REC_JUNBI && $myp->recruit_flag == 0x07) {
?>
			<input type="button" value="　作成完了　" onclick="location.href='mp_set_status.php?pjt_id=<?=$pjt_id?>'">
<?
}
if ($myp->status == MPS_REC_KANRYOU) {
?>
			<input type="button" value="作成中に戻す" onclick="location.href='mp_set_status.php?pjt_id=<?=$pjt_id?>'">
			<input type="button" value="　申請　" onclick="apply()">
<?
}
?>
			<input type="button" value="　戻る　" onclick="location.href='mp_pjt.php?pjt_id=<?=$pjt_id?>'">
			</form>
			<!-- myパートナー -->
<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
