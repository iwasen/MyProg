<?php
include ("../inc/com_path.inc");

function com_get_date($type) {
	$dat = getdate ();
	$ret = $dat[$type];
	if ($type == "hours_a") {
		$ret = (int)date(H);
	}
	if ($type == "minutes_10") {
		$ret = (int)($dat['minutes'] / 10 + 1) * 10;
	}
	return $ret;
}
/* 01.07.31’Ç‰Á */
function com_get_specify_date($type, $timestamp) {
	$dat = getdate ($timestamp);
	$ret = $dat[$type];
	if ($type == "hours_a") {
		$ret = (int)date(H, $timestamp);
	}
	if ($type == "minutes_10") {
		$ret = (int)($dat['minutes'] / 10) * 10;
	}
//2001.08.29íœ	echo $dat['minutes'];
//2001.08.29íœ	echo "qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq $type=".$ret."\n";
	return $ret;
}

function com_chg_year($i_month, $i_day) {
	$now_yy = com_get_date("year");
	if (com_get_date("mon") == 12 && $i_month == 1) {
		$now_yy = $now_yy + 1;
	}
	return $now_yy;
}
?>

