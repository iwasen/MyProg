<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報クラス
'******************************************************/
class energy {
	var $el_input_use;
	var $el_input_sum;
	var $gs_input_use;
	var $gs_input_sum;
	var $ol_input_use;
	var $ol_input_sum;

	var $el_use;
	var $el_use_flag;
	var $el_sum;
	var $el_sum_flag;
	var $gs_use;
	var $gs_use_flag;
	var $gs_sum;
	var $gs_sum_flag;
	var $ol_use;
	var $ol_use_flag;
	var $ol_sum;
	var $ol_sum_flag;

	var $el_use_av;
	var $gs_use_av;
	var $ol_use_av;
}

class mm_regist3 {
	var $active;
	var $sys_ym;
	var $start_ym;
	var $start_y;
	var $start_m;
	var $mb_name1;

	var $el_cd;
	var $gs_cd;
	var $ol_cd;
	var $wt_cd;
	var $gl_cd;
	var $gm_cd;
	var $gs_kind;
	var $gs_type;
	var $family_num;
	var $boiler_cd;
	var $car_flag;
	var $ol_flag;
	var $area_cd;

	var $el_price_unit;
	var $gs_price_unit;
	var $ol_price_unit;
	var $wt_price_unit;
	var $gl_price_unit;

	var $wt_input_use;
	var $wt_input_sum;
	var $gl_input_use;
	var $gl_input_sum;
	var $gm_input_use;

	var $wt_use;
	var $wt_use_flag;
	var $wt_sum;
	var $wt_sum_flag;
	var $gl_use;
	var $gl_use_flag;
	var $gl_sum;
	var $gl_sum_flag;
	var $gm_use;
	var $gm_use_flag;

	var $wt_use_av;
	var $wt_sum_av;
	var $gl_use_av;
	var $gl_sum_av;
	var $gm_use_av;

	// コンストラクタ
	function mm_regist3() {
		$this->active = true;
	}

	// 開始年月取得(13ヶ月前)
	function set_start_ym() {
		$fetch = get_system_info('sy_batch_month');
		$this->start_ym = add_date($fetch->sy_batch_month, -1, -1, 0);
		$this->start_y = get_datepart('Y', $this->start_ym);
		$this->start_m = get_datepart('M', $this->start_ym);
	}

	// エネルギー単価取得
	function set_price_unit() {
		$this->el_price_unit = get_price_unit($this->el_cd);
		$this->gs_price_unit = cnv_gas_mny(1);
		$this->ol_price_unit = get_price_unit($this->ol_cd);
		$this->wt_price_unit = get_price_unit($this->wt_cd);
		$this->gl_price_unit = get_price_unit($this->gl_cd);
	}

	// 実績データ保存
	function save_db($seq_no) {

		$year = $this->start_y;
		$month = $this->start_m;


		function get_m($i, $year, $month) {
			return (int)(date("m", mktime (0,0,0,$month + $i,1,$year)));
		}
		function get_y($i, $year, $month) {
			return (int)(date("Y", mktime (0,0,0,$month + $i,1,$year)));
		}

		$sql = "select bd_seq_no from t_base_data where bd_mb_seq_no=" . sql_number($seq_no);
		$result = db_exec($sql);
		if (!pg_numrows($result)) {

			db_begin_trans();

			for ($i = 1; $i <= 12; $i++) {
				$energy = &$this->energy[get_m($i,$year,$month)];

				$sql = sprintf("insert into t_base_data(bd_mb_seq_no,bd_month,bd_el_use,bd_el_use_flag,bd_el_sum,bd_el_sum_flag,bd_gs_use,bd_gs_use_flag,bd_gs_sum,bd_gs_sum_flag,bd_ol_use,bd_ol_use_flag,bd_ol_sum,bd_ol_sum_flag,bd_wt_use,bd_wt_use_flag,bd_wt_sum,bd_wt_sum_flag,bd_gl_use,bd_gl_use_flag,bd_gl_sum,bd_gl_sum_flag,bd_gm_use,bd_gm_use_flag,bd_wt_use_input,bd_wt_sum_input,bd_commit_flag) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,1)",
					sql_number($seq_no),
					sql_date(get_y($i,$year,$month)."/".get_m($i,$year,$month)."/01"),
					sql_number($energy->el_use),
					sql_number($energy->el_use_flag),
					sql_number($energy->el_sum),
					sql_number($energy->el_sum_flag),
					sql_number($energy->gs_use),
					sql_number($energy->gs_use_flag),
					sql_number($energy->gs_sum),
					sql_number($energy->gs_sum_flag),
					sql_number($energy->ol_use),
					sql_number($energy->ol_use_flag),
					sql_number($energy->ol_sum),
					sql_number($energy->ol_sum_flag),
					sql_number($this->wt_use),
					sql_number($this->wt_use_flag),
					sql_number($this->wt_sum),
					sql_number($this->wt_sum_flag),
					sql_number($this->gl_use),
					sql_number($this->gl_use_flag),
					sql_number($this->gl_sum),
					sql_number($this->gl_sum_flag),
					sql_number($this->gm_use),
					sql_number($this->gm_use_flag),
					sql_number($this->wt_use),
					sql_number($this->wt_sum));
					db_exec($sql);
			}
			$fetch = get_system_info('sy_batch_month');
			$sql = "update t_member set mb_step=3, mb_regist_month=" . sql_date($fetch->sy_batch_month) . ",mb_regist_date='now'"
				. " where mb_step<>9 and mb_seq_no=".sql_number($seq_no);
			db_exec($sql);

			db_commit_trans();
		}
	}

	// 家族人数コード取得
 	function set_family_num() {
		$seq_no = $_SESSION['ss_seq_no'];
		$sql = "select count(*) as fm_count from t_family where fm_mb_seq_no=" .sql_number($seq_no);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
		}

		if ($fetch->fm_count + 1 > 6)
			$family_num = 6;
		else
			$family_num = $fetch->fm_count + 1;

		$this->family_num = $family_num;
	}

	// エネルギーコードセット
	function set_energy_cd() {
		$seq_no = $_SESSION['ss_seq_no'];
		$sql = "select ar_area2_cd,mb_name1,mb_boiler_cd,mb_gas_kind_cd,mb_gas_type_cd,mb_gcar_num,mb_dcar_num,mb_bike_num,mb_ol_flag"
			. " from t_member left join m_area on mb_area_cd=ar_area_cd"
			. " where mb_step <> 9 and mb_seq_no=" . sql_number($seq_no);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->mb_name1 = $fetch->mb_name1;
			$this->area_cd = $fetch->ar_area2_cd;
			$this->gs_kind = $fetch->mb_gas_kind_cd;
			$this->boiler_cd = $fetch->mb_boiler_cd;
			$this->ol_flag = $fetch->mb_ol_flag;
			$this->car_flag = 1;
			$this->gs_type = $fetch->mb_gas_type_cd;


			$this->el_cd = 1;	// 電気（一般電灯、給湯なし）
			$this->gs_cd = 3;	// 都市ガス
			$this->ol_cd = 7;	// 灯油使用あり
			$this->wt_cd = 5;	// 水道
			$this->gm_cd = 8;	// ゴミ
			$this->gl_cd = 9;	// ガソリン（車所有）
			$this->gs_type = 0;	// ガスタイプ（なし）

			if ($fetch->mb_boiler_cd == 1 || $fetch->mb_boiler_cd == 2 || $fetch->mb_boiler_cd == 6)
				$this->el_cd = 2;

			if ($fetch->mb_gas_kind_cd == 2)
				$this->gs_cd = 4;
			if ($fetch->mb_gas_kind_cd == 3)
				$this->gs_cd = 0;

			if ($fetch->mb_boiler_cd == 5)
				$this->ol_cd = 6;
			if ($fetch->mb_ol_flag == 0)
				$this->ol_cd = 0;

			if ($fetch->mb_gcar_num <= 0 && $fetch->mb_dcar_num <= 0 && $fetch->mb_bike_num <= 0) {
				$this->gl_cd = 0;
				$this->car_flag = 0;
			}

			if ($fetch->mb_gas_type_cd) {
				if ($fetch->mb_gas_type_cd == 99)
					$this->gs_type = 13;
				else
					$this->gs_type = $fetch->mb_gas_type_cd;
			}
		}
	}

	// 平均値セット
	function set_all_average() {
		$sql = "select av_energy_cd,av_month,av_volume from m_average where av_family_cd=" . sql_number($this->family_num)
			 . " and av_energy_cd in ($this->el_cd,$this->gs_cd,$this->ol_cd,$this->wt_cd,$this->gl_cd,$this->gm_cd) order by av_energy_cd,av_month";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$av_code = $fetch->av_energy_cd;
			$av_month = $fetch->av_month;

			$energy = &$this->energy[$av_month];
			if ($av_code == $this->el_cd) {
				$energy->el_use_av = $fetch->av_volume;
			}
			if ($av_code == $this->gs_cd) {
				if ($this->gs_cd == 4)
					$energy->gs_use_av = (int)($fetch->av_volume);
				else
					$energy->gs_use_av = (int)(gas_mcal_m3($fetch->av_volume));
			}
			if ($av_code == $this->wt_cd) {
				$this->wt_use_av = $fetch->av_volume;
			}
			if ($av_code == $this->ol_cd) {
				$energy->ol_use_av = $fetch->av_volume;
			}
			if ($av_code == $this->gm_cd) {
				$this->gm_use_av = $fetch->av_volume;
			}
			if ($av_code == $this->gl_cd) {
				$this->gl_use_av = $fetch->av_volume;
			}
		}
	}
}
?>