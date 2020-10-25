<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:暖房エコ診断処理クラス
'******************************************************/

// 暖房エコ診断クラス
class sim_danbou {
	// 現状データ
	var $main_heater;		// 主暖房機器
	var $sub_heater;		// 補助暖房機器
	var $temperature;		// 設定温度[℃]
	var $main_hours1;		// 主暖房使用時間（起床〜昼）[h]
	var $main_hours2;		// 主暖房使用時間（昼〜夕方）[h]
	var $main_hours3;		// 主暖房使用時間（夕方以降）[h]
	var $sub_hours1;		// 補助暖房使用時間（起床〜昼）[h]
	var $sub_hours2;		// 補助暖房使用時間（昼〜夕方）[h]
	var $sub_hours3;		// 補助暖房使用時間（夕方以降）[h]

	// 診断データ
	var $main_heater_e;	// 主暖房機器
	var $sub_heater_e;	// 補助暖房機器
	var $temperature_e;	// 設定温度[℃]
	var $main_hours1_e;	// 主暖房使用時間（起床〜昼）[h]
	var $main_hours2_e;	// 主暖房使用時間（昼〜夕方）[h]
	var $main_hours3_e;	// 主暖房使用時間（夕方以降）[h]
	var $sub_hours1_e;	// 補助暖房使用時間（起床〜昼）[h]
	var $sub_hours2_e;	// 補助暖房使用時間（昼〜夕方）[h]
	var $sub_hours3_e;	// 補助暖房使用時間（夕方以降）[h]

	// コンストラクタ
	function sim_danbou() {
		// クラス変数を初期化
		$this->main_heater = 1;
		$this->sub_heater = 8;
		$this->temperature = 18;
		$this->main_hours1 = 0;
		$this->main_hours2 = 0;
		$this->main_hours3 = 0;
		$this->sub_hours1 = 0;
		$this->sub_hours2 = 0;
		$this->sub_hours3 = 0;

		$this->main_heater_e = 1;
		$this->sub_heater_e = 8;
		$this->temperature_e = 18;
		$this->main_hours1_e = 0;
		$this->main_hours2_e = 0;
		$this->main_hours3_e = 0;
		$this->sub_hours1_e = 0;
		$this->sub_hours2_e = 0;
		$this->sub_hours3_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_danbou;

		// 現状データで計算
		$cal->main_heater = $this->main_heater;
		$cal->sub_heater = $this->sub_heater;
		$cal->temperature = $this->temperature;
		$cal->main_hours1 = $this->main_hours1;
		$cal->main_hours2 = $this->main_hours2;
		$cal->main_hours3 = $this->main_hours3;
		$cal->sub_hours1 = $this->sub_hours1;
		$cal->sub_hours2 = $this->sub_hours2;
		$cal->sub_hours3 = $this->sub_hours3;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->main_heater = $this->main_heater_e;
		$cal->sub_heater = $this->sub_heater_e;
		$cal->temperature = $this->temperature_e;
		$cal->main_hours1 = $this->main_hours1_e;
		$cal->main_hours2 = $this->main_hours2_e;
		$cal->main_hours3 = $this->main_hours3_e;
		$cal->sub_hours1 = $this->sub_hours1_e;
		$cal->sub_hours2 = $this->sub_hours2_e;
		$cal->sub_hours3 = $this->sub_hours3_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 主暖房種類変更チェック
		if ($this->main_heater != $this->main_heater_e)
			$msg_ary[] = '主暖房を' . danbou_shurui_name($this->main_heater_e) . 'に替え';

		// 主暖房使用時間変更チェック
		$hours = $this->main_hours1 + $this->main_hours2 + $this->main_hours3;
		$hours_e = $this->main_hours1_e + $this->main_hours2_e + $this->main_hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '主暖房の使用時間を' . ($hours - $hours_e) . '時間短くし';
		if ($hours_e > $hours)
			$msg_ary[] = '主暖房の使用時間を' . ($hours_e - $hours) . '時間長くし';

		// 補助暖房種類変更チェック
		if ($this->sub_heater != $this->sub_heater_e)
			$msg_ary[] = '補助暖房を' . danbou_shurui_name($this->sub_heater_e) . 'に替え';

		// 補助暖房使用時間変更チェック
		$hours = $this->sub_hours1 + $this->sub_hours2 + $this->sub_hours3;
		$hours_e = $this->sub_hours1_e + $this->sub_hours2_e + $this->sub_hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '補助暖房の使用時間を' . ($hours - $hours_e) . '時間短くし';
		if ($hours_e > $hours)
			$msg_ary[] = '補助暖房の使用時間を' . ($hours_e - $hours) . '時間長くし';

		// 設定温度変更チェック
		if ($this->temperature > $this->temperature_e)
			$msg_ary[] = '暖房の設定温度を' . ($this->temperature - $this->temperature_e) . '℃低くし';
		elseif ($this->temperature < $this->temperature_e)
			$msg_ary[] = '暖房の設定温度を' . ($this->temperature_e - $this->temperature) . '℃高くし';

		// 診断メッセージ出力
		if ($msg_ary)
			$msg = join('、<br>', $msg_ary) . 'ます。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_DANBOU_LD);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->main_heater = array_shift($ary);
			$this->sub_heater = array_shift($ary);
			$this->temperature = array_shift($ary);
			$this->main_hours1 = array_shift($ary);
			$this->main_hours2 = array_shift($ary);
			$this->main_hours3 = array_shift($ary);
			$this->sub_hours1 = array_shift($ary);
			$this->sub_hours2 = array_shift($ary);
			$this->sub_hours3 = array_shift($ary);
			$this->main_heater_e = array_shift($ary);
			$this->sub_heater_e = array_shift($ary);
			$this->temperature_e = array_shift($ary);
			$this->main_hours1_e = array_shift($ary);
			$this->main_hours2_e = array_shift($ary);
			$this->main_hours3_e = array_shift($ary);
			$this->sub_hours1_e = array_shift($ary);
			$this->sub_hours2_e = array_shift($ary);
			$this->sub_hours3_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->main_heater;
		$ary[] = $this->sub_heater;
		$ary[] = $this->temperature;
		$ary[] = $this->main_hours1;
		$ary[] = $this->main_hours2;
		$ary[] = $this->main_hours3;
		$ary[] = $this->sub_hours1;
		$ary[] = $this->sub_hours2;
		$ary[] = $this->sub_hours3;
		$ary[] = $this->main_heater_e;
		$ary[] = $this->sub_heater_e;
		$ary[] = $this->temperature_e;
		$ary[] = $this->main_hours1_e;
		$ary[] = $this->main_hours2_e;
		$ary[] = $this->main_hours3_e;
		$ary[] = $this->sub_hours1_e;
		$ary[] = $this->sub_hours2_e;
		$ary[] = $this->sub_hours3_e;

		return join(',', $ary);
	}
}

// 暖房計算クラス
class cal_danbou {
	var $main_heater;		// 主暖房機器コード
	var $sub_heater;		// 補助暖房機器コード
	var $temperature;		// 設定温度[℃]
	var $main_hours1;		// 主暖房使用時間（起床〜昼）[h]
	var $main_hours2;		// 主暖房使用時間（昼〜夕方）[h]
	var $main_hours3;		// 主暖房使用時間（夕方以降）[h]
	var $sub_hours1;		// 補助暖房使用時間（起床〜昼）[h]
	var $sub_hours2;		// 補助暖房使用時間（昼〜夕方）[h]
	var $sub_hours3;		// 補助暖房使用時間（夕方以降）[h]

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 主暖房機器情報取得
		$this->get_heater_info($this->main_heater, $main_ave_power, $main_max_power, $main_ave_cop, $main_heat_type);

		// 時間帯ごとの熱負荷を合計
		$th_load = $this->get_th_load(1, 1, $this->main_hours1, $this->temperature)
						 + $this->get_th_load(1, 2, $this->main_hours2, $this->temperature)
						 + $this->get_th_load(1, 3, $this->main_hours3, $this->temperature);

		// 熱拡散係数
		$main_th_diffusion = $this->get_th_diffusion($main_heat_type);

		// 主暖房立上がり運転分
		$main_startup = $this->get_startup($main_max_power, $main_heat_type, $this->main_hours1, $this->main_hours2, $this->main_hours3);

		// 主暖房ファン運転分
		$main_fan = $this->get_fan($main_heat_type, $this->main_hours1, $this->main_hours2, $this->main_hours3);

		// 主暖房カロリー計算
		$main_calorie = $th_load * $main_th_diffusion / $main_ave_cop + $main_startup + $main_fan;

		// 主暖房料金、CO2計算
		$this->get_cost_co2($this->main_heater, $main_calorie, $main_cost, $main_co2);

		if ($this->sub_heater) {
			// 補助暖房機器情報取得
			$this->get_heater_info($this->sub_heater, $sub_ave_power, $sub_max_power, $sub_ave_cop, $sub_heat_type);

			// 補助暖房立上がり運転分
			$sub_startup = $this->get_startup($sub_max_power, $sub_heat_type, $this->sub_hours1, $this->sub_hours2, $this->sub_hours3);

			// 補助暖房ファン運転分
			$sub_fan = $this->get_fan($sub_heat_type, $this->sub_hours1, $this->sub_hours2, $this->sub_hours3);

			// 補助暖房カロリー量計算
			$sub_calorie = $sub_ave_power * ($this->sub_hours1 + $this->sub_hours2 + $this->sub_hours3) * DAYS_DANBOU * KWH_MCAL + $sub_startup + $sub_fan;

			// 補助暖房料金、CO2計算
			$this->get_cost_co2($this->sub_heater, $sub_calorie, $sub_cost, $sub_co2);
		}

		// 主暖房と補助暖房の合計
		$calorie = $main_calorie + $sub_calorie;
		$cost = $main_cost + $sub_cost;
		$co2 = $main_co2 + $sub_co2;
	}

	// 熱負荷取得
	function get_th_load($room_cd, $time_cd, $hours, $temp) {
		$sql = "SELECT hl_calorie"
				. " FROM m_heater_load"
				. " WHERE hl_room_cd=$room_cd AND hl_time_cd=$time_cd AND hl_used_hours=$hours AND hl_temperature=$temp";
		return db_fetch1($sql);
	}

	// 暖房機器情報取得
	function get_heater_info($heater_cd, &$ave_power, &$max_power, &$ave_cop, &$heat_type) {
		$sql = "SELECT hp_ave_power,hp_max_power,hp_ave_cop,hp_heat_type"
				. " FROM m_heater_prm"
				. " WHERE hp_heater_cd=$heater_cd AND hp_room_cd=1";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$ave_power = $fetch->hp_ave_power;
			$max_power = $fetch->hp_max_power;
			$ave_cop = $fetch->hp_ave_cop;
			$heat_type = $fetch->hp_heat_type;
		}
	}

	// 熱拡散係数取得
	function get_th_diffusion($heat_type) {
		switch ($heat_type) {
		case 1:		// 温風型
			return 1.2;
		case 2:		// 輻射型
			return 1.0;
		}
	}

	// 立上がり運転分計算
	function get_startup($max_power, $heat_type, $hours1, $hours2, $hours3) {
		// 立上がり時間取得
		$startup_time = $this->get_startup_time($heat_type, $hours1, $hours2, $hours3);

		return $max_power * $startup_time * DAYS_DANBOU * KWH_MCAL;
	}

	// ファン運転分計算
	function get_fan($heat_type, $hours1, $hours2, $hours3) {
		// １日の使用時間
		$total_hours = $hours1 + $hours2 + $hours3;

		// 立上がり時間
		$startup_time = $this->get_startup_time($heat_type, $hours1, $hours2, $hours3);

		return 0.021 * ($total_hours + $startup_time) * DAYS_DANBOU * KWH_MCAL;
	}

	// 立上がり時間取得
	function get_startup_time($heat_type, $hours1, $hours2, $hours3) {
		// 立上がり時間取得
		switch ($heat_type) {
		case 1:		// 温風型
			$time = 0.25;
			break;
		case 2:		// 輻射型
			$time = 0.5;
			break;
		}

		// 運転回数取得
		$count = ($hours1 ? 1 : 0) + ($hours2 ? 1 : 0) + ($hours3 ? 1 : 0);

		return $time * $count;
	}

	// 料金とCO2計算
	function get_cost_co2($heater, $calorie, &$cost, &$co2) {
		switch ($heater) {
		case 1:		// エアコン普及型
		case 2:		// エアコン省エネ型
		case 6:		// ホットカーペット
		case 7:		// こたつ
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 3:		// ガスファンヒータ
		case 5:		// ガス温水式床暖房
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		case 4:		// 石油ファンヒータ
			$l = oil_mcal_l($calorie);
			$cost = cnv_oil_mny($l);
			$co2 = cnv_oil_co2($l);
			break;
		}
	}
}
?>