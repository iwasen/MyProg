<?
/******************************************************
' System :きかせて・net共通
' Content:モニタープロファイル集計処理
'******************************************************/

function monitor_profile() {
	// トランザクション開始
	db_begin_trans();

	// 初期化処理
	profile_init();

	// 各種集計処理
	profile_all(0);				// 全体
	profile_age(1);				// 年代別
	profile_mikikon(2);		// 未既婚別
	profile_area(3);			// 居住地域別
	profile_shokugyou(4);	// 職業別
	profile_gyoushu(5);		// 業種別
	profile_shokushu(6);	// 職種別
	profile_conveni(7);		// よく行くコンビニ別
	profile_super(8);			// よく行くスーパー別
	profile_genre(9);			// 興味ジャンル別

	// コミット
	db_commit_trans();
}

// 初期化処理
function profile_init() {
	// 旧集計値クリア
	$sql = "DELETE FROM t_profile_date";
	db_exec($sql);
	$sql = "DELETE FROM t_profile_data";
	db_exec($sql);

	// 集計日時セット
	$sql = "INSERT INTO t_profile_date (pd_date) values (current_timestamp)";
	db_exec($sql);
}

// 全体集計
function profile_all($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		profile_add($fetch, $data_mm, $data_mw, $data_pm, $data_pw);
	}

	// 集計値を保存
	profile_insert($profile_id, 0, $data_mm, $data_mw, $data_pm, $data_pw);
}

// 年代別集計
function profile_age($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,trunc(date_part('Y',age(mn_birthday))/10) AS age"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,age";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$age = (int)$fetch->age;
		if ($age <= 1)
			$age = 1;
		elseif ($age >= 6)
			$age = 6;

		profile_add($fetch, $data_mm[$age], $data_mw[$age], $data_pm[$age], $data_pw[$age]);
	}

	// 集計値を保存
	for ($i = 1; $i <= 6; $i++)
		profile_insert($profile_id, $i * 10, $data_mm[$i], $data_mw[$i], $data_pm[$i], $data_pw[$i]);
}

// 未既婚別集計
function profile_mikikon($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,mn_mikikon"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,mn_mikikon";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->mn_mikikon;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	for ($i = 1; $i <= 2; $i++)
		profile_insert($profile_id, $i, $data_mm[$i], $data_mw[$i], $data_pm[$i], $data_pw[$i]);
}

// 居住地域別集計
function profile_area($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,mn_jitaku_area"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,mn_jitaku_area";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->mn_jitaku_area;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT ar_area_cd FROM m_area ORDER BY ar_area_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->ar_area_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// 職業別集計
function profile_shokugyou($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,mn_shokugyou_cd"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,mn_shokugyou_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->mn_shokugyou_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT sg_shokugyou_cd FROM m_shokugyou ORDER BY sg_shokugyou_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->sg_shokugyou_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// 業種別集計
function profile_gyoushu($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,mn_gyoushu_cd"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,mn_gyoushu_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->mn_gyoushu_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT gs_gyoushu_cd FROM m_gyoushu ORDER BY gs_gyoushu_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->gs_gyoushu_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// 職種別集計
function profile_shokushu($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,mn_shokushu_cd"
			. " FROM t_monitor"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,mn_shokushu_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->mn_shokushu_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT ss_shokushu_cd FROM m_shokushu ORDER BY ss_shokushu_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->ss_shokushu_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// よく行くコンビニ別集計
function profile_conveni($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,dc_conveni_cd"
			. " FROM t_monitor JOIN t_conveni ON mn_monitor_id=dc_monitor_id"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,dc_conveni_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->dc_conveni_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT cv_conveni_cd FROM m_conveni ORDER BY cv_conveni_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->cv_conveni_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// よく行くスーパー別集計
function profile_super($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,ds_super_cd"
			. " FROM t_monitor JOIN t_super ON mn_monitor_id=ds_monitor_id"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,ds_super_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->ds_super_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT sp_super_cd FROM m_super ORDER BY sp_super_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->sp_super_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// 興味ジャンル別集計
function profile_genre($profile_id) {
	// SQL実行
	$sql = "SELECT mn_type,mn_sex,count(*) AS cnt,dk_genre_cd"
			. " FROM t_monitor JOIN t_genre ON mn_monitor_id=dk_monitor_id"
			. " WHERE mn_status=0"
			. " GROUP BY mn_type,mn_sex,dk_genre_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	// 集計値取得
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->dk_genre_cd;
		profile_add($fetch, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}

	// 集計値を保存
	$sql = "SELECT kg_genre_cd FROM m_genre ORDER BY kg_genre_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$code = (int)$fetch->kg_genre_cd;
		profile_insert($profile_id, $code, $data_mm[$code], $data_mw[$code], $data_pm[$code], $data_pw[$code]);
	}
}

// 集計値加算
function profile_add($fetch, &$data_mm, &$data_mw, &$data_pm, &$data_pw) {
	// 集計値取得
	$cnt = (int)$fetch->cnt;

	switch ($fetch->mn_type) {
	case 1:		// モニター
		switch ($fetch->mn_sex) {
		case 1:
			$data_mm += $cnt;
			break;
		case 2:
			$data_mw += $cnt;
			break;
		}
		break;
	case 2:		// Ｍパートナー
		switch ($fetch->mn_sex) {
		case 1:
			$data_pm += $cnt;
			break;
		case 2:
			$data_pw += $cnt;
			break;
		}
		break;
	}
}

// 集計値保存
function profile_insert($profile_id, $code, $data_mm, $data_mw, $data_pm, $data_pw) {
	if (empty($data_mm))
		$data_mm = 0;

	if (empty($data_mw))
		$data_mw = 0;

	if (empty($data_pm))
		$data_pm = 0;

	if (empty($data_pw))
		$data_pw = 0;

	$sql = "INSERT INTO t_profile_data (pf_profile_id,pf_code,pf_data_mm,pf_data_mw,pf_data_pm,pf_data_pw)"
			. " values($profile_id,$code,$data_mm,$data_mw,$data_pm,$data_pw)";
	db_exec($sql);
}
?>