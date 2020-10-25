<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:ポイント関連処理
'******************************************************/

// ポイント種類
define('PT_ENQUETE', 1);		// アンケート回答
define('PT_SEISAN', 2);			// 精算
define('PT_MAIL_RECV', 3);	// アンケートメール受信

// モニターポイント加算
function monitor_point($monitor_id, $kind, $point) {
	$point = (int)$point;

	// ポイントログに追加
	if ($point != 0) {
		$sql = sprintf("INSERT INTO l_point (lp_member_id,lp_point_kind,lp_point) VALUES (%s,%s,%s)",
				sql_number($monitor_id),
				sql_number($kind),
				sql_number($point));
		db_exec($sql);
	}

	// モニターポイント情報更新
	$add_point = max($point, 0);
	$sql = "UPDATE t_monitor_point SET mp_curr_point=mp_curr_point+$point,mp_total_point=mp_total_point+$add_point"
			. " WHERE mp_monitor_id=$monitor_id";
	db_exec($sql);
}
?>