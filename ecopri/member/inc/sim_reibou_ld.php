<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:LD冷房エコ診断処理クラス
'******************************************************/

// LD冷房エコ診断クラス
class sim_reibou_ld {
	// 現状データ
	var $cooler_type;		// 冷房の種類
	var $temperature;		// 設定温度[℃]
	var $hours1;				// 使用時間帯（起床〜昼）
	var $hours2;				// 使用時間帯（昼〜夕方）
	var $hours3;				// 使用時間帯（夕方以降）

	// 診断データ
	var $cooler_type_e;	// 冷房の種類
	var $temperature_e;	// 設定温度[℃]
	var $hours1_e;			// 使用時間帯（起床〜昼）
	var $hours2_e;			// 使用時間帯（昼〜夕方）
	var $hours3_e;			// 使用時間帯（夕方以降）

	// コンストラクタ
	function sim_reibou_ld() {
		// クラス変数を初期化
		$this->cooler_type = 1;
		$this->temperature = 27;
		$this->hours1 = 0;
		$this->hours2 = 0;
		$this->hours3 = 0;
		$this->cooler_type_e = 1;
		$this->temperature_e = 27;
		$this->hours1_e = 0;
		$this->hours2_e = 0;
		$this->hours3_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_reibou_ld;

		// 現状データで計算
		$cal->cooler_type = $this->cooler_type;
		$cal->temperature = $this->temperature;
		$cal->hours1 = $this->hours1;
		$cal->hours2 = $this->hours2;
		$cal->hours3 = $this->hours3;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->cooler_type = $this->cooler_type_e;
		$cal->temperature = $this->temperature_e;
		$cal->hours1 = $this->hours1_e;
		$cal->hours2 = $this->hours2_e;
		$cal->hours3 = $this->hours3_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 冷房種類変更チェック
		if ($this->cooler_type != $this->cooler_type_e)
			$msg_ary[] = 'を' . reibou_shurui_name($this->cooler_type_e) . 'に替え';

		// 設定温度変更チェック
		if ($this->temperature > $this->temperature_e)
			$msg_ary[] = '設定温度を' . ($this->temperature - $this->temperature_e) . '℃低くし';
		elseif ($this->temperature < $this->temperature_e)
			$msg_ary[] = '設定温度を' . ($this->temperature_e - $this->temperature) . '℃高くし';

		// 使用時間変更チェック
		$hours = $this->hours1 + $this->hours2 + $this->hours3;
		$hours_e = $this->hours1_e + $this->hours2_e + $this->hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '使用時間を' . ($hours - $hours_e) . '時間短くし';
		if ($hours_e > $hours)
			$msg_ary[] = '使用時間を' . ($hours_e - $hours) . '時間長くし';

		// 診断メッセージ出力
		if ($msg_ary)
			$msg = 'LDのエアコン' . join('、<br>', $msg_ary) . 'ます。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_REIBOU_LD);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->cooler_type = array_shift($ary);
			$this->temperature = array_shift($ary);
			$this->hours1 = array_shift($ary);
			$this->hours2 = array_shift($ary);
			$this->hours3 = array_shift($ary);
			$this->cooler_type_e = array_shift($ary);
			$this->temperature_e = array_shift($ary);
			$this->hours1_e = array_shift($ary);
			$this->hours2_e = array_shift($ary);
			$this->hours3_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->cooler_type;
		$ary[] = $this->temperature;
		$ary[] = $this->hours1;
		$ary[] = $this->hours2;
		$ary[] = $this->hours3;
		$ary[] = $this->cooler_type_e;
		$ary[] = $this->temperature_e;
		$ary[] = $this->hours1_e;
		$ary[] = $this->hours2_e;
		$ary[] = $this->hours3_e;

		return join(',', $ary);
	}
}

// LD冷房計算クラス
class cal_reibou_ld {
	var $cooler_type;		// 冷房の種類
	var $temperature;		// 設定温度[℃]
	var $hours1;				// 使用時間帯（起床〜昼）
	var $hours2;				// 使用時間帯（昼〜夕方）
	var $hours3;				// 使用時間帯（夕方以降）

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 時間帯ごとの熱負荷を合計
		$th_load = $this->get_th_load(1, 1, $this->hours1, $this->temperature)
						 + $this->get_th_load(1, 2, $this->hours2, $this->temperature)
						 + $this->get_th_load(1, 3, $this->hours3, $this->temperature);

		// 冷房機器情報取得
		$this->get_cooler_info($this->cooler_type, $ave_power, $max_power, $ave_cop);

		// 冷房立上がり運転分
		$startup = $this->get_startup($max_power, $this->hours1, $this->hours2, $this->hours3);

		// カロリー計算
		$calorie = $th_load / $ave_cop + $startup;

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