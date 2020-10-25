<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:追い炊きエコ診断処理クラス
'******************************************************/

// 追い炊きエコ診断クラス
class sim_oidaki {
	// 現状データ
	var $ninzuu_summer;			// 入浴人数（夏）
	var $ninzuu_sp_aut;			// 入浴人数（春･秋）
	var $ninzuu_winter;			// 入浴人数（冬）
	var $yuon_summer;				// 湯温（夏）[℃]
	var $yuon_sp_aut;				// 湯温（春･秋）[℃]
	var $yuon_winter;				// 湯温（冬）[℃]
	var $nyuuyoku_kankaku;	// 入浴間隔[h]
	var $yokusou_futa;			// 浴槽の蓋
	var $yuhari_ryou;				// 湯はり量[L]
	var $boiler_type;				// 給湯器タイプ

	// 診断データ
	var $nyuuyoku_kankaku_e;	// 入浴間隔[h]
	var $yokusou_futa_e;			// 浴槽の蓋

	// コンストラクタ
	function sim_oidaki() {
		// クラス変数を初期化
		$this->ninzuu_summer = 1;
		$this->ninzuu_sp_aut = 1;
		$this->ninzuu_winter = 1;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->nyuuyoku_kankaku = 0;
		$this->yokusou_futa = 2;
		$this->yuhari_ryou = 210;
		$this->boiler_type = 1;

		$this->nyuuyoku_kankaku_e = 0;
		$this->yokusou_futa_e = 2;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_oidaki;

		// 現状データで計算
		$cal->ninzuu_summer = $this->ninzuu_summer;
		$cal->ninzuu_sp_aut = $this->ninzuu_sp_aut;
		$cal->ninzuu_winter = $this->ninzuu_winter;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->nyuuyoku_kankaku = $this->nyuuyoku_kankaku;
		$cal->yokusou_futa = $this->yokusou_futa;
		$cal->yuhari_ryou = $this->yuhari_ryou;
		$cal->boiler_type = $this->boiler_type;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->nyuuyoku_kankaku = $this->nyuuyoku_kankaku_e;
		$cal->yokusou_futa = $this->yokusou_futa_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ出力
		if ($this->nyuuyoku_kankaku > $this->nyuuyoku_kankaku_e)
			$msg_ary[] = '家族の入浴間隔を ' . ($this->nyuuyoku_kankaku - $this->nyuuyoku_kankaku_e) . ' 分以内にし';

		if ($this->yokusou_futa == 2 && $this->yokusou_futa_e == 1)
			$msg_ary[] = '入浴中以外は浴槽に蓋をし';

		if ($msg_ary)
			$msg = join('、<br>', $msg_ary) . 'ます。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_OIDAKI);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->ninzuu_summer = array_shift($ary);
			$this->ninzuu_sp_aut = array_shift($ary);
			$this->ninzuu_winter = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->nyuuyoku_kankaku = array_shift($ary);
			$this->yokusou_futa = array_shift($ary);
			$this->yuhari_ryou = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->nyuuyoku_kankaku_e = array_shift($ary);
			$this->yokusou_futa_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->ninzuu_summer;
		$ary[] = $this->ninzuu_sp_aut;
		$ary[] = $this->ninzuu_winter;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->nyuuyoku_kankaku;
		$ary[] = $this->yokusou_futa;
		$ary[] = $this->yuhari_ryou;
		$ary[] = $this->boiler_type;
		$ary[] = $this->nyuuyoku_kankaku_e;
		$ary[] = $this->yokusou_futa_e;

		return join(',', $ary);
	}
}

// 追い炊きエコ診断計算クラス
class cal_oidaki {
	var $ninzuu_summer;			// 入浴人数（夏）
	var $ninzuu_sp_aut;			// 入浴人数（春･秋）
	var $ninzuu_winter;			// 入浴人数（冬）
	var $yuon_summer;				// 湯温（夏）[℃]
	var $yuon_sp_aut;				// 湯温（春･秋）[℃]
	var $yuon_winter;				// 湯温（冬）[℃]
	var $nyuuyoku_kankaku;	// 入浴間隔[h]
	var $yokusou_futa;			// 浴槽の蓋
	var $yuhari_ryou;				// 湯はり量[L]
	var $boiler_type;				// 給湯器タイプ

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 湯温低下率取得
		switch ($this->yokusou_futa) {
		case 1:		// 蓋あり
			$teika_ritsu = TEIKA_FUTAARI;
			break;
		case 2:		// 蓋なし
			$teika_ritsu = TEIKA_FUTANASHI;
			break;
		}

		// 機器効率取得
		$efficiency = $this->get_efficiency($this->boiler_type);

		// 季節ごとに計算
		$winter = $this->get_calorie($this->ninzuu_winter, $this->yuon_winter, DAYS_WINTER, $teika_ritsu, $efficiency);
		$summer = $this->get_calorie($this->ninzuu_summer, $this->yuon_summer, DAYS_SUMMER, $teika_ritsu, $efficiency);
		$sp_aut = $this->get_calorie($this->ninzuu_sp_aut, $this->yuon_sp_aut, DAYS_SP_AUT, $teika_ritsu, $efficiency);

		// 合計（kcal->Mcal)
		$calorie = ($winter + $summer + $sp_aut) / 1000;

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
	}

	// 季節ごとのカロリー計算
	function get_calorie($ninzuu, $yuon, $days, $teika_ritsu, $efficiency) {
		// 湯温低下分
		$yuon_teika = $ninzuu * (NYUUYOKU_JIKAN * TEIKA_FUTANASHI + $this->nyuuyoku_kankaku * $teika_ritsu) / 60;

		// カロリー計算
		return $this->yuhari_ryou * $days * $yuon_teika / $efficiency;
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