<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:����å�������Ƚ���CSV����
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

// �ޥ�������̾�μ���
function get_name($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$name .= "��{$fetch[0]}��";
	}
	return $name;
}

// ���׹��ܽ���
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

// �����ID�����å�
if ($job_id == '')
	redirect('index.php');

// CSV�ե�����̾����
prepare_csv("job_clicksum_$job_id.csv");

// ����־������
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

// ������ȴ��ּ���
$sql = "SELECT cc_start_date,cc_end_date FROM t_click_counter WHERE cc_job_id=$job_id ORDER BY cc_ct_id LIMIT 1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$start_date = $fetch->cc_start_date;
	$end_date = $fetch->cc_end_date;
}

// �ۿ�������
set_csv($csv, '�ۿ���');
set_csv($csv, format_date($start_date));
output_csv($csv);

// ������ȴ��ֽ���
set_csv($csv, '������ȴ���');
set_csv($csv, format_date($start_date) . '��' . format_date($end_date));
output_csv($csv);

// �ۿ�������
set_csv($csv, '�ۿ���');
set_csv($csv, $real_send);
output_csv($csv);

// ��̾����
set_csv($csv, '��̾');
set_csv($csv, $subject);
output_csv($csv);

// ���̽���
set_csv($csv, '����');
set_csv($csv, decode_sex($sex));
output_csv($csv);

// ̤��������
set_csv($csv, '̤����');
set_csv($csv, decode_mikikon($mikikon));
output_csv($csv);

// ǯ�����
set_csv($csv, 'ǯ��');
set_csv($csv, ($age_from || $age_to) ? "{$age_from}��{$age_to}" : '����ʤ�');
output_csv($csv);

// �������
set_csv($csv, '����');
set_csv($csv, $kyojuu != '' ? str_replace('/', '', $kyojuu) : '����ʤ�');
output_csv($csv);

// ���Ƚ���
set_csv($csv, '����');
if ($shokugyou != '') {
	$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($shokugyou) ORDER BY sg_order";
	set_csv($csv, get_name($sql));
} else
	set_csv($csv, '����ʤ�');
output_csv($csv);

// ��̣���������
set_csv($csv, '��̣������');
if ($genre != '') {
	$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($genre) ORDER BY kg_order";
	set_csv($csv, get_name($sql));
} else
	set_csv($csv, '����ʤ�');
output_csv($csv);

// �׹���̾����
$name_ary['��'][''] = '';

// ���̹���̾����
for ($i = 1; $i <= 2; $i++)
	$name_ary['����'][decode_sex($i)] = $i;

// ǯ�����̾����
for ($i = 10; $i <= 60; $i += 10)
	$name_ary['ǯ��']["{$i}��" . ($i == 60 ? '��' : '')] = $i;

// ̤��������̾����
for ($i = 1; $i <= 3; $i++)
	$name_ary['̤����'][decode_mikikon($i)] = $i;

// �������̾����
$sql = "SELECT ar_area_cd,ar_area_name FROM m_area ORDER BY ar_area_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($kyojuu == '' || strpos($kyojuu, $fetch->ar_area_name) !== false)
		$name_ary['����'][$fetch->ar_area_name] = $fetch->ar_area_cd;
}

// ���ȹ���̾����
if ($shokugyou != '')
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($shokugyou) ORDER BY sg_shokugyou_cd";
else
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_shokugyou_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$name_ary['����'][$fetch->sg_shokugyou_name] = $fetch->sg_shokugyou_cd;
}

// ���׽���
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

	$tmp = &$sum_ary['�ۿ���'];
	$tmp['��']['']++;
	$tmp['����'][$fetch->mn_sex]++;
	$tmp['ǯ��'][$nendai]++;
	$tmp['̤����'][$fetch->mn_mikikon]++;
	$tmp['����'][$fetch->mn_jitaku_area]++;
	$tmp['����'][$fetch->mn_shokugyou_cd]++;

	$tmp = &$sum_ary['����å���'];
	$tmp['��'][''] += $fetch->click_count;
	$tmp['����'][$fetch->mn_sex] += $fetch->click_count;
	$tmp['ǯ��'][$nendai] += $fetch->click_count;
	$tmp['̤����'][$fetch->mn_mikikon] += $fetch->click_count;
	$tmp['����'][$fetch->mn_jitaku_area] += $fetch->click_count;
	$tmp['����'][$fetch->mn_shokugyou_cd] += $fetch->click_count;

	$tmp = &$sum_ary['����å�����ˡ���'];
	$tmp['��'][''] += $click_uniq;
	$tmp['����'][$fetch->mn_sex] += $click_uniq;
	$tmp['ǯ��'][$nendai] += $click_uniq;
	$tmp['̤����'][$fetch->mn_mikikon] += $click_uniq;
	$tmp['����'][$fetch->mn_jitaku_area] += $click_uniq;
	$tmp['����'][$fetch->mn_shokugyou_cd] += $click_uniq;
}

// ����å�Ψ�׻�
foreach ($name_ary as $name => &$ary) {
	foreach ($ary as $code) {
		$send_num = $sum_ary['�ۿ���'][$name][$code];
		$click_num = $sum_ary['����å���'][$name][$code];
		$uniq_num = $sum_ary['����å�����ˡ���'][$name][$code];

		if ($send_num != 0) {
			$sum_ary['����å�Ψ'][$name][$code] = round($click_num / $send_num * 100, 2);
			$sum_ary['����å�Ψ��ˡ���'][$name][$code] = round($uniq_num / $send_num * 100, 2);
		}

		if ($uniq_num != 0)
			$sum_ary['����å����ʲ󡿿͡�'][$name][$code] = round($click_num / $uniq_num, 2);
	}
}

// ���׷�̽���
output_sum($name_ary, $sum_ary, '�ۿ���');
output_sum($name_ary, $sum_ary, '����å���');
output_sum($name_ary, $sum_ary, '����å�Ψ');
output_sum($name_ary, $sum_ary, '����å�����ˡ���');
output_sum($name_ary, $sum_ary, '����å�Ψ��ˡ���');
output_sum($name_ary, $sum_ary, '����å����ʲ󡿿͡�');

exit;
?>