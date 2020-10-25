<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ登録情報更新ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");

// POST変数取得
$member_id = $_POST['member_id'];
$del_flag = $_POST["delete_flag"];


if (is_array($del_flag)) {
	db_begin_trans();
	foreach($del_flag as $meimaga_id => $check) {

		if ($check) {
			$sql = sprintf("DELETE FROM t_melmaga_member WHERE mmm_member_id=%s AND mmm_melmaga_id=%s"
				, sql_number($member_id)
				, sql_number($meimaga_id));
			db_exec($sql);
		}
	}
	db_commit_trans();
}

redirect('personal.php?member_id=' . $member_id);
?>
