<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:洗濯エコ診断処理クラス
'******************************************************/

// 洗濯エコ診断クラス
class sim_sentaku {
	// 現状データ
	var $capacity;				// 洗濯層の容量[kg]
	var $weekday_times;		// 一日の洗濯回数（平日）
	var $holiday_times;		// 一日の洗濯回数（休日）
	var $ratio;						// 洗濯物量の割合
	var $nokoriyu;				// 風呂の残り湯の使用

	// 診断データ
	var $nokoriyu_e;			// 風呂の残り湯の使用

	// コンストラクタ
	function sim_sentaku() {
		$this->capacity = 6;
		$this->weekday_times = 1;
		$this->holiday_times = 1;
		$this->ratio = 80;
		$this->nokoriyu = 2;
		$this->nokoriyu_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// 計算クラス生成
		$cal = new cal_sentaku;

		// 現状データで計算
		$cal->capacity = $this->capacity;
		$cal->weekday_times = $this->weekday_times;
		$cal->holiday_times = $this->holiday_times;
		$cal->ratio = $this->ratio;
		$cal->nokoriyu = $this->nokoriyu;
		$cal->calculation($calorie_c, $cost_c, $co2_c, $water_c);

		// 診断条件で計算
		$cal->nokoriyu = $this->nokoriyu_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e, $water_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;
		$water = $water_c - $water_e;

		// 診断メッセージ出力
		$msg = '洗濯に、風呂の残り湯を「' . nokoriyu_name($this->nokoriyu_e) . '」にする。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_SENTAKU);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->capacity = array_shift($ary);
			$this->weekday_times = array_shift($ary);
			$this->holiday_times = array_shift($ary);
			$this->ratio = array_shift($ary);
			$this->nokoriyu = array_shift($ary);
			$this->nokoriyu_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->capacity;
		$ary[] = $this->weekday_times;
		$ary[] = $this->holiday_times;
		$ary[] = $this->ratio;
		$ary[] = $this->nokoriyu;
		$ary[] = $this->nokoriyu_e;

		return join(',', $ary);
	}
}

// 洗濯計算クラス
class cal_sentaku {
	var $capacity;				// 洗濯層の容量[kg]
	var $weekday_times;		// 一日の洗濯回数（平日）
	var $holiday_times;		// 一日の洗濯回数（休日）
	var $ratio;						// 洗濯物量の割合
	var $nokoriyu;				// 風呂の残り湯の使用

	// カロリー、料金、CO2、水量計算
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// 水量取得
		$water_vol = $this->get_water_vol();

		// 水道使用回数取得
		$water_use = $this->get_water_use();

		// 平日と休日の水量計算
		$weekday = $water_vol * $water_use * $this->weekday_times * DAYS_WEEKDAY;
		$holiday = $water_vol * $water_use * $this->holiday_times * DAYS_HOLIDAY;

		$calorie = 0;
		$water = ($weekday + $holiday) / 1000;
		$cost = cnv_wtr_mny($water);
		$co2 = cnv_wtr_co2($water);
	}

	// 水量取得
	function get_water_vol() {
		$sql = "SELECT wp_water FROM m_washer_prm WHERE wp_capacity=$this->capacity AND wp_ratio=$this->ratio";
		return db_fetch1($sql);
	}

	// 残り湯の使用から水道使用回数取得
	function get_water_use() {
		switch ($this->nokoriyu) {
		case 1:		// 使用しない
			return 3;
		case 2:		// 洗いのみ使用
			return 2;
		case 3;		// 洗いとすすぎ1回使用
			return 1;
		}
	}
}
