<?php 
function err_info() {
	$print_msg = "　バックナンバーを公開していません。";
	$p_titl = "バックナンバー表示";
	include ("err-msg.php");
	exit();
}

// 外部からのリンク非表示チェック
function bucknb_check($back_rid) {
	$sql = "select LB.mag_id,MM.mag_bn_pub from l_backnumber LB left join m_magazine MM on LB.mag_id=MM.mag_id"
			. " where LB.index_id=$back_rid and MM.mag_bn_pub != '1'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_id = $fetch->mag_id;
		$mag_bn_pub = $fetch->mag_bn_pub;
		if ($mag_bn_pub == '3') {
			$sql = "select max(index_id) as max_id from l_backnumber where mag_id='$mag_id'";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$max_id = $fetch->max_id;

			if ($max_id != $back_rid)
					err_info();
			}
		} else
			err_info();
	}
}
?>
