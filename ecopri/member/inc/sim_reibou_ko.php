<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:個室冷房エコ診断処理クラス
'******************************************************/

// 個室冷房エコ診断クラス
class sim_reibou_ko {
	// 現状データ
	var $cooler_type_ary;		// 冷房の種類
	var $temperature_ary;		// 設定温度[℃]
	var $hours_ary;					// 使用時間帯（就寝後）

	// 診断データ
	var $cooler_type_ary_e;	// 冷房の種類
	var $temperature_ary_e;	// 設定温度[℃]
	var $hours_ary_e;				// 使用時間帯（就寝後）

	// コンストラクタ
	function sim_reibou_ko() {
		// クラス変数を初期化
		for ($i = 1; $i <= 3; $i++) {
			$this->cooler_type_ary[$i] = 1;
			$this->temperature_ary[$i] = 27;
			$this->hours_ary[$i] = 0;

			$this->cooler_type_ary_e[$i] = 1;
			$this->temperature_ary_e[$i] = 27;
			$this->hours_ary_e[$i] = 0;
		}
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_reibou_ko;

		// 現状データで計算
		for ($i = 1; $i <= 3; $i++) {
			$cal->cooler_type_ary[$i] = $this->cooler_type_ary[$i];
			$cal->temperature_ary[$i] = $this->temperature_ary[$i];
			$cal->hours_ary[$i] = $this->hours_ary[$i];
		}
		$cal->calculation($calorie_c, $cost_c, $co2_c, $calorie_ko_c);

		// 診断条件で計算
		for ($i = 1; $i <= 3; $i++) {
			$cal->cooler_type_ary[$i] = $this->cooler_type_ary_e[$i];
			$cal->temperature_ary[$i] = $this->temperature_ary_e[$i];
			$cal->hours_ary[$i] = $this->hours_ary_e[$i];
		}
		$cal->calculation($calorie_e, $cost_e, $co2_e, $calorie_ko_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 部屋名称セット
		$room_name[1] = '主寝室';
		$room_name[2] = '子供室(中央)';
		$room_name[3] = '子供室(西側)';

		// 診断メッセージ編集
		for ($i = 1; $i <= 3; $i++) {
			// エネルギーが減ったものについてのみメッセージ出力
			if ($calorie_ko_c[$i] > $calorie_ko_e[$i]) {
				$msg_ary = array();

				// 部屋名称

				// 冷房種類変更チェック
				if ($this->cooler_type_ary[$i] != $this->cooler_type_ary_e[$i])
					$msg_ary[] = 'を' . reibou_shurui_name($this->cooler_type_ary_e[$i]) . 'に替え';

				// 設定温度変更チェック
				if ($this->temperature_ary[$i] > $this->temperature_ary_e[$i])
					$msg_ary[] = '設定温度を' . ($this->temperature_ary[$i] - $this->temperature_ary_e[$i]) . '℃低くし';
				elseif ($this->temperature_ary[$i] < $this->temperature_ary_e[$i])
					$msg_ary[] = '設定温度を' . ($this->temperature_ary_e[$i] - $this->temperature_ary[$i]) . '℃高くし';

				// 使用時間変更チェック
				if ($this->hours_ary[$i] > $this->hours_ary_e[$i])
					$msg_ary[] = '使用時間を' . ($this->hours_ary[$i] - $this->hours_ary_e[$i]) . '時間短くし';
				if ($this->hours_ary_e[$i] > $this->hours_ary[$i])
					$msg_ary[] = '使用時間を' . ($this->hours_ary_e[$i] - $this->hours_ary[$i]) . '時間長くし';

				// 診断メッセージ出力
				if ($msg_ary)
					$msg .= "{$room_name[$i]}のエアコン" . join('、', $msg_ary) . 'ます。<br>';
			}
		}
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_REIBOU_KO);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			for ($i = 1; $i <= 3; $i++) {
				$this->cooler_type_ary[$i] = array_shift($ary);
				$this->temperature_ary[$i] = array_shift($ary);
				$this->hours_ary[$i] = array_shift($ary);
				$this->cooler_type_ary_e[$i] = array_shift($ary);
				$this->temperature_ary_e[$i] = array_shift($ary);
				$this->hours_ary_e[$i] = array_shift($ary);
			}
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		for ($i = 1; $i <= 3; $i++) {
			$ary[] = $this->cooler_type_ary[$i];
			$ary[] = $this->temperature_ary[$i];
			$ary[] = $this->hours_ary[$i];
			$ary[] = $this->cooler_type_ary_e[$i];
			$ary[] = $this->temperature_ary_e[$i];
			$ary[] = $this->hours_ary_e[$i];
		}

		return join(',', $ary);
	}
}

// 個室冷房計算クラス
class cal_reibou_ko {
	var $cooler_type_ary;		// 冷房の種類
	var $temperature_ary;		// 設定温度[℃]
	var $hours_ary;					// 使用時間帯（就寝後）

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2, &$calorie_ko) {
		$calorie = 0;
		for ($i = 1; $i <= 3; $i++) {
			if ($this->hours_ary[$i]) {
				// 部屋コード
				$room_cd = $i + 1;

				// 時間帯ごとの熱負荷を合計
				$th_load = $this->get_th_load($room_cd, 4, $this->hours_ary[$i], $this->temperature_ary[$i]);

				// 冷房機器情報取得
				$this->get_cooler_info($this->cooler_type_ary[$i], $ave_power, $max_power, $ave_cop);

				// 冷房立上がり運転分
				$startup = 0.25;

				// 冷房エネルギー量計算
				$calorie_ko[$i] = $th_load / $ave_cop + $startup;
				$calorie += $calorie_ko[$i];
			}
		}

		// 料金とCO2算出
		$kwh = ele_mcal_kwh($calorie);
		$cost = cnv_ele_mny($kwh);
		$co2 = cnv_ele_co2($kwh);
	}

	// 熱負荷取得
	function get_th_load($room_cd, $time_cd, $hours, $temp) {
		$sql = "SELECT cl_calorie"
				. " FROM m_cooler_load"
				. " WHERE cl_room_cd=$room_cd AND cl_time_cd=$time_cd AND cl_used_hours=$hours AND cl_temperature=$temp";
		return db_fetch1($sql);
	}

	// 冷房機器情報取得
	function get_cooler_info($cooler_type, &$ave_power, &$max_power, &$ave_cop) {
		$sql = "SELECT cp_ave_power,cp_max_power,cp_ave_cop"
				. " FROM m_cooler_prm"
				. " WHERE cp_cooler_type=$cooler_type AND cp_room_cd=1";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$ave_power = $fetch->cp_ave_power;
			$max_power = $fetch->cp_max_power;
			$ave_cop = $fetch->cp_ave_cop;
		}
	}

	// 立上がり運転分計算
	function get_startup($max_power, $hours1, $hours2, $hours3) {
		// 運転時間取得
		$startup_time = (($hours1 ? 1 : 0) + ($hours2 ? 1 : 0) + ($hours3 ? 1 : 0)) * 0.25;

		return $max_power * $startup_time * DAYS_REIBOU * KWH_MCAL;
	}
}
?>