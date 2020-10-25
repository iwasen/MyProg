<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

if ($id != '' && is_numeric($id)) {
	// アフィリエイト有効チェック
	$sql = "SELECT COUNT(*)"
			. " FROM t_affiliate"
			. " JOIN t_affiliate_mgr ON afm_affiliate_mgr_id=af_affiliate_mgr_id"
			. " JOIN t_monitor ON mn_monitor_id=af_monitor_id"
			. " WHERE af_affiliate_id=$id AND af_status=1 AND current_date BETWEEN afm_affiliate_start_date AND afm_affiliate_end_date AND mn_status<>9";
	if (db_fetch1($sql) == 1) {
		// アフィリエイトクリック数更新
		$rec['afc_click_count'] = 'afc_click_count+1';
		db_update('t_affiliate_cnt', $rec, "afc_affiliate_id=$id");

		SetCookie('cookie_affiliate_id', $id, 0, '/');
	}
}
redirect('index.php');
?>