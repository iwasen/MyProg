<?
/******************************************************
' System :Eco-footprint ²ñ°÷¥Ú¡¼¥¸
' Content:ÍáÁåÆþÍá¥¨¥³¿ÇÃÇ½èÍý¥¯¥é¥¹
'******************************************************/

// ÍáÁåÆþÍá¥¨¥³¿ÇÃÇ¥¯¥é¥¹
class sim_yokusou {
	// ¸½¾õ¥Ç¡¼¥¿
	var $nyuuyoku_summer;		// ÍáÁåÆþÍáÍ­Ìµ¡Ê²Æ¡Ë
	var $nyuuyoku_sp_aut; 	// ÍáÁåÆþÍáÍ­Ìµ¡Ê½ÕŽ¥½©¡Ë
	var $nyuuyoku_winter;		// ÍáÁåÆþÍáÍ­Ìµ¡ÊÅß¡Ë
	var $yuon_summer;				// Åò²¹¡Ê²Æ¡Ë[¡î]
	var $yuon_sp_aut;				// Åò²¹¡Ê½ÕŽ¥½©¡Ë[¡î]
	var $yuon_winter;				// Åò²¹¡ÊÅß¡Ë[¡î]
	var $yuhari_ryou;				// Åò¤Ï¤êÎÌ[L]
	var $boiler_type;				// µëÅò´ï¥¿¥¤¥×

	// ¿ÇÃÇ¥Ç¡¼¥¿
	var $yuhari_ryou_e;			// Åò¤Ï¤êÊÑ²½ÎÌ[L]
	var $yuhari_flag_e;			// Åò¤Ï¤ê¾å²¼¥Õ¥é¥°
	var $yuon_e;						// Åò²¹

	// ¥³¥ó¥¹¥È¥é¥¯¥¿
	function sim_yokusou() {
		// ¥¯¥é¥¹ÊÑ¿ô¤ò½é´ü²½
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
		// ·×»»¥¯¥é¥¹À¸À®
		$cal = new cal_yokusou;

		// ¸½¾õ¥Ç¡¼¥¿¤Ç·×»»
		$cal->nyuuyoku_summer = $this->nyuuyoku_summer;
		$cal->nyuuyoku_sp_aut = $this->nyuuyoku_sp_aut;
		$cal->nyuuyoku_winter = $this->nyuuyoku_winter;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->yuhari_ryou = $this->yuhari_ryou;
		$cal->boiler_type = $this->boiler_type;
		$cal->calculation($calorie_c, $cost_c, $co2_c, $water_c);

		// ¿ÇÃÇ¾ò·ï¤Ç·×»»
		$cal->yuhari_ryou += ($this->yuhari_flag_e == 1) ? $this->yuhari_ryou_e : -$this->yuhari_ryou_e;
		$cal->yuon_summer -= $this->yuon_e;
		$cal->yuon_sp_aut -= $this->yuon_e;
		$cal->yuon_winter -= $this->yuon_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e, $water_e);

		// º¹Ê¬¤ò·×»»
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;
		$water = $water_c - $water_e;

		// ¿ÇÃÇ¥á¥Ã¥»¡¼¥¸½ÐÎÏ
		if ($this->yuhari_ryou_e)
			$msg_ary[] = "ÍáÁå¤ÎÅò¤Ï¤êÎÌ¤ò $this->yuhari_ryou_e L(¥ê¥Ã¥È¥ë)" . ($this->yuhari_flag_e == 1 ? 'Â¿¤¯' : '¾¯¤Ê¤¯') . '¤·';
		if ($this->yuon_e)
			$msg_ary[] = "ÍáÁå¤ÎÅò²¹¤ò $this->yuon_e ¡î²¼¤²";
		if ($msg_ary)
			$msg = join('¡¢<br>', $msg_ary) . '¤Þ¤¹¡£';
	}

	// DB¤«¤é¿ÇÃÇ¥Ç¡¼¥¿¤òÆÉ¤ß¹þ¤ß
	function read_eco_data() {
		// ¥¨¥³¿ÇÃÇ¥Ç¡¼¥¿¤ò¼èÆÀ
		$eco_data = read_sim_data(GANBA_YOKUSOU);
		if ($eco_data) {
			// ¥¯¥é¥¹ÊÑ¿ô¤Ë¥»¥Ã¥È
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

	// ¥¨¥³¿ÇÃÇ¥Ç¡¼¥¿¤ò¥«¥ó¥Þ¶èÀÚ¤ê¤Ç¼èÆÀ
	function get_eco_data() {
		// ¥¨¥³¿ÇÃÇ¥Ç¡¼¥¿¤ò¥«¥ó¥Þ¶èÀÚ¤ê¤ËÊÔ½¸
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

// ÍáÁåÆþÍá·×»»¥¯¥é¥¹
class cal_yokusou {
	var $nyuuyoku_summer;		// ÍáÁåÆþÍáÍ­Ìµ¡Ê²Æ¡Ë
	var $nyuuyoku_sp_aut;		// ÍáÁåÆþÍáÍ­Ìµ¡Ê½ÕŽ¥½©¡Ë
	var $nyuuyoku_winter;		// ÍáÁåÆþÍáÍ­Ìµ¡ÊÅß¡Ë
	var $yuon_summer;				// Åò²¹¡Ê²Æ¡Ë[¡î]
	var $yuon_sp_aut;				// Åò²¹¡Ê½ÕŽ¥½©¡Ë[¡î]
	var $yuon_winter;				// Åò²¹¡ÊÅß¡Ë[¡î]
	var $yuhari_ryou;				// Åò¤Ï¤êÎÌ[L]
	var $boiler_type;				// µëÅò´ï¥¿¥¤¥×

	// ¥«¥í¥ê¡¼¡¢ÎÁ¶â¡¢CO2¡¢¿åÎÌ·×»»
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// µ¡´ï¸úÎ¨¼èÆÀ
		$efficiency = $this->get_efficiency($this->boiler_type);

		// Åß
		if ($this->nyuuyoku_winter) {
			$calorie_winter = $this->yuhari_ryou * DAYS_WINTER * ($this->yuon_winter - TMP_WINTER) / $efficiency;
			$water_winter = $this->yuhari_ryou * DAYS_WINTER;
		}

		// ²Æ
		if ($this->nyuuyoku_summer) {
			$calorie_summer = $this->yuhari_ryou * DAYS_SUMMER * ($this->yuon_summer - TMP_SUMMER) / $efficiency;
			$water_summer = $this->yuhari_ryou * DAYS_SUMMER;
		}

		// ½Õ½©
		if ($this->nyuuyoku_sp_aut) {
			$calorie_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT * ($this->yuon_sp_aut - TMP_SP_AUT) / $efficiency;
			$water_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT;
		}

		// ¹ç·×¡Êkcal->Mcal, L->£í3)
		$calorie = ($calorie_winter + $calorie_summer + $calorie_sp_aut) / 1000;
		$water = ($water_winter + $water_summer + $water_sp_aut) / 1000;

		// ÎÁ¶â¡¢CO2·×»»
		switch ($this->boiler_type) {
		case 1:		// ¥¬¥¹¥¿¥¤¥×
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		case 2:		// ÅÅµ¤¥¿¥¤¥×
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 3:		// ÅôÌý¥¿¥¤¥×
			$l = oil_mcal_l($calorie);
			$cost = cnv_oil_mny($l, $this->boiler_type);
			$co2 = cnv_oil_co2($l, $this->boiler_type);
			break;
		}
		$cost += cnv_wtr_mny($water);
		$co2 += cnv_wtr_co2($water);
	}

	// µ¡´ï¸úÎ¨¼èÆÀ
	function get_efficiency($boiler_type) {
		switch ($boiler_type) {
		case 1:		// ¥¬¥¹¥¿¥¤¥×
			return EFF_GAS;
		case 2:		// ÅÅµ¤¥¿¥¤¥×
			return EFF_ELE;
		case 3:		// ÅôÌý¥¿¥¤¥×
			return EFF_OIL;
		}
	}
}
?>