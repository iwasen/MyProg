<?
/******************************************************
' System :�������āEnet�����ǁE�G�[�W�F���g�p�y�[�W
' Content:My�p�[�g�i�[�E�Y���ҁi0,1�`���jCSV�o��
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/myp_csv.php");
$inc = "$top/inc";
include("$inc/login_check.php");

csv_sel2($pjt_id, $all, true);
exit;
?>