<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�迴����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

if ($ganbalist_id) {
	write_ganbarist($ganbalist_id, '', $energy, $water, $cost, $co2, $eco_data, $advice);
}

redirect('life_style.php#sim_top');
?>