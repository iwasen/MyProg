<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:����������å�
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

if (date(d) >= 1 && date(d) <= 10) {
	redirect('season.php');
	exit();
} else {
	redirect('main.php');
	exit();
}
?>