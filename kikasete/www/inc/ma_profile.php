<?
/******************************************************
' System :きかせて・net共通
' Content:追加プロファイル関連
'******************************************************/

// 追加プロファイル情報取得
function get_ma_profile() {
	$ma_profile_tbl = array();
    
	// プロファイル追加項目テーブル読み込み
	$sql = "SELECT pfi_profile_id,pfi_profile_name"
			. " FROM t_profile_item"
			. " WHERE pfi_hidden_flag=false"
			. " ORDER BY pfi_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ma_profile_tbl[$fetch->pfi_profile_id] = $fetch->pfi_profile_name;
		
	}

	return $ma_profile_tbl;
}
?>
