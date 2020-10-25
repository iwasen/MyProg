<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

//$_ENV['HITOBITO_DB_HOST'] = '192.168.52.32';
db_begin_trans();

echo update_child_num(0);

db_commit_trans();
exit;

function update_child_num($remark_id) {
	$all_chind_num = 0;

	$sql = "SELECT rmk_remark_id,rmk_child_num,rmk_seq_no,rmk_open_flag FROM l_room_remark WHERE rmk_parent_remark_id=$remark_id AND rmk_status=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$child_num = update_child_num($fetch->rmk_remark_id);

		if ($fetch->rmk_child_num != $child_num) {
			$rec['rmk_child_num'] = sql_number($child_num);
			db_update('l_room_remark', $rec, "rmk_remark_id=$fetch->rmk_remark_id");
			echo "[$fetch->rmk_seq_no: $fetch->rmk_child_num => $child_num] ";
		}

		$all_chind_num += $child_num;

		if ($fetch->rmk_open_flag == 1)
			$all_chind_num++;
	}

	return $all_chind_num;
}
?>