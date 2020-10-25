<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 問合せ管理ページ
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

$inquiry_id = $_GET['inquiry_id'];

db_begin_trans();

// 問合せ情報削除
$sql = sprintf("DELETE FROM t_navi_inquiry WHERE nvi_inquiry_id=%s", sql_number($inquiry_id));
db_exec($sql);

// 問合せ回答情報削除
$sql = sprintf("DELETE FROM t_navi_answer WHERE nva_inquiry_id=%s", sql_number($inquiry_id));
db_exec($sql);

db_commit_trans();

redirect('list.php');
?>