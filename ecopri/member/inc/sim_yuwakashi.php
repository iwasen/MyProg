<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:湯沸しエコ診断処理クラス
'******************************************************/

// 湯沸しエコ診断クラス
class sim_yuwakashi {
	// 現状データ
	var $yuwakashi_houhou;	// 湯沸しの方法
	var $yuwakashi_kaisuu;	// 湯沸し回数
	var $yuwakashi_ryou;		// 湯沸し量コード
	var $hoon_houhou;				// 保温の方法
	var $hoon_jikan;				// 保温時間[h]

	// 診断データ
	var $yuwakashi_houhou_e;	// 湯沸しの方法
	var $hoon_houhou_e;				// 保温の方法
	var $hoon_jikan_e;				// 保温時間[h]

	// コンストラクタ
	function sim_yuwakashi() {
		// クラス変数を初期化
		$this->yuwakashi_houhou = 1;
		$this->yuwakashi_kaisuu = 1;
		$this->yuwakashi_ryou = 80;
		$this->hoon_houhou = 1;
		$this->hoon_jikan = 0;
		$this->yuwakashi_houhou_e = 1;
		$this->hoon_houhou_e = 1;
		$this->hoon_jikan_e = 24;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_yuwakashi;

		// 現状データで計算
		$cal->yuwakashi_houhou = $this->yuwakashi_houhou;
		$cal->yuwakashi_kaisuu = $this->yuwakashi_kaisuu;
		$cal->yuwakashi_ryou = $this->yuwakashi_ryou;
		$cal->hoon_houhou = $this->hoon_houhou;
		$cal->hoon_jikan = $this->hoon_jikan;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->yuwakashi_houhou = $this->yuwakashi_houhou_e;
		$cal->hoon_houhou = $this->hoon_houhou_e;
		$cal->hoon_jikan = $this->hoon_jikan_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ編集
		$yuwakashi_houhou = yuwakashi_houhou_name($this->yuwakashi_houhou_e);
		$hoon_houhou = hoon_houhou_name($this->hoon_houhou_e);
		$hoon_jikan = hoon_jikan_name($this->hoon_jikan_e);
		$msg = "湯沸しの方法は、${yuwakashi_houhou}にし、${hoon_houhou}で、${hoon_jikan}保温する。";
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_YUWAKASHI);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->yuwakashi_houhou = array_shift($ary);
			$this->yuwakashi_kaisuu = array_shift($ary);
			$this->yuwakashi_ryou = array_shift($ary);
			$this->hoon_houhou = array_shift($ary);
			$this->hoon_jikan = array_shift($ary);
			$this->yuwakashi_houhou_e = array_shift($ary);
			$this->hoon_houhou_e = array_shift($ary);
			$this->hoon_jikan_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->yuwakashi_houhou;
		$ary[] = $this->yuwakashi_kaisuu;
		$ary[] = $this->yuwakashi_ryou;
		$ary[] = $this->hoon_houhou;
		$ary[] = $this->hoon_jikan;
		$ary[] = $this->yuwakashi_houhou_e;
		$ary[] = $this->hoon_houhou_e;
		$ary[] = $this->hoon_jikan_e;

		return join(',', $ary);
	}
}

// 湯沸し計算クラス
class cal_yuwakashi {
	var $yuwakashi_houhou;	// 湯沸しの方法
	var $yuwakashi_kaisuu;	// 湯沸し回数
	var $yuwakashi_ryou;		// 湯沸し量[L]
	var $hoon_houhou;				// 保温の方法
	var $hoon_jikan;				// 保温時間[h]

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 湯沸し量取得
		$ryou = $this->yuwakashi_ryou / 100;

		// 湯沸し方法によるカロリー計算
		switch ($this->yuwakashi_houhou) {
		case 1:		// 電気ポット
			$yuwakashi_calorie = 13.2 / 60 * $ryou * 1 * $this->yuwakashi_kaisuu * 365 * KWH_MCAL;

			$kwh = ele_mcal_kwh($yuwakashi_calorie);
			$yuwakashi_cost = cnv_ele_mny($kwh);
			$yuwakashi_co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// やかん（ガス）
			$winter = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_WINTER) * DAYS_WINTER / 0.5 / 1000;
			$summer = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SUMMER) * DAYS_SUMMER / 0.5 / 1000;
			$sp_aut = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SP_AUT) * DAYS_SP_AUT / 0.5 / 1000;
			$yuwakashi_calorie = $winter + $summer + $sp_aut;

			$m3 = gas_mcal_m3($yuwakashi_calorie);
			$yuwakashi_cost = cnv_gas_mny($m3);
			$yuwakashi_co2 = cnv_gas_co2($m3);
			break;
		case 3:		// やかん（ＩＨ）
			$winter = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_WINTER) * DAYS_WINTER / 0.9 / 1000;
			$summer = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SUMMER) * DAYS_SUMMER / 0.9 / 1000;
			$sp_aut = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SP_AUT) * DAYS_SP_AUT / 0.9 / 1000;
			$yuwakashi_calorie = $winter + $summer + $sp_aut;

			$kwh = ele_mcal_kwh($yuwakashi_calorie);
			$yuwakashi_cost = cnv_gas_mny($kwh);
			$yuwakashi_co2 = cnv_gas_co2($kwh);
			break;
		}

		// 保温方法によるカロリー計算
		switch ($this->hoon_houhou) {
		case 1:		// 電気ポット
			$hoon_calorie = 0.029 * $this->hoon_jikan * 365 * KWH_MCAL;
			$kwh = ele_mcal_kwh($hoon_calorie);
			$hoon_cost = cnv_ele_mny($kwh);
			$hoon_co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// 魔法瓶
			$hoon_calorie = 0;
			$hoon_cost = 0;
			$hoon_co2 = 0;
			break;
		}

		// 湯沸しと保温を合計
		$calorie = $yuwakashi_calorie + $hoon_calorie;
		$cost = $yuwakashi_cost + $hoon_cost;
		$co2 = $yuwakashi_co2 + $hoon_co2;
	}
}
?>