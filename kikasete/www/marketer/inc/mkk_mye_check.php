<?
// Myアンケートチェック
if ($enquete_id == '')
	redirect('mkk_enq.php');

$sql = "SELECT * FROM t_my_enquete WHERE me_marketer_id={$_SESSION['ss_owner_id']} AND me_enquete_id=$enquete_id";
if (pg_numrows(db_exec($sql)) == 0) {
	$sql = "SELECT *"
			. " FROM m_tantousha"
			. " JOIN t_project ON pj_tantousha_id=ts_tantousha_id"
			. " JOIN t_job ON jb_project_id=pj_project_id"
			. " JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE en_enquete_id=$enquete_id AND ts_marketer_id={$_SESSION['ss_owner_id']}";
	if (pg_numrows(db_exec($sql)) == 0)
		redirect('mkk_enq.php');
}
?>