<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・ポイント付与カレンダー修正
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");

sscanf($ym, '%4d%2d', $y, $m);

$ary = explode('/', $calc_md);
$rec['ppl_calc_date'] = sql_date("$calc_y/{$ary[0]}/{$ary[1]}");

$ary = explode('/', $give_md);
$rec['ppl_give_date'] = sql_date("$give_y/{$ary[0]}/{$ary[1]}");

$sql = "SELECT COUNT(*) FROM t_mp_point_log WHERE ppl_pjt_id=$pjt_id AND ppl_year_month='$ym'";
if (db_fetch1($sql) == 0) {
	$rec['ppl_pjt_id'] = sql_number($pjt_id);
	$rec['ppl_year_month'] = sql_char($ym);
	db_insert('t_mp_point_log', $rec);
} else
	db_update('t_mp_point_log', $rec, "ppl_pjt_id=$pjt_id AND ppl_year_month='$ym'");
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
