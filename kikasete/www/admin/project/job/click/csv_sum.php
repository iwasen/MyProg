<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:クリックカウント集計CSV出力
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");

// マスタから名称取得
function get_name($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$name .= "「{$fetch[0]}」";
	}
	return $name;
}

// 集計項目出力
function output_sum(&$name_ary, &$sum_ary, $sum_item) {
	foreach ($name_ary as $name => &$ary) {
		$name1 = "{$sum_item}-{$name}";

		foreach ($ary as $name2 => $code) {
			set_csv($csv, $name1);
			set_csv($csv, $name2);
			set_csv($csv, $sum_ary[$sum_item][$name][$code] + 0);
			output_csv($csv);

			$name1 = '';
		}
	}
}

// ジョブIDチェック
if ($job_id == '')
	redirect('index.php');

// CSVファイル名設定
prepare_csv("job_clicksum_$job_id.csv");

// ジョブ情報取得
$sql = "SELECT jb_start_date,jb_real_send,jb_subject,jb_enquete_id,sr_sex,sr_mikikon,sr_age_from,sr_age_to,sr_kyojuu,sr_shokugyou,sr_genre"
	. " FROM t_job"
	. " JOIN t_search ON sr_search_id=jb_search_id"
	. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$start_date = $fetch->jb_start_date;
	$real_send = $fetch->jb_real_send;
	$subject = $fetch->jb_subject;
	$sex = $fetch->sr_sex;
	$mikikon = $fetch->sr_mikikon;
	$age_from = $fetch->sr_age_from;
	$age_to = $fetch->sr_age_to;
	$kyojuu = $fetch->sr_kyojuu;
	$shokugyou = $fetch->sr_shokugyou;
	$genre = $fetch->sr_genre;
	$enauete_id = $fetch->jb_enquete_id;
}

// カウント期間取得
$sql = "SELECT cc_start_date,cc_end_date FROM t_click_counter WHERE cc_job_id=$job_id ORDER BY cc_ct_id LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$start_date = $fetch->cc_start_date;
	$end_date = $fetch->cc_end_date;
}

// 配信日出力
set_csv($csv, '配信日');
set_csv($csv, format_date($start_date));
output_csv($csv);

// カウント期間出力
set_csv($csv, 'カウント期間');
set_csv($csv, format_date($start_date) . '〜' . format_date($end_date));
output_csv($csv);

// 配信数出力
set_csv($csv, '配信数');
set_csv($csv, $real_send);
output_csv($csv);

// 件名出力
set_csv($csv, '件名');
set_csv($csv, $subject);
output_csv($csv);

// 性別出力
set_csv($csv, '性別');
set_csv($csv, decode_sex($sex));
output_csv($csv);

// 未既婚出力
set_csv($csv, '未既婚');
set_csv($csv, decode_mikikon($mikikon));
output_csv($csv);

// 年齢出力
set_csv($csv, '年齢');
set_csv($csv, ($age_from || $age_to) ? "{$age_from}〜{$age_to}" : '設定なし');
output_csv($csv);

// 住所出力
set_csv($csv, '住所');
set_csv($csv, $kyojuu != '' ? str_replace('/', '', $kyojuu) : '設定なし');
output_csv($csv);

// 職業出力
set_csv($csv, '職業');
if ($shokugyou != '') {
	$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($shokugyou) ORDER BY sg_order";
	set_csv($csv, get_name($sql));
} else
	set_csv($csv, '設定なし');
output_csv($csv);

// 興味ジャンル出力
set_csv($csv, '興味ジャンル');
if ($genre != '') {
	$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($genre) ORDER BY kg_order";
	set_csv($csv, get_name($sql));
} else
	set_csv($csv, '設定なし');
output_csv($csv);

// 計項目名設定
$name_ary['計'][''] = '';

// 性別項目名設定
for ($i = 1; $i <= 2; $i++)
	$name_ary['性別'][decode_sex($i)] = $i;

// 年代項目名設定
for ($i = 10; $i <= 60; $i += 10)
	$name_ary['年代']["{$i}代" . ($i == 60 ? '〜' : '')] = $i;

// 未既婚項目名設定
for ($i = 1; $i <= 3; $i++)
	$name_ary['未既婚'][decode_mikikon($i)] = $i;

// 住所項目名設定
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($kyojuu == '' || strpos($kyojuu, $fetch->ar_area_name) !== false)
		$name_ary['住所'][$fetch->ar_area_name] = $fetch->ar_area_cd;
}

// 職業項目名設定
if ($shokugyou != '')
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($shokugyou) ORDER BY sg_shokugyou_cd";
else
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_shokugyou_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$name_ary['職業'][$fetch->sg_shokugyou_name] = $fetch->sg_shokugyou_cd;
}

// 集計処理
$sb1 = "SELECT lc_monitor_id,COUNT(*) AS click_count"
	. " FROM t_click_counter"
	. " JOIN l_click_counter ON cc_ct_id=lc_ct_id"
	. " WHERE cc_job_id=$job_id"
	. " GROUP BY lc_monitor_id";
$sql = "SELECT mn_sex,DATE_PART('Y',AGE(en_start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_jitaku_area,mn_shokugyou_cd,click_count"
	. " FROM t_job"
	. " JOIN t_enquete ON en_enquete_id=jb_enquete_id"
	. " JOIN t_enquete_list ON el_enquete_id=jb_enquete_id"
	. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
	. " LEFT JOIN ($sb1) sb1 ON lc_monitor_id=mn_monitor_id"
	. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$nendai = floor($fetch->mn_age / 10) * 10;
	if ($nendai > 60)
		$nendai = 60;

	$click_uniq = $fetch->click_count ? 1 : 0;

	$tmp = &$sum_ary['配信数'];
	$tmp['計']['']++;
	$tmp['性別'][$fetch->mn_sex]++;
	$tmp['年代'][$nendai]++;
	$tmp['未既婚'][$fetch->mn_mikikon]++;
	$tmp['住所'][$fetch->mn_jitaku_area]++;
	$tmp['職業'][$fetch->mn_shokugyou_cd]++;

	$tmp = &$sum_ary['クリック数'];
	$tmp['計'][''] += $fetch->click_count;
	$tmp['性別'][$fetch->mn_sex] += $fetch->click_count;
	$tmp['年代'][$nendai] += $fetch->click_count;
	$tmp['未既婚'][$fetch->mn_mikikon] += $fetch->click_count;
	$tmp['住所'][$fetch->mn_jitaku_area] += $fetch->click_count;
	$tmp['職業'][$fetch->mn_shokugyou_cd] += $fetch->click_count;

	$tmp = &$sum_ary['クリック数ユニーク'];
	$tmp['計'][''] += $click_uniq;
	$tmp['性別'][$fetch->mn_sex] += $click_uniq;
	$tmp['年代'][$nendai] += $click_uniq;
	$tmp['未既婚'][$fetch->mn_mikikon] += $click_uniq;
	$tmp['住所'][$fetch->mn_jitaku_area] += $click_uniq;
	$tmp['職業'][$fetch->mn_shokugyou_cd] += $click_uniq;
}

// クリック率計算
foreach ($name_ary as $name => &$ary) {
	foreach ($ary as $code) {
		$send_num = $sum_ary['配信数'][$name][$code];
		$click_num = $sum_ary['クリック数'][$name][$code];
		$uniq_num = $sum_ary['クリック数ユニーク'][$name][$code];

		if ($send_num != 0) {
			$sum_ary['クリック率'][$name][$code] = round($click_num / $send_num * 100, 2);
			$sum_ary['クリック率ユニーク'][$name][$code] = round($uniq_num / $send_num * 100, 2);
		}

		if ($uniq_num != 0)
			$sum_ary['クリック数（回／人）'][$name][$code] = round($click_num / $uniq_num, 2);
	}
}

// 集計結果出力
output_sum($name_ary, $sum_ary, '配信数');
output_sum($name_ary, $sum_ary, 'クリック数');
output_sum($name_ary, $sum_ary, 'クリック率');
output_sum($name_ary, $sum_ary, 'クリック数ユニーク');
output_sum($name_ary, $sum_ary, 'クリック率ユニーク');
output_sum($name_ary, $sum_ary, 'クリック数（回／人）');

exit;
?>