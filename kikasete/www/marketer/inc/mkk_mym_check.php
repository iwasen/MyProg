<?
// Myモニターチェック
if ($room_id == '')
	redirect('mkk_mymonitor.php');

$sql = "SELECT * FROM t_my_monitor WHERE mm_room_id=$room_id AND mm_marketer_id={$_SESSION['ss_owner_id']}";
if (pg_numrows(db_exec($sql)) == 0)
	redirect('mkk_mymonitor.php');
?>