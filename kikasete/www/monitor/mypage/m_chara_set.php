<?
$top = './..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mn_mypage.php");
include("$inc/mn_chara.php");

if ($chara_name != '') {
	$sql = "UPDATE t_monitor_point SET mp_chara_name='$chara_name' WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
	db_exec($sql);

	$_SESSION['ss_chara_name'] = $chara_name;
}

if ($chara_type != '') {
	$sql = "UPDATE t_monitor_point SET mp_chara_type=$chara_type WHERE mp_monitor_id={$_SESSION['ss_monitor_id']}";
	db_exec($sql);

	$_SESSION['ss_chara_image'] = get_chara_image($_SESSION['ss_monitor_id']);
}

redirect('m_index_main.php');
?>