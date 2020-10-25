<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

follow_end();
exit;

// ¥ê¥ì¡¼¥·¥ç¥ó½ªÎ»½èÍý
function follow_end() {
	db_begin_trans();

	// Ì¤°ú·Ñ¤®¤ÎÁ´¥Ç¡¼¥¿¤ò¼èÆÀ
	$sql = "SELECT ust_user_id,urs_user_id,urs_commant1,urs_commant2,urs_commant3,urs_commant4"
			. " FROM t_user_regist"
			. " JOIN t_user_status ON ust_user_id=urg_user_id"
			. " LEFT JOIN t_user_result ON urs_user_id=ust_user_id"
			. " WHERE urg_handover_date IS NULL";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->urg_user_id;

		// ¥¹¥Æ¡¼¥¿¥¹¤ò°ú·Ñ¤®ºÑ¤ß¤Ë¥»¥Ã¥È
		$rec['ust_status'] = 3;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// ¥³¥á¥ó¥È¥»¥Ã¥È
		if ($fetch->urs_commant1 == '' && $fetch->urs_commant2 == '' && $fetch->urs_commant3 == '' && $fetch->urs_commant4 == '') {
			$rec['urs_commant1'] = sql_char('¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö');
			$rec['urs_commant2'] = sql_char('¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö');
			$rec['urs_commant3'] = sql_char('¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö');
			$rec['urs_commant4'] = sql_char('¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö¡ö');
		}

		// ¥æ¡¼¥¶ÂÐ±þ·ë²ÌÅÐÏ¿¡Ê·èÃå¶èÊ¬:6¡¢¥é¥ó¥¯:D¡Ë
		$rec['urs_kecchaku'] = sql_number(6);
		$rec['urs_rank'] = sql_char('D');
		if ($fetch->urs_user_id == null) {
			$rec['urs_user_id'] = sql_number($user_id);
			db_insert('t_user_result', $rec);
		} else {
			db_update('t_user_result', $rec, "urs_user_id=$user_id");
		}

		// °ú·Ñ¤®Æü»þ¥»¥Ã¥È
		$rec['urg_handover_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id");
	}

	db_commit_trans();
}
?>
