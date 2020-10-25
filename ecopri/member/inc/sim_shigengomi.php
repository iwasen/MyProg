<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:生ごみエコ診断処理クラス
'******************************************************/

// 生ごみエコ診断クラス
class sim_shigengomi {
	// 現状データ
	var $milk_num;				// 牛乳パック本数
	var $milk_recycle;		// 牛乳パックリサイクル
	var $pet_num;					// ペットボトル本数
	var $pet_recycle;			// ペットボトルリサイクル
	var $alminum_num;			// アルミ缶飲料本数
	var $alminum_recycle;	// アルミ缶飲料リサイクル

	// 診断データ
	var $milk_recycle_e;		// 牛乳パックリサイクル
	var $pet_recycle_e;			// ペットボトルリサイクル
	var $alminum_recycle_e;	// アルミ缶飲料リサイクル

	// コンストラクタ
	function sim_shigengomi() {
		$this->milk_num = 1;
		$this->milk_recycle = 1;
		$this->pet_num = 1;
		$this->pet_recycle = 1;
		$this->alminum_num = 1;
		$this->alminum_recycle = 1;
		$this->milk_recycle_e = 1;
		$this->pet_recycle_e = 1;
		$this->alminum_recycle_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// 計算クラス生成
		$cal = new cal_shigengomi;

		// 現状データで計算
		$cal->milk_num = $this->milk_num;
		$cal->milk_recycle = $this->milk_recycle;
		$cal->pet_num = $this->pet_num;
		$cal->pet_recycle = $this->pet_recycle;
		$cal->alminum_num = $this->alminum_num;
		$cal->alminum_recycle = $this->alminum_recycle;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// 診断条件で計算
		$cal->milk_recycle = $this->milk_recycle_e;
		$cal->pet_recycle = $this->pet_recycle_e;
		$cal->alminum_recycle = $this->alminum_recycle_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// 差分を計算
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// 診断メッセージ出力
		if ($this->milk_recycle_e)
			$msg_ary[] .= '牛乳パック';
		if ($this->pet_recycle_e)
			$msg_ary[] = 'ペットボトル';
		if ($this->alminum_recycle_e)
			$msg_ary[] = 'アルミ缶';
		if ($msg_ary)
			$msg = join('、', $msg_ary) . 'をリサイクルする。';
	}

	// DBから診断データを読み込み
	function read_eco_data() {
		// エコ診断データを取得
		$eco_data = read_sim_data(GANBA_SHIGENGOMI);
		if ($eco_data) {
			// クラス変数にセット
			$ary = explode(',', $eco_data);
			$this->milk_num = array_shift($ary);
			$this->milk_recycle = array_shift($ary);
			$this->pet_num = array_shift($ary);
			$this->pet_recycle = array_shift($ary);
			$this->alminum_num = array_shift($ary);
			$this->alminum_recycle = array_shift($ary);
			$this->milk_recycle_e = array_shift($ary);
			$this->pet_recycle_e = array_shift($ary);
			$this->alminum_recycle_e = array_shift($ary);
		}
	}

	// エコ診断データをカンマ区切りで取得
	function get_eco_data() {
		// エコ診断データをカンマ区切りに編集
		$ary[] = $this->milk_num;
		$ary[] = $this->milk_recycle;
		$ary[] = $this->pet_num;
		$ary[] = $this->pet_recycle;
		$ary[] = $this->alminum_num;
		$ary[] = $this->alminum_recycle;
		$ary[] = $this->milk_recycle_e;
		$ary[] = $this->pet_recycle_e;
		$ary[] = $this->alminum_recycle_e;

		return join(',', $ary);
	}
}

// 資源ごみエコ診断計算クラス
class cal_shigengomi {
	var $milk_num;				// 牛乳パック本数
	var $milk_recycle;		// 牛乳パックリサイクル
	var $pet_num;					// ペットボトル本数
	var $pet_recycle;			// ペットボトルリサイクル
	var $alminum_num;			// アルミ缶飲料本数
	var $alminum_recycle;	// アルミ缶飲料リサイクル

	// カロリー、料金、CO2計算
	function calculation(&$calorie, &$cost, &$co2) {
		$dust = 0;
		// 牛乳パック
		if (!$this->milk_recycle)
			$dust += $this->milk_num * 0.033 * 52;

		// ペットボトル
		if (!$this->pet_recycle)
			$dust += $this->pet_num * 0.020 * 52;

		// ペットボトル
		if (!$this->alminum_recycle)
			$dust += $this->alminum_num * 0.046 * 52;

		$calorie = 0;
		$cost = 0;
		$co2 = cnv_dst_co2($dust);
	}
}
?>