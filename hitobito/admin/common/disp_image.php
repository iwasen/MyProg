<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����ɽ���ڡ���
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