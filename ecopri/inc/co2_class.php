<?
/******************************************************
' System :Eco-footprint ����
' Content:ǯ��CO2�ӽ��̤ˤ�륯�饹ʬ��
'******************************************************/

define('AVE_RANGE', 0.25);		// ʿ��(���饹���ˤ��ϰ�

function get_co2_class($seq_no, $date) {
	// ���饹�Υǥե����
	$class = 2;

	// ǯ��CO2����
	$date2 = add_date($date, 0, -11, 0);
	$sql = "SELECT sum(cr_co2) AS sum_co2,count(cr_co2) AS count_co2 FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym BETWEEN '$date' AND '$date2'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sum_co2 = $fetch->sum_co2;
		if ($fetch->count_co2 == 12) {
			// �ϰ���CO2�ӽ���ʿ���ͼ���
			$sql = "SELECT ac_ave_co2 FROM m_ave_co2"
					. " JOIN m_area ON ac_area2_cd=ar_area2_cd"
					. " JOIN t_member ON ar_area_cd=mb_area_cd"
					. " WHERE mb_seq_no=$seq_no";
			$ave_co2 = db_fetch1($sql);

			// ���饹��Ƚ��
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