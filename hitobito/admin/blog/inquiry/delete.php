<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ��礻�����ڡ���
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

// ��礻������
$sql = sprintf("DELETE FROM t_navi_inquiry WHERE nvi_inquiry_id=%s", sql_number($inquiry_id));
db_exec($sql);

// ��礻����������
$sql = sprintf("DELETE FROM t_navi_answer WHERE nva_inquiry_id=%s", sql_number($inquiry_id));
db_exec($sql);

db_commit_trans();

redirect('list.php');
?>