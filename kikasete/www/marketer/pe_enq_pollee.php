<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/search.php");
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

if ($search_id == '')
	redirect('pe_index.php');

$search = new search_class;
$search->read_db($search_id);
$cell = new cell_class;
$cell->read_db($search_id);
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
			<table width="768" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="400">
						アンケートを発信した対象者条件です。
					</td>
					<td width="200">
						<a href="javascript:history.back()">アンケート詳細表示に戻る</a>
					</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">セル指定</td>
					<td align="center" class="footer_text"><?=$cell->cell_option == 1 ? 'しない' : 'する'?></td>
				</tr>
<?
if ($cell->cell_option == 1) {
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">性別</td>
					<td align="center" class="footer_text"><?=decode_sex($search->sex, '未設定')?></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">年代</td>
					<td align="center" class="footer_text"><? disp_age2($search->age_cd, $search->age_from, $search->age_to, '未設定') ?></td>
				</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
				<tr>
					<td align="center" bgcolor="#eeeeee" class="footer_text" colspan="2">性別／年代</td>
					<td class="footer_text"><?=($search->age_from || $search->age_to) ? "任意の年齢を指定する　{$search->age_from}歳 〜 {$search->age_to}歳" : "任意の年齢を指定しない"?>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
	if ($cell->age_option == 2) {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%" class="footer_text">10代</td>
					<td nowrap width="10%" colspan=2 class="footer_text">20代</td>
					<td nowrap width="10%" colspan=2 class="footer_text">30代</td>
					<td nowrap width="10%" colspan=2 class="footer_text">40代</td>
					<td nowrap width="10%" colspan=2 class="footer_text">50代</td>
					<td nowrap width="10%" colspan=2 class="footer_text">60代</td>
					<td nowrap width="5%" rowspan=2 class="footer_text">70代<br>以上</td>
					<td nowrap width="5%" rowspan=2 class="footer_text">合計</td>
				</tr>
				<tr bgcolor="#eeeeee" align="center">
					<td class="footer_text">後</td>
					<td class="footer_text">前</td>
					<td class="footer_text">後</td>
					<td class="footer_text">前</td>
					<td class="footer_text">後</td>
					<td class="footer_text">前</td>
					<td class="footer_text">後</td>
					<td class="footer_text">前</td>
					<td class="footer_text">後</td>
					<td class="footer_text">前</td>
					<td class="footer_text">後</td>
				</tr>
<?
	} elseif ($cell->age_option == 3) {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%" class="footer_text">&nbsp;</td>
					<td nowrap width="10%" class="footer_text">10代</td>
					<td nowrap width="10%" class="footer_text">20代</td>
					<td nowrap width="10% class="footer_text"">30代</td>
					<td nowrap width="10% class="footer_text"">40代</td>
					<td nowrap width="10% class="footer_text"">50代</td>
					<td nowrap width="10% class="footer_text"">60代</td>
					<td nowrap width="10% class="footer_text"">70代以上</td>
					<td nowrap width="10%" class="footer_text">合計</td>
				</tr>
<?
	} else {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%" class="footer_text">&nbsp;</td>
					<td nowrap width="10% class="footer_text"">年代指定なし</td>
					<td nowrap width="10% class="footer_text"">合計</td>
				</tr>
<?
	}

	$sum_row = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center" class="footer_text"><?=decode_sex($sex, '性別指定なし')?></td>
<?
		$sum_col = 0;
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = $send_cell[$sex][$age][0];
			$sum_col += $num;
			$sum_row[$age] += $num;
?>
					<td nowrap align="right" class="footer_text"><?=number_format($num)?></td>
<?
		}
?>
					<td nowrap align="right" class="footer_text" ><?=number_format($sum_col)?></td>
				</tr>
<?
	}
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center" class="footer_text">合計</td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sum_row[$age];
		$sum_col += $num;
?>
					<td nowrap align="right" class="footer_text"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right" class="footer_text"><?=number_format($sum_col)?></td>
				</tr>
			</table>
								</td>
							</tr>
<?
}
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">未既婚</td>
					<td width="70%" align="center" class="footer_text">
						<?=decode_mikikon($search->mikikon, '未設定')?>
					</td>
				</tr>
<?
if ($cell->rate_option == 2) {
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">発信割合設定</td>
					<td width="70%" align="center" class="footer_text">未婚<?=$cell->send_rate[1]?>% ／ 既婚<?=$cell->send_rate[2]?>%</td>
				</tr>
<?
}
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">居住地域</td>
					<td width="70%" align="center" class="footer_text">
						<? disp_area($search->jitaku_area, '未設定') ?>
					</td>
				</tr>
<?
if ($cell->rate_option == 3) {
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">発信割合設定</td>
					<td width="70%" align="center" class="footer_text">
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
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">職業</td>
					<td width="70%" align="center" class="footer_text">
						<? disp_shokugyou($search->shokugyou, '未設定') ?>
					</td>
				</tr>

				<? /*<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">業種</td>
					<td width="70%" align="center" class="footer_text">*/ ?>
						<? /*disp_gyoushu($search->gyoushu, '未設定')*/ ?>
					<? /*</td>
				</tr>*/ ?>
				<? /*<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">職種</td>
					<td width="70%" align="center" class="footer_text">*/ ?>
						<? /*disp_shokushu($search->shokushu, '未設定')*/ ?>
					<? /*</td>
				</tr>*/ ?>
<!--				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap>勤務先住所</td>
					<td width="70%" align="center">
						<font size="2" class="honbun2">東京都</font>
					</td>
				</tr>
-->
				<? /*
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">子供の有無</td>
					<td width="70%" align="center" class="footer_text"><?=multi_decode(decode_have_child, $search->have_child, '未設定')?></td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">住居形態</td>
					<td width="70%" align="center" class="footer_text"><?=multi_decode(decode_housing_form, $search->housing_form, '未設定')?></td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">車の所有</td>
					<td width="70%" align="center" class="footer_text"><?=multi_decode(decode_have_car, $search->have_car, '未設定')?></td>
				</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text"><?=htmlspecialchars($profile_name)?></td>
					<td width="70%" align="center" class="footer_text">
						<? disp_ma_profile($profile_id, $search->ma_profile, '未設定') ?>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">よく行くコンビニ</td>
					<td width="70%" align="center" class="footer_text">
						<? disp_conveni($search->conveni, '未設定') ?>
					</td>
				</tr>
				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">よく行くスーパー</td>
					<td width="70%" align="center" class="footer_text">
						<? disp_super($search->super, '未設定') ?>
					</td>
				</tr>

				<tr>
					<td width="30%" align="center" bgcolor="#eeeeee" colspan="2" nowrap class="footer_text">興味ジャンル</td>
					<td width="70%" align="center" class="footer_text">
						<? disp_genre($search->genre, '未設定') ?>
					</td>
				</tr>
				*/ 
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
