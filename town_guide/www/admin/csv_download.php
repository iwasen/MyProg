<?
$inc = '../inc';
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");

// 日時(YYYY/MM/DD hh:mm)
function format_datetime($date) {
	sscanf($date, "%d-%d-%d %d:%d", &$y, &$m, &$d, &$h, &$i);
	return sprintf("%04d/%02d/%02d %02d:%02d", $y, $m, $d, $h, $i);
}

prepare_csv('output.csv');

// カテゴリ数取得
$sql = "SELECT COUNT(*) FROM m_category";
$category_num = db_fetch1($sql);

// 業種別回答データ取得
$sql = "SELECT * FROM t_ans_category";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$category_ans[$fetch->ac_seq_no][$fetch->ac_category_id] = $fetch->ac_category_ans;
	$gyoshu_tel[$fetch->ac_seq_no][$fetch->ac_category_id] = $fetch->ac_gyoshu_tel;
	$gyoshu_addr[$fetch->ac_seq_no][$fetch->ac_category_id] = $fetch->ac_gyoshu_addr;
}

// 見出し行出力
set_csv($csv, 'SEQ');
$qno = 0;
for ($category_id = 1; $category_id <= $category_num; $category_id++) {
	$qno++;
	set_csv($csv, "Q{$qno}カテゴリ");
	$qno++;
	set_csv($csv, "Q{$qno}電話");
	set_csv($csv, "Q{$qno}住所");
}
$qno++;
set_csv($csv, "Q{$qno}-1");
set_csv($csv, "Q{$qno}-2");
$qno++;
set_csv($csv, "Q{$qno}");
set_csv($csv, 'パターン');
set_csv($csv, 'F1');
set_csv($csv, 'F2');
set_csv($csv, 'F3');
set_csv($csv, 'F4');
set_csv($csv, 'F5');
set_csv($csv, 'F6');
set_csv($csv, 'F7');
set_csv($csv, 'id');
set_csv($csv, 'gkey');
set_csv($csv, '回答時間');
output_csv($csv);

// アンケート回答出力
$sql = "SELECT * FROM t_ans_user JOIN t_ans_fix ON af_seq_no=au_seq_no ORDER BY au_seq_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->au_seq_no;

	// SEQ出力
	set_csv($csv, $seq_no);

	// 業種別回答出力
	for ($category_id = 1; $category_id <= $category_num; $category_id++) {
		set_csv($csv, $category_ans[$seq_no][$category_id]);
		set_csv($csv, $gyoshu_tel[$seq_no][$category_id]);
		set_csv($csv, $gyoshu_addr[$seq_no][$category_id]);
	}

	// 固定設問出力
	set_csv($csv, $fetch->af_free_answer1);
	set_csv($csv, $fetch->af_free_answer2);
	set_csv($csv, $fetch->af_sel_answer);

	// パターン出力
	set_csv($csv, $fetch->au_pattern);

	// 回答者情報出力
	set_csv($csv, $fetch->af_todofuken);
	set_csv($csv, $fetch->af_sex);
	set_csv($csv, $fetch->af_age);
	set_csv($csv, $fetch->af_mikikon);
	set_csv($csv, $fetch->af_shokugyo);
	set_csv($csv, $fetch->af_mail_addr);
	set_csv($csv, $fetch->af_answer_id);

	// パラメータ情報出力
	set_csv($csv, $fetch->au_user_id);
	set_csv($csv, $fetch->au_group_id);

	// 回答日時出力
	set_csv($csv, format_datetime($fetch->au_date));

	// CSV１行出力
	output_csv($csv);
}
exit;
?>