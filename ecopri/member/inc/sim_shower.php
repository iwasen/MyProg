<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:シャワーエコ診断処理クラス
'******************************************************/

// シャワーエコ診断クラス
class sim_shower {
	// 現状データ
	var $ryuuryou;				// シャワー流量[L/分]
	var $yuon_summer;			// 湯温（夏）[℃]
	var $yuon_sp_aut;			// 湯温（春･秋）[℃]
	var $yuon_winter;			// 湯温（冬）[℃]
	var $boiler_type;			// 給湯器タイプ

	// 診断データ
	var $shower_jikan;		// シャワー短縮時間[分]

	// コンストラクタ
	function sim_shower() {
		// クラス変数を初期化
		$this->ryuuryou = 10;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->boiler_type = 1;
		$this->shower_jikan = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// 計算クラス生成
		$cal = new cal_shower;

		// 診断条件で計算
		$cal->ryuuryou = $this->ryuuryou;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->boiler_type = $this->boiler_type;
		$cal->shower_jikan = $this->shower_jikan;
		$cal->calculation($energy, $cost, $co2, $water);

		// 診断メッセージ出力
		if ($this->shower_jikan)
			$msg = "シャワー・蛇口使用時間を、全員で $this->shower_jikan 分短くします。";
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_SHOWER);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->ryuuryou = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->shower_jikan = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->ryuuryou;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->boiler_type;
		$ary[] = $this->shower_jikan;

		return join(',', $ary);
	}
}

// シャワーエコ診断計算クラス
class cal_shower {
	var $ryuuryou;				// シャワー流量[L/分]
	var $yuon_summer;			// 湯温（夏）[℃]
	var $yuon_sp_aut;			// 湯温（春･秋）[℃]
	var $yuon_winter;			// 湯温（冬）[℃]
	var $boiler_type;			// 給湯器タイプ
	var $shower_jikan;		// シャワー使用時間[分]

	// カロリー、料金、CO2、水量計算
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// 機器効率取得
		$efficiency = $this->get_efficiency($this->boiler_type);

		// 冬
		$calorie_winter = $this->ryuuryou * $this->shower_jikan * DAYS_WINTER * ($this->yuon_winter - TMP_WINTER) / $efficiency;
		$water_winter = $this->ryuuryou * $this->shower_jikan * DAYS_WINTER;

		// 夏
		$calorie_summer = $this->ryuuryou * $this->shower_jikan * DAYS_SUMMER * ($this->yuon_summer - TMP_SUMMER) / $efficiency;
		$water_summer = $this->ryuuryou * $this->shower_jikan * DAYS_SUMMER;

		// 春秋
		$calorie_sp_aut = $this->ryuuryou * $this->shower_jikan * DAYS_SP_AUT * ($this->yuon_sp_aut - TMP_SP_AUT) / $efficiency;
		$water_sp_aut = $this->ryuuryou * $this->shower_jikan * DAYS_SP_AUT;

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