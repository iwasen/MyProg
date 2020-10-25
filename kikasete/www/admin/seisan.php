<?
$top = '.';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/point.php");

if ($_REQUEST['exec']) {
	db_begin_trans();

	$sql = "SELECT si_seq_no,si_member_id,si_point,si_item FROM t_seisan s1 WHERE si_seq_no IN (SELECT MAX(s2.si_seq_no) FROM t_seisan s2 WHERE s2.si_status=2 AND s2.si_member_id IN (SELECT lp_member_id FROM l_point WHERE lp_date='2010-10-13 22:18:44.321093+09') GROUP BY s2.si_member_id) AND s1.si_member_id NOT IN (280840,51699,83257,122194,272223,82702,304527,291171,284608,78940,122721,79658,74882,97470,118926,46938,294146,102864,33814,116225,99012,46223,100532,122561,84006,73951,77943,283680,230235,114507,120114,94816,245941,124103,56150,30747,274942,810,118185,41933,163848,211339,113416,278617,68324,211333,211332,211335,211338,236390,97944,275238,108916,71975,17749,301611,290991,281081,23752,294204,218055,292600,145913,304048,46699,216386,65254,75508,58447,111293,27526,107310,236436,131965,281506,31322,287365,33238,131926,283051,100517,131752,272236,298159,50626,14775,92289,303243,74131,60352,283172,78706,293461,122748,145368,116412,288819,75985,241924)";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$sql = "UPDATE t_seisan SET si_status=0 WHERE si_seq_no={$fetch->si_seq_no}";
		db_exec($sql);

		$point = $fetch->si_point + get_charge_point($fetch->si_item);
		monitor_point($fetch->si_member_id, PT_SEISAN, -$point);
	}
	db_commit_trans();
//	db_rollback();
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body>
<?
if ($_REQUEST['exec']) {
?>
½ªÎ»
<br><?=$nrow?>
<?
} else {
?>
<form>
<input name="exec" type="submit" value="¼Â¹Ô">
</form>
<?
}
?>
</body>
</html>
