<?
/******************************************************
' System :「きゃららFactory」ユーザページ
' Content:似顔絵ダウンロードページ
'******************************************************/

$inc = "../inc";
include_once("$inc/database.php");
include_once("$inc/status.php");

$id = $_SERVER['QUERY_STRING'];
if ($id != '') {
	// 似顔絵ファイル名取得
	$sql = "SELECT nd_nigaoe_id,nd_nigaoe_file FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$nigaoe_id = $fetch->nd_nigaoe_id;
		$filename = $fetch->nd_nigaoe_file;

		if ($nigaoe_id) {
			// 似顔絵画像URL取得
			$url = get_system_info('url_nigaoe');

			// ファイル名連結
			$url .= $filename;

			// 似顔絵データテーブル更新
			$rec['nd_status'] = STATUS_DOWNLOADED;
			$rec['nd_download_date'] = 'current_timestamp';
			db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$nigaoe_id AND nd_status=" . STATUS_USER_NOTIFIED);
		}
	}
}

// Shift_JISで出力
mb_http_output('Shift_JIS');
ob_start('mb_output_handler');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>きゃららFactory</title>
</head>
<body>
<img src="<?=$url?>" alt="にがおえ">
</body>
</html>
