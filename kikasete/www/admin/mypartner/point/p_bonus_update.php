<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・ボーナス設定
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");

sscanf($ym, '%4d%2d', $y, $m);

$rec['pmp_bonus'] = sql_number($bonus);

$sql = "SELECT COUNT(*) FROM t_mp_monitor_point WHERE pmp_pjt_id=$pjt_id AND pmp_year_month='$ym' AND pmp_monitor_id=$monitor_id";
if (db_fetch1($sql) == 0) {
	$rec['pmp_pjt_id'] = sql_number($pjt_id);
	$rec['pmp_year_month'] = sql_char($ym);
	$rec['pmp_monitor_id'] = sql_number($monitor_id);
	$rec['pmp_point'] = sql_number(0);
	db_insert('t_mp_monitor_point', $rec);
} else
	db_update('t_mp_monitor_point', $rec, "pmp_pjt_id=$pjt_id AND pmp_year_month='$ym' AND pmp_monitor_id=$monitor_id");
?>
<html lang="ja">
<head>
<script type="text/javascript">
<!--
opener.location.reload();
window.close();
//-->
</script>
</head>
</html>
