<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビプロファイルページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('ナビプロファイル', BACK_TOP);

// リスト処理クラス生成
$list1 = new list_class('small');

// 年齢別リストヘッダ設定
$list1->set_header('年齢別','left', '');
$list1->set_header_group('ナビページ',3);
$list1->set_header('男性', 'right', '');
$list1->set_header('女性', 'right', '');
$list1->set_header('合計', 'right', '');
$list1->set_header_group('ワイワイ会議室',3);
$list1->set_header('男性', 'right', '');
$list1->set_header('女性', 'right', '');
$list1->set_header('合計', 'right', '');

$sql = "SELECT DATE_PART('Y', age(nav_birthday)) AS navi_age"
	. ", CASE WHEN nav_sex_cd=1 THEN 1 END AS navi_male"
	. ", CASE WHEN nav_sex_cd=2 THEN 1 END AS navi_female"
	. ", CASE WHEN nav_sex_cd=1 AND rom_room_id IS NOT NULL THEN 1 END AS ml_male"
	. ", CASE WHEN nav_sex_cd=2 AND rom_room_id IS NOT NULL THEN 1 END AS ml_female"
	. " FROM t_navi"
	. " JOIN t_navi_page ON nvp_navi_id=nav_navi_id"
	. " LEFT JOIN t_room ON rom_room_id=nvp_room_id AND rom_status=1"
	. " WHERE nvp_open_flag=1"
	. " ORDER BY navi_age";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$j = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$age = $fetch->navi_age;

	if ($age <= 9)
		$j = 0;
	elseif ($age >= 80)
		$j = 15;
	else
		$j = floor($age / 5) - 1;

	$ag_navi_male[$j] += $fetch->navi_male;
	$ag_navi_female[$j] += $fetch->navi_female;
	$ag_ml_male[$j] += $fetch->ml_male;
	$ag_ml_female[$j] += $fetch->ml_female;

	$ag_navi_male_total += $fetch->navi_male;
	$ag_navi_female_total += $fetch->navi_female;
	$ag_ml_male_total += $fetch->ml_male;
	$ag_ml_female_total += $fetch->ml_female;
}


$n1 = '';
$n2 = 9;
for ($i = 0; $i <= 15; $i++) {
	if ($n2 > 80)
		$n2 = '';

	$list1->set_data("$n1 - $n2");
	$list1->set_data(number_format($ag_navi_male[$i]));
	$list1->set_data(number_format($ag_navi_female[$i]));
	$list1->set_data(number_format($ag_navi_male[$i] + $ag_navi_female[$i]));
	$list1->set_data(number_format($ag_ml_male[$i]));
	$list1->set_data(number_format($ag_ml_female[$i]));
	$list1->set_data(number_format($ag_ml_male[$i] + $ag_ml_female[$i]));

	$n1 = $n2 + 1;
	$n2 = $n2 + 5;
}
$list1->set_data('計');
$list1->set_data(number_format($ag_navi_male_total));
$list1->set_data(number_format($ag_navi_female_total));
$list1->set_data(number_format($ag_navi_male_total + $ag_navi_female_total));
$list1->set_data(number_format($ag_ml_male_total));
$list1->set_data(number_format($ag_ml_female_total));
$list1->set_data(number_format($ag_ml_male_total + $ag_ml_female_total));


// リスト処理クラス生成
$list2 = new list_class('small');

// 職業別リストヘッダ設定
$list2->set_header('職業別','left', '');
$list2->set_header_group('ナビページ',3);
$list2->set_header('男性', 'right', '');
$list2->set_header('女性', 'right', '');
$list2->set_header('合計', 'right', '');
$list2->set_header_group('ワイワイ会議室',3);
$list2->set_header('男性', 'right', '');
$list2->set_header('女性', 'right', '');
$list2->set_header('合計', 'right', '');

$sb1 = "SELECT nav_shokugyo_id"
	. ", SUM(CASE WHEN nav_sex_cd = 1 THEN 1 END) AS navi_male"
	. ", SUM(CASE WHEN nav_sex_cd = 2 THEN 1 END) AS navi_female"
	. ", SUM(CASE WHEN nav_sex_cd = 1 AND rom_room_id IS NOT NULL THEN 1 END) AS ml_male"
	. ", SUM(CASE WHEN nav_sex_cd = 2 AND rom_room_id IS NOT NULL THEN 1 END) AS ml_female"
	. " FROM t_navi"
	. " JOIN t_navi_page ON nvp_navi_id=nav_navi_id"
	. " LEFT JOIN t_room ON rom_room_id=nvp_room_id AND rom_status=1"
	. " WHERE nvp_open_flag=1"
	. " GROUP BY nav_shokugyo_id";
$sql = "SELECT skg_shokugyo_name,sb1.*"
	. " FROM m_shokugyo"
	. " LEFT JOIN ($sb1) sb1 ON nav_shokugyo_id=skg_shokugyo_id"
	. " ORDER BY skg_shokugyo_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list2->set_data($fetch->skg_shokugyo_name);
	$list2->set_data(number_format($fetch->navi_male));
	$list2->set_data(number_format($fetch->navi_female));
	$list2->set_data(number_format($fetch->navi_male + $fetch->navi_female));
	$list2->set_data(number_format($fetch->ml_male));
	$list2->set_data(number_format($fetch->ml_female));
	$list2->set_data(number_format($fetch->ml_male + $fetch->ml_female));

	$sg_navi_male += $fetch->navi_male;
	$sg_navi_female += $fetch->navi_female;
	$sg_ml_male += $fetch->ml_male;
	$sg_ml_female += $fetch->ml_female;

}
$list2->set_data('計');
$list2->set_data($sg_navi_male);
$list2->set_data($sg_navi_female);
$list2->set_data(number_format($sg_navi_male + $sg_navi_female));
$list2->set_data($sg_ml_male);
$list2->set_data($sg_ml_female);
$list2->set_data(number_format($sg_ml_male + $sg_ml_female));


// 都道府県別リスト処理クラス生成
$list3 = new list_class('small');

// リストヘッダ設定
$list3->set_header('都道府県別','left', '');
$list3->set_header_group('ナビページ',3);
$list3->set_header('男性', 'right', '');
$list3->set_header('女性', 'right', '');
$list3->set_header('合計', 'right', '');
$list3->set_header_group('ワイワイ会議室',3);
$list3->set_header('男性', 'right', '');
$list3->set_header('女性', 'right', '');
$list3->set_header('合計', 'right', '');

$sb1 = "SELECT nav_address1"
	. ", SUM(CASE WHEN nav_sex_cd = 1 THEN 1 END) AS navi_male"
	. ", SUM(CASE WHEN nav_sex_cd = 2 THEN 1 END) AS navi_female"
	. ", SUM(CASE WHEN nav_sex_cd = 1 AND rom_room_id IS NOT NULL THEN 1 END) AS ml_male"
	. ", SUM(CASE WHEN nav_sex_cd = 2 AND rom_room_id IS NOT NULL THEN 1 END) AS ml_female"
	. " FROM t_navi"
	. " LEFT JOIN t_navi_page ON nvp_navi_id=nav_navi_id"
	. " LEFT JOIN t_room ON rom_room_id=nvp_room_id AND rom_status=1"
	. " WHERE nvp_open_flag=1"
	. " GROUP BY nav_address1";
$sql = "SELECT tdf_name,sb1.*"
	. " FROM m_todofuken"
	. " LEFT JOIN ($sb1) sb1 ON nav_address1=tdf_area_id"
	. " ORDER BY tdf_area_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list3->set_data($fetch->tdf_name);
	$list3->set_data(number_format($fetch->navi_male));
	$list3->set_data(number_format($fetch->navi_female));
	$list3->set_data(number_format($fetch->navi_male + $fetch->navi_female));
	$list3->set_data(number_format($fetch->ml_male));
	$list3->set_data(number_format($fetch->ml_female));
	$list3->set_data(number_format($fetch->ml_male + $fetch->ml_female));

	$ad_navi_male += $fetch->navi_male;
	$ad_navi_female += $fetch->navi_female;
	$ad_ml_male += $fetch->ml_male;
	$ad_ml_female += $fetch->ml_female;
}
$list3->set_data('計');
$list3->set_data($ad_navi_male);
$list3->set_data($ad_navi_female);
$list3->set_data(number_format($ad_navi_male + $ad_navi_female));
$list3->set_data($ad_ml_male);
$list3->set_data($ad_ml_female);
$list3->set_data(number_format($ad_ml_male + $ad_ml_female));

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("navi_profile.csv");
	$csv->export_data($list1);
	$csv->output_newline();
	$csv->export_data($list2);
	$csv->output_newline();
	$csv->export_data($list3);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>
<form name="form1" method="post">
<table width="100%" border=0>
	<tr>
		<td align="right"><input type="submit" name="export" value="エクスポート"></td>
	</tr>
</table>
<table width="100%" border=0>
	<tr>
		<td width="50%" valign="top">
<?php $list1->disp_list(); ?>
<br>
<?php $list2->disp_list(); ?>
		</td>
		<td width="50%" valign="top">
<?php $list3->disp_list(); ?>
	</tr>
</table>
</form>
<?php $header->page_footer(); ?>

</body>
</html>
