<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンターリセット
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
include("$inc/data.php");
include("data.php");

// パラメータ取得
$recruit_id = $_GET['recruit_id'];

// データ処理クラス生成
$data = new recruit_data_class();

$data->reset_click_counter($recruit_id);

redirect("edit.php?recruit_id=$recruit_id");
?>