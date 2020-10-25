<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート一覧CSV出力処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");

// CSVファイル名
$filename = sprintf('Myenqlist_%s.csv', date('YmdHi'));
prepare_csv($filename);

if ($status == '*')
	$where = "en_status<>9";
else {
	if ($status == '')
		$where = "(en_status=2 OR en_status=3)";
	else
		$where = "en_status=$status";
}

$csv = 'enq_id,アンケート開始日,アンケート終了日,企業名,マーケター名,エージェント名,お題,質問数（SA）,質問数（MA）,質問数（マトリクス）,質問数（FA）,質問数（NA）,質問数（合計）,アンケートポイント,発信数,画像の使用有無';
output_csv($csv);

$sql = "SELECT me_enquete_id,en_start_date,en_end_date,mr_kinmu_name,mr_name1,mr_name2,ag_name1,ag_name2,en_title,en_point,me_send_num"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND (eq_question_type=1 OR eq_question_type=7)) AS count_sa"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND eq_question_type=2) AS count_ma"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND (eq_question_type=4 OR eq_question_type=5)) AS count_matrix"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND (eq_question_type=3 OR eq_question_type=8)) AS count_fa"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND eq_question_type=6) AS count_na"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id) AS count_all"
		. ",(SELECT COUNT(*) FROM t_enq_question WHERE eq_enquete_id=me_enquete_id AND eq_image_id<>0) AS count_image"
		. " FROM t_my_enquete"
		. " JOIN t_enquete ON me_enquete_id=en_enquete_id"
		. " JOIN t_marketer ON me_marketer_id=mr_marketer_id"
		. " LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
		. " WHERE $where ORDER BY me_enquete_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	set_csv($csv, $fetch->me_enquete_id);
	set_csv($csv, str_replace('-', '/', substr($fetch->en_start_date, 0, 10)));
	set_csv($csv, str_replace('-', '/', substr($fetch->en_end_date, 0, 10)));
	set_csv($csv, $fetch->mr_kinmu_name);
	set_csv($csv, "{$fetch->mr_name1}{$fetch->mr_name2}");
	set_csv($csv, "{$fetch->ag_name1}{$fetch->ag_name2}");
	set_csv($csv, $fetch->en_title);
	set_csv($csv, $fetch->count_sa);
	set_csv($csv, $fetch->count_ma);
	set_csv($csv, $fetch->count_matrix);
	set_csv($csv, $fetch->count_fa);
	set_csv($csv, $fetch->count_na);
	set_csv($csv, $fetch->count_all);
	set_csv($csv, $fetch->en_point + 0);
	set_csv($csv, $fetch->me_send_num + 0);
	set_csv($csv, $fetch->count_image == 0 ? '無' : '有');
	output_csv($csv);
}

exit;
?>