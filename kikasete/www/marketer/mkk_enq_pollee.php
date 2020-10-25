<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

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
?>
<? marketer_header('対象者条件', PG_NULL) ?>
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
		<td width="400" valign="top">≪対象者条件≫<br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="400">
						アンケートを発信した対象者条件です。
					</td>
					<td widt="300" align="right">
						<a href="javascript:history.back()">アンケート詳細表示に戻る</a>
					</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2" width="20%">最大発信数</td>
					<td align="center" class="footer_text"><?=$myenq->send_num?></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">発信数割付(セル指定)</td>
					<td align="center" class="footer_text">
						<?
							if ($cell->cell_option == 1)
								echo('しない');
							else
								 echo('する');
						?>
					</td>
				</tr>
<?
if ($cell->cell_option == 1) {
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">性別</td>
					<td align="center" class="footer_text">
						<?
							echo(decode_sex($search->sex, '指定なし'))
						?>
					</td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">年代</td>
					<td align="center" class="footer_text"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
				</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">性別／年代</td>
					<td align="center" class="footer_text">
										<?=($search->age_from || $search->age_to) ? "任意の年齢を指定する　{$search->age_from}歳 〜 {$search->age_to}歳" : "任意の年齢を指定しない"?>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
	if ($cell->age_option == 2) {
?>
				<tr align="center" bgcolor="#eeeeee" class="footer_text" align="center">
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
				<tr align="center" bgcolor="#eeeeee" class="footer_text" align="center">
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
				<tr align="center" bgcolor="#eeeeee" class="footer_text" align="center">
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
				<tr align="center" bgcolor="#eeeeee" class="footer_text" align="center">
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
					<td align="center" bgcolor="#eeeeee" class="footer_text" nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
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
					<td align="center" bgcolor="#eeeeee" class="footer_text" nowrap align="center">合計</td>
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
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">未既婚</td>
					<td align="center" class="footer_text">
						<?
							echo(decode_mikikon($search->mikikon, '指定しない'));
						?>
					</td>
				</tr>
<?
if ($cell->rate_option == 2) {
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">発信割合設定</td>
					<td align="center" class="footer_text">未婚<?=$cell->send_rate[1]?>% ／ 既婚<?=$cell->send_rate[2]?>% ／ その他<?=$cell->send_rate[3]?>%</td>
				</tr>
<?
}
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">居住地域</td>
					<td align="center" class="footer_text"><? disp_area($search->jitaku_area) ?></td>
				</tr>
				<!-- jeon_start seq=mp3-->
				<!-- commentout by namiki 20070807 <tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" rowspan='3' <?if($search->zip=='' && $search->zip_kinmuchi=='')echo "colspan='2'"?>>郵便番号</td>
					<?if($search->zip!='' && $search->zip_kinmuchi!=''){?>
					<td align="center" bgcolor="#eeeeee" class="footer_text" width='10%'>条件</td>
					<td align="center" class="footer_text"><? if($search->zip_kinmuchi_andor==1)echo "居住地域 AND 勤務地";
										else echo"居住地域  OR 勤務地";?></td>
					<?}else if($search->zip=='' && $search->zip_kinmuchi==''){?><td rowspan='3' align="center" class="footer_text">&nbsp;</td><?}?>
				</tr> -->
					<?if($search->zip!=''){?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" width='10%'>居住地域</td>
					<td align="center" class="footer_text"><?=$search->zip;?></td>
				</tr>
					<?}?>
					<?if($search->zip_kinmuchi!=''){?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" width='10%'>勤務地</td>
					<td align="center" class="footer_text"><?=$search->zip_kinmuchi;?></td>
				</tr>
					<?}?>
				<!-- jeon_end seq=mp3-->
<?
if ($cell->rate_option == 3) {
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">発信割合設定</td>
					<td align="center" class="footer_text">
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
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">職業</td>
					<td align="center" class="footer_text"><? disp_shokugyou($search->shokugyou) ?></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">業種</td>
					<td align="center" class="footer_text"><? disp_gyoushu($search->gyoushu) ?></td>
				</tr>
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
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2"><?=htmlspecialchars($fetch->pnc_category_name)?></td>
					<td align="center" class="footer_text"><?=htmlspecialchars(join('　', $special_panel_name))?></td>
				</tr>
<?
}
?>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
