<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:浴槽入浴エコ診断処理クラス
'******************************************************/

// 浴槽入浴エコ診断クラス
class sim_yokusou {
	// 現状データ
	var $nyuuyoku_summer;		// 浴槽入浴有無（夏）
	var $nyuuyoku_sp_aut; 	// 浴槽入浴有無（春･秋）
	var $nyuuyoku_winter;		// 浴槽入浴有無（冬）
	var $yuon_summer;				// 湯温（夏）[℃]
	var $yuon_sp_aut;				// 湯温（春･秋）[℃]
	var $yuon_winter;				// 湯温（冬）[℃]
	var $yuhari_ryou;				// 湯はり量[L]
	var $boiler_type;				// 給湯器タイプ

	// 診断データ
	var $yuhari_ryou_e;			// 湯はり変化量[L]
	var $yuhari_flag_e;			// 湯はり上下フラグ
	var $yuon_e;						// 湯温

	// コンストラクタ
	function sim_yokusou() {
		// クラス変数を初期化
		$this->nyuuyoku_summer = 0;
		$this->nyuuyoku_sp_aut = 0;
		$this->nyuuyoku_winter = 0;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->yuhari_ryou = 210;
		$this->boiler_type = 1;

		$this->yuhari_ryou_e = 0;
		$this->yuhari_flag_e = 2;
		$this->yuon_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// 計算クラス生成
		$cal = new cal_yokusou;

		// 現状データで計算
		$cal->nyuuyoku_summer = $this->nyuuyoku_summer;
		$cal->nyuuyoku_sp_aut = $this->nyuuyoku_sp_aut;
		$cal->nyuuyoku_winter = $this->nyuuyoku_winter;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->yuhari_ryou = $this->yuhari_ryou;
		$cal->boiler_type = $this->boiler_type;
		$cal->calculation($calorie_c, $cost_c, $co2_c, $water_c);

		// 診断条件で計算
		$cal->yuhari_ryou += ($this->yuhari_flag_e == 1) ? $this->yuhari_ryou_e : -$this->yuhari_ryou_e;
		$cal->yuon_summer -= $this->yuon_e;
		$cal->yuon_sp_aut -= $this->yuon_e;
		$cal->yuon_winter -= $this->yuon_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e, $water_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;
		$water = $water_c - $water_e;

		// 診断メッセージ出力
		if ($this->yuhari_ryou_e)
			$msg_ary[] = "浴槽の湯はり量を $this->yuhari_ryou_e L(リットル)" . ($this->yuhari_flag_e == 1 ? '多く' : '少なく') . 'し';
		if ($this->yuon_e)
			$msg_ary[] = "浴槽の湯温を $this->yuon_e ℃下げ";
		if ($msg_ary)
			$msg = join('、<br>', $msg_ary) . 'ます。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_YOKUSOU);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->nyuuyoku_summer = array_shift($ary);
			$this->nyuuyoku_sp_aut = array_shift($ary);
			$this->nyuuyoku_winter = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->yuhari_ryou = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->yuhari_ryou_e = array_shift($ary);
			$this->yuhari_flag_e = array_shift($ary);
			$this->yuon_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->nyuuyoku_summer;
		$ary[] = $this->nyuuyoku_sp_aut;
		$ary[] = $this->nyuuyoku_winter;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->yuhari_ryou;
		$ary[] = $this->boiler_type;
		$ary[] = $this->yuhari_ryou_e;
		$ary[] = $this->yuhari_flag_e;
		$ary[] = $this->yuon_e;

		return join(',', $ary);
	}
}

// 浴槽入浴計算クラス
class cal_yokusou {
	var $nyuuyoku_summer;		// 浴槽入浴有無（夏）
	var $nyuuyoku_sp_aut;		// 浴槽入浴有無（春･秋）
	var $nyuuyoku_winter;		// 浴槽入浴有無（冬）
	var $yuon_summer;				// 湯温（夏）[℃]
	var $yuon_sp_aut;				// 湯温（春･秋）[℃]
	var $yuon_winter;				// 湯温（冬）[℃]
	var $yuhari_ryou;				// 湯はり量[L]
	var $boiler_type;				// 給湯器タイプ

	// カロリー、料金、CO2、水量計算
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// 機器効率取得
		$efficiency = $this->get_efficiency($this->boiler_type);

		// 冬
		if ($this->nyuuyoku_winter) {
			$calorie_winter = $this->yuhari_ryou * DAYS_WINTER * ($this->yuon_winter - TMP_WINTER) / $efficiency;
			$water_winter = $this->yuhari_ryou * DAYS_WINTER;
		}

		// 夏
		if ($this->nyuuyoku_summer) {
			$calorie_summer = $this->yuhari_ryou * DAYS_SUMMER * ($this->yuon_summer - TMP_SUMMER) / $efficiency;
			$water_summer = $this->yuhari_ryou * DAYS_SUMMER;
		}

		// 春秋
		if ($this->nyuuyoku_sp_aut) {
			$calorie_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT * ($this->yuon_sp_aut - TMP_SP_AUT) / $efficiency;
			$water_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT;
		}

		// 合計（kcal->Mcal, L->ｍ3)
		$calorie = ($calorie_winter + $calorie_summer + $calorie_sp_aut) / 1000;
		$water = ($water_winter + $water_summer + $water_sp_aut) / 1000;

		// 料金、CO2計算
		switch ($this->boiler_type) {
		case 1:		// ガスタイプ
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		case 2:		// 電気タイプ
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 3:		// 灯油タイプ
			$l = oil_mcal_l($calorie);
			$cost = cnv_oil_mny($l, $this->boiler_type);
			$co2 = cnv_oil_co2($l, $this->boiler_type);
			break;
		}
		$cost += cnv_wtr_mny($water);
		$co2 += cnv_wtr_co2($water);
	}

	// 機器効率取得
	function get_efficiency($boiler_type) {
		switch ($boiler_type) {
		case 1:		// ガスタイプ
			return EFF_GAS;
		case 2:		// 電気タイプ
			return EFF_ELE;
		case 3:		// 灯油タイプ
			return EFF_OIL;
		}
	}
}
?>