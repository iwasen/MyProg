<?
/******************************************************
' System :Eco-footprint 共通
' Content:年間CO2排出量によるクラス分け
'******************************************************/

define('AVE_RANGE', 0.25);		// 平均(クラス２）の範囲

function get_co2_class($seq_no, $date) {
	// クラスのデフォルト
	$class = 2;

	// 年間CO2取得
	$date2 = add_date($date, 0, -11, 0);
	$sql = "SELECT sum(cr_co2) AS sum_co2,count(cr_co2) AS count_co2 FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym BETWEEN '$date' AND '$date2'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sum_co2 = $fetch->sum_co2;
		if ($fetch->count_co2 == 12) {
			// 地域別CO2排出量平均値取得
			$sql = "SELECT ac_ave_co2 FROM m_ave_co2"
					. " JOIN m_area ON ac_area2_cd=ar_area2_cd"
					. " JOIN t_member ON ar_area_cd=mb_area_cd"
					. " WHERE mb_seq_no=$seq_no";
			$ave_co2 = db_fetch1($sql);

			// クラスを判定
			if ($ave_co2) {
				if ($sum_co2 < $ave_co2 * (1 - AVE_RANGE))
					$class = 1;
				elseif ($sum_co2 > $ave_co2 * (1 + AVE_RANGE))
					$class = 3;
			}
		}
	}

	return $class;
}
?>