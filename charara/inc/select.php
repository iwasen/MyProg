<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:選択肢表示処理
'******************************************************/

// 年
function select_year($start_year, $default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$end_year = date('Y') + 4;
	for ($i = $start_year; $i < $end_year; $i++) {
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
	}
}

// 月
function select_month($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 12; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 日
function select_day($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 31; $i++)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 時間
function select_hour($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 24; $i++) {
		if ($i < 12)
			echo '<option ', value_selected($i, $selected), ">AM $i</option>\n";
		else
			echo '<option ', value_selected($i, $selected), ">PM ", $i - 12, "</option>\n";
	}
}

// 分
function select_minute($default, $selected, $step = 1) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 0; $i < 60; $i++) {
			echo '<option ', value_selected($i, $selected), ">$i</option>\n";
			$i += ($step - 1);
	}
}

// 表示行数
function select_displine($selected) {
	echo '<option ', value_selected(0, $selected), ">- 全て -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// 性別
function select_sex($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

		echo '<option ', value_selected(1, $selected), '>男性</option>', "\n";
		echo '<option ', value_selected(2, $selected), '>女性</option>', "\n";
}

// 有効/無効
function select_judge($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

		echo '<option ', value_selected(0, $selected), '>有効</option>', "\n";
		echo '<option ', value_selected(9, $selected), '>無効</option>', "\n";
}

// スタッフ所属種別
function select_staff_kind($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sk_kind_cd,sk_name FROM m_staff_kind ORDER BY sk_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sk_kind_cd, $selected), '>', htmlspecialchars($fetch->sk_name), '</option>', "\n";
	}
}

// スタッフ所属
function select_staff_org($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT so_org_cd,so_name FROM m_staff_org ORDER BY so_name_kana";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->so_org_cd, $selected), '>', htmlspecialchars($fetch->so_name), '</option>', "\n";
	}
}

// レベルランク
function select_level_rank($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	for ($i = 1; $i <= 5; $i++) {
		echo '<option ', value_selected($i, $selected), '>', $i , '</option>', "\n";
	}
}

// ステータス選択
function select_status($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

		echo '<option ', value_selected(1, $selected), '>&nbsp;1：課金サーバから受信</option>', "\n";
		echo '<option ', value_selected(2, $selected), '>&nbsp;2：ユーザからの申込み受付</option>', "\n";
		echo '<option ', value_selected(3, $selected), '>&nbsp;3：写真受信済み</option>', "\n";
		echo '<option ', value_selected(4, $selected), '>&nbsp;4：作画スタッフ割り当て済み</option>', "\n";
		echo '<option ', value_selected(5, $selected), '>&nbsp;5：似顔絵作成中</option>', "\n";
		echo '<option ', value_selected(6, $selected), '>&nbsp;6：似顔絵作成完了</option>', "\n";
		echo '<option ', value_selected(7, $selected), '>&nbsp;7：チェックＯＫ</option>', "\n";
		echo '<option ', value_selected(8, $selected), '>&nbsp;8：ユーザに通知済み</option>', "\n";
		echo '<option ', value_selected(9, $selected), '>&nbsp;9：ユーザダウンロード済み</option>', "\n";
		echo '<option ', value_selected(10, $selected), '>10：終了</option>', "\n";
		echo '<option ', value_selected(11, $selected), '>11：メール返信待ち</option>', "\n";
}

// 課金サーバ選択
function select_ac_server($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT as_server_id FROM m_account_server ORDER BY as_server_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->as_server_id, $selected), '>', htmlspecialchars($fetch->as_server_id), '</option>', "\n";
	}
}

// スタッフ選択
function select_staff($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT st_staff_id, (st_name1 || st_name2) AS st_name FROM m_staff ORDER BY st_name1_kana";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->st_staff_id, $selected), '>', htmlspecialchars($fetch->st_name), '</option>', "\n";
	}
}

// キャンペーン選択
function select_campaign($default, $selectted, $server_id = '') {
	if ($default)
		echo "<option value=''>$default</option>\n";

	if ($server_id)
		$where = "WHERE cp_server_id=$server_id";

	$sql = "SELECT cp_campaign_cd, cp_name FROM m_campaign $where ORDER BY cp_campaign_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cp_campaign_cd, $selected), '>', htmlspecialchars($fetch->cp_name), '</option>', "\n";
	}
}

// 商品選択
function select_product($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT pd_product_cd, pd_name FROM m_product ORDER BY pd_product_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->pd_product_cd, $selected), '>', htmlspecialchars($fetch->pd_name), '</option>', "\n";
	}
}

// 画風選択
function select_brushwork($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT bw_brushwork_cd, bw_name FROM m_brushwork ORDER BY bw_brushwork_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->bw_brushwork_cd, $selected), '>', htmlspecialchars($fetch->bw_name), '</option>', "\n";
	}
}
?>