<?
/******************************************************
' System :�������ơ�net����
' Content:�ɲåץ�ե������Ϣ
'******************************************************/

// �ɲåץ�ե�����������
function get_ma_profile() {
	$ma_profile_tbl = array();
    
	// �ץ�ե������ɲù��ܥơ��֥��ɤ߹���
	$sql = "SELECT pfi_profile_id,pfi_profile_name"
			. " FROM t_profile_item"
			. " WHERE pfi_hidden_flag=false"
			. " ORDER BY pfi_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ma_profile_tbl[$fetch->pfi_profile_id] = $fetch->pfi_profile_name;
		
	}

	return $ma_profile_tbl;
}
?>
