<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:運転方法エコ診断処理クラス
'******************************************************/

// 運転方法エコ診断クラス
class sim_unten {
	// 現状データ
	var $car_cd;				// 車の種類
	var $engine_vol;		// 排気量[cc]
	var $kyori;					// 月間走行距離[km]
	var $idling;				// アイドリング時間[分]

	// 診断データ
	var $kyori_e;				// 月間走行距離[km]
	var $idling_e;			// アイドリング時間[分]

	// コンストラクタ
	function sim_unten() {
		// クラス変数を初期化
		$this->car_cd = 1;
		$this->engine_vol[1] = 2;
		$this->engine_vol[2] = 5;
		$this->engine_vol[3] = 3;
		$this->kyori = 100;
		$this->idling = 10;
		$this->kyori_e = 0;
		$this->idling_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_unten;

		// 現状データで計算
		$cal->car_cd = $this->car_cd;
		$cal->engine_vol = $this->engine_vol[$this->car_cd];
		$cal->kyori = $this->kyori;
		$cal->idling = $this->idling;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->kyori = $this->kyori * (100 - $this->kyori_e) / 100;
		$cal->idling = $this->idling * (100 - $this->idling_e) / 100;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ編集
		if ($this->kyori_e)
			$msg_ary[] = "月間走行距離を{$this->kyori_e}％";
		if ($this->idling_e)
			$msg_ary[] = "駐車中のアイドリング時間を{$this->idling_e}％";
		if ($msg_ary)
			$msg = join('、<br>', $msg_ary) . '少なくします。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_UNTENHOUHOU);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->car_cd = 1;
			$this->engine_vol[1] = array_shift($ary);
			$this->engine_vol[2] = array_shift($ary);
			$this->engine_vol[3] = array_shift($ary);
			$this->kyori = array_shift($ary);
			$this->idling = array_shift($ary);
			$this->kyori_e = array_shift($ary);
			$this->idling_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->car_cd;
		$ary[] = $this->engine_vol[1];
		$ary[] = $this->engine_vol[2];
		$ary[] = $this->engine_vol[3];
		$ary[] = $this->kyori;
		$ary[] = $this->idling;
		$ary[] = $this->kyori_e;
		$ary[] = $this->idling_e;

		return join(',', $ary);
	}
}

// 運転方法エコ診断計算クラス
class cal_unten {
	var $car_cd;				// 車の種類
	var $engine_vol;		// 排気量[cc]
	var $kyori;					// 月間走行距離[km]
	var $idling;				// アイドリング時間[分]

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 消費燃料[L]
		$l = $this->kyori * 12 / $this->get_fuel_cost() + 0.14 * $this->idling / 10 * 365;

		// カロリー計算
		switch ($this->car_cd) {
		case 1:		// ガソリン車
		case 3:		// ハイブリッド車
			$calorie = gso_l_mcal($l);
			break;
		case 2:		// ディーゼル車
			$calorie = dsl_l_mcal($l);
			break;
		}

		// 料金、CO2計算
		$cost = cnv_gso_mny($l, $this->car_cd);
		$co2 = cnv_gso_co2($l, $this->car_cd);
	}

	// 燃費取得
	function get_fuel_cost() {
		$sql = "SELECT fp_cost FROM m_fuel_prm WHERE fp_car_type_cd=$this->car_cd AND fp_engine_vol_cd=$this->engine_vol";
		return db_fetch1($sql);
	}
}
?>