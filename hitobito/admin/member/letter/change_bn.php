<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便りメール文更新ページ
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

$sql = "UPDATE t_hitobito_letter"
		. " SET hbl_backnumber=CASE hbl_backnumber WHEN 0 THEN 1 WHEN 1 THEN 0 ELSE hbl_backnumber END"
		. " WHERE hbl_letter_id={$_GET['letter_id']}";
db_exec($sql);

redirect('list.php');
?>