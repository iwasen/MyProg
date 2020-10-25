<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:洗濯乾燥エコ診断処理クラス
'******************************************************/

// 洗濯乾燥エコ診断クラス
class sim_kansou {
	// 現状データ
	var $dryer_type;			// 乾燥機の種類
	var $capacity;				// 乾燥機の容量[kg]
	var $times;						// 使用回数
	var $method_fair;			// 乾燥方法（晴れの日）
	var $method_rain;			// 乾燥方法（雨の日）

	// 診断データ
	var $dryer_type_e;		// 乾燥機の種類
	var $method_fair_e;		// 乾燥方法（晴れの日）
	var $method_rain_e;		// 乾燥方法（雨の日）

	// コンストラクタ
	function sim_kansou() {
		$this->dryer_type = 4;
		$this->capacity = 4;
		$this->times = 0;
		$this->method_fair = 1;
		$this->method_rain = 1;
		$this->dryer_type_e = 4;
		$this->method_fair_e = 1;
		$this->method_rain_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_kansou;

		// 現状データで計算
		$cal->dryer_type = $this->dryer_type;
		$cal->capacity = $this->capacity;
		$cal->times = $this->times;
		$cal->method_fair = $this->method_fair;
		$cal->method_rain = $this->method_rain;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->dryer_type = $this->dryer_type_e;
		$cal->method_fair = $this->method_fair_e;
		$cal->method_rain = $this->method_rain_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ出力
		$dryer_type = kansou_shurui_name($this->dryer_type_e);
		$method_fair = kansou_houhou_name($this->method_fair_e);
		$method_rain = kansou_houhou_name($this->method_rain_e);
		$msg = "乾燥機は、${dryer_type}とし、晴れの日は、${method_fair}。<br>雨の日は、${method_rain}。";
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_KANSOU);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->dryer_type = array_shift($ary);
			$this->capacity = array_shift($ary);
			$this->times = array_shift($ary);
			$this->method_fair = array_shift($ary);
			$this->method_rain = array_shift($ary);
			$this->dryer_type_e = array_shift($ary);
			$this->method_fair_e = array_shift($ary);
			$this->method_rain_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->dryer_type;
		$ary[] = $this->capacity;
		$ary[] = $this->times;
		$ary[] = $this->method_fair;
		$ary[] = $this->method_rain;
		$ary[] = $this->dryer_type_e;
		$ary[] = $this->method_fair_e;
		$ary[] = $this->method_rain_e;

		return join(',', $ary);
	}
}

// 洗濯乾燥計算クラス
class cal_kansou {
	var $dryer_type;			// 乾燥機の種類
	var $capacity;				// 乾燥機の容量[kg]
	var $times;						// 使用回数
	var $method_fair;			// 乾燥方法（晴れの日）
	var $method_rain;			// 乾燥方法（雨の日）

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 晴れの日を計算
		$this->get_calorie_sub($this->method_fair, DAYS_FAIR, $calorie_fair, $cost_fair, $co2_fair);

		// 雨の日を計算
		$this->get_calorie_sub($this->method_rain, DAYS_RAIN, $calorie_rain, $cost_rain, $co2_rain);

		// 晴れと雨を加算
		$calorie = $calorie_fair + $calorie_rain;
		$cost = $cost_fair + $cost_rain;
		$co2 = $co2_fair + $co2_rain;
	}

	// 乾燥方法によりカロリー計算
	function get_calorie_sub($method, $days, &$calorie, &$cost, &$co2) {
		switch ($method) {
		case 1:		// 乾燥機は使用しない
			$calorie = 0;
			break;
		case 2:		// 乾燥機で全乾燥
			$calorie = $this->get_power() * $this->get_drying_time() * $this->times * $days * KWH_MCAL;
			break;
		case 3:		// 乾燥機でやや乾燥させた後干す
			$calorie = $this->get_power() * (20 / 60) * $this->times * $days * KWH_MCAL;
			break;
		case 4:		// 干した後、生乾きのものは乾燥機を使う
			$calorie = $this->get_power() * 1 * $this->times * $days * KWH_MCAL;
			break;
		}

		// 乾燥機の種類により料金とCO2を計算
		switch ($this->dryer_type) {
		case 1:		// 電気式衣類乾燥機
		case 3:		// 電気式洗濯乾燥機
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// ガス式衣類乾燥機
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		}
	}

	// 消費電力取得
	function get_power() {
		$sql = "SELECT dp_power FROM m_dryer_prm WHERE dp_dryer_type=$this->dryer_type AND dp_capacity=$this->capacity";
		return db_fetch1($sql);
	}

	// 乾燥時間取得
	function get_drying_time() {
		switch ($this->dryer_type) {
		case 1:		// 電気式(ドラム)
			return 2;
		case 2:		// ガス式
			return 1;
		case 3:		// 電気式(上開き)
			return 4;
		}
	}
}

// 生ごみエコ診断計算クラス
class es_namagomi {
	var $weekday_dust;		// 生ごみ量(平日)[kg]
	var $holiday_dust;		// 生ごみ量(休日)[kg]
	var $compost;					// 生ごみ処理機やコンポストの使用

	// ごみ量計算[kg]
	function get_dust() {
		// 生ごみ処理機やコンポストの使用状態
		switch ($this->compost) {
		case 1:		// 使用しない
			$dust = $this->weekday_dust * SINK_CORNER * DAYS_WEEKDAY + $this->holiday_dust * SINK_CORNER * DAYS_HOLIDAY;
			break;
		case 2:		// 使用する
			$dust = 0;
			break;
		}

		return $dust;
	}
}
?>