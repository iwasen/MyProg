<?php
include ( "inc/com_db_func2.inc" );
include ( "jmk/inc/code.php" );

// パラメータエラー
function param_error() {
	header("HTTP/1.0 404 Not Found");
	exit;
}
/****************************************************************
 Main
****************************************************************/
$ct_id = decrypt_number($QUERY_STRING);
if ($ct_id) {
	$sql = "select cc_ct_id,cc_jump_url from t_click_counter where cc_ct_id=".sql_number($ct_id);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$cc_ct_id = $fetch->cc_ct_id;
		$cc_jump_url = $fetch->cc_jump_url;

		db_begin_trans();
		$sql = "insert into l_click_counter (lc_ct_id,lc_ip_addr) values ($cc_ct_id,'$REMOTE_ADDR')";
		db_exec($sql);
		$sql = "update t_click_counter set cc_click_num=cc_click_num+1 where cc_ct_id=$cc_ct_id";
		db_exec($sql);
		db_commit_trans();
		header("location: $cc_jump_url");
		exit;
	} else 
		param_error();
} else
	param_error();

?>