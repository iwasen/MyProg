<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 画像表示ページ
 *
 *
 * @package
 * @author
 * @version
 */
$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/image.php");

disp_image($_REQUEST['image_id']);
?>