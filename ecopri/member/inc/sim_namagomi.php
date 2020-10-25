<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:生ごみエコ診断処理クラス
'******************************************************/

// 生ごみエコ診断クラス
class sim_namagomi {
	// 現状データ
	var $weekday_dust;		// 生ごみ量(平日)[kg]
	var $holiday_dust;		// 生ごみ量(休日)[kg]
	var $compost;					// 生ごみ処理機やコンポストの使用

	// 診断データ
	var $compost_e;				// 生ごみ処理機やコンポストの使用

	// コンストラクタ
	function sim_namagomi() {
		$this->weekday_dust = 1;
		$this->holiday_dust = 1;
		$this->compost = 1;
		$this->compost_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_namagomi;

		// 現状データで計算
		$cal->weekday_dust = $this->weekday_dust;
		$cal->holiday_dust = $this->holiday_dust;
		$cal->compost = $this->compost;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->compost = $this->compost_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ出力
		if ($this->compost == 1 && $this->compost_e == 2)
			$msg = '生ごみ処理機やコンポストを使用する。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_NAMAGOMI);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->weekday_dust = array_shift($ary);
			$this->holiday_dust = array_shift($ary);
			$this->compost = array_shift($ary);
			$this->compost_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->weekday_dust;
		$ary[] = $this->holiday_dust;
		$ary[] = $this->compost;
		$ary[] = $this->compost_e;

		return join(',', $ary);
	}
}

// 生ごみエコ診断計算クラス
class cal_namagomi {
	var $weekday_dust;		// 生ごみ量(平日)[kg]
	var $holiday_dust;		// 生ごみ量(休日)[kg]
	var $compost;					// 生ごみ処理機やコンポストの使用

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		// 生ごみ処理機やコンポストの使用状態
		if ($this->compost) {
			// 使用する
			$dust = 0;
		} else {
			// 使用しない
			$dust = $this->weekday_dust * SINK_CORNER * DAYS_WEEKDAY + $this->holiday_dust * SINK_CORNER * DAYS_HOLIDAY;
		}

		$calorie = 0;
		$cost = 0;
		$co2 = cnv_dst_co2($dust);
	}
}
?>