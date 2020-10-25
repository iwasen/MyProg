<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:送信者情報CSV出力
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

if ($job_id == '')
	redirect('index.php');

// アンケートID取得
$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id";
$enquete_id = db_fetch1($sql);

// アンケート開始日時取得
$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

// CSVデータ出力
$sql = "SELECT mn_monitor_id,el_send_date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
		. " FROM t_enquete_list"
		. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " WHERE el_enquete_id=$enquete_id AND el_status=1"
		. " ORDER BY el_monitor_id";
$csv = '"ID",送信日時,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域';
$filename = "job_send_$job_id.csv";

prepare_csv($filename);
output_csv($csv);

$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$csv = '';
	set_csv($csv, $fetch->mn_monitor_id);
	set_csv($csv, format_datetime($fetch->el_send_date));
	set_csv($csv, decode_sex($fetch->mn_sex));
	set_csv($csv, decode_mikikon($fetch->mn_mikikon));
	set_csv($csv, $fetch->mn_age);
	set_csv($csv, $fetch->jitaku_area_name);
	set_csv($csv, $fetch->sg_shokugyou_name);
	set_csv($csv, $fetch->gs_gyoushu_name);
	set_csv($csv, $fetch->ss_shokushu_name);
	set_csv($csv, $fetch->kinmu_area_name);

	output_csv($csv);
}
?>