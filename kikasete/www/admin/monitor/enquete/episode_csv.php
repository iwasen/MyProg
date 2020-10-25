<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");

// CSVデータ出力
$filename = "episode_$seq_no.csv";
prepare_csv($filename);

$csv = "モニターID,性別,年齢,未既婚,職業,居住地域,ハンドル名,内容";
output_csv($csv);

// アンケート開始日時取得
$sql = "SELECT en_start_date FROM t_enquete JOIN t_free_enquete ON en_enquete_id=fe_enquete_id WHERE fe_seq_no=$seq_no";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

$sql = "SELECT ep_episode,ep_handle_name,mn_monitor_id,mn_sex,DATE_PART('Y', AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,sg_shokugyou_name,ar_area_name"
		. " FROM t_episode"
		. " JOIN t_monitor ON mn_monitor_id=ep_monitor_id"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " WHERE ep_seq_no=$seq_no"
		. " ORDER BY mn_monitor_id";

$result = db_exec($sql);
$nrow = pg_numrows($result);
$no = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$csv = '';
	set_csv($csv, $fetch->mn_monitor_id);
	set_csv($csv, decode_sex($fetch->mn_sex));
	set_csv($csv, $fetch->mn_age);
	set_csv($csv, decode_mikikon($fetch->mn_mikikon));
	set_csv($csv, $fetch->sg_shokugyou_name);
	set_csv($csv, $fetch->ar_area_name);
	set_csv($csv, $fetch->ep_handle_name);
	set_csv($csv, $fetch->ep_episode);

	output_csv($csv);
}
?>