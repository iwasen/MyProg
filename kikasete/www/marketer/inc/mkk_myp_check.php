<?
// Myパートナー所有チェック
if ($pjt_id != '') {
	$sql = sprintf('SELECT COUNT(*) FROM t_mp_pjt WHERE ppj_pjt_id=%s AND ppj_marketer_id=%s', sql_number($pjt_id), sql_number($_SESSION['ss_owner_id']));
	if (db_fetch1($sql, 0) == 0)
		redirect('mp_pjt_list.php');
}
?>