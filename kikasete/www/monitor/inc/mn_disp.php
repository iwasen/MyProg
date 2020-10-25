<?
/******************************************************
' System :きかせて・netモニターページ
' Content:各種表示処理
'******************************************************/

// モニター名表示
function disp_monitor_name() {
	$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}
?>