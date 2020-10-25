<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

/*
print "openDateOfgarden=$openDateOfgarden<br>";
print "lapsedDaysOfgarden=$lapsedDaysOfgarden<br>";
print "waterFlowerDays=$waterFlowerDays<br>";
print "point_totalco2=$point_totalco2<br>";
*/

if ($openDateOfgarden == '-9999') {		// ‰‰ñŽž
	$first_date = $lapsedDaysOfgarden != '' ? date("Y-m-d") : '';
	$play_date = $lapsedDaysOfgarden != '' ? date("Y-m-d") : '';
	$wt_date = $waterFlowerDays != '' ? date("Y-m-d") : '';
	$sql = "insert into t_gardening (gd_mb_seq_no,gd_first_date,gd_play_date,gd_wt_date,gd_wt_count) values ("
			. sql_number($_SESSION['ss_seq_no']) . ","
			. sql_date($first_date) . ","
			. sql_date($play_date) . ","
			. sql_date($wt_date) . ",1)";
	db_exec($sql);
} elseif ($openDateOfgarden >= 0) {		// “ñ‰ñ–ÚˆÈ~
	$sql = "select ('now'::date - gd_wt_date::date) as gd_wt_date from t_gardening where gd_mb_seq_no={$_SESSION['ss_seq_no']}";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ($lapsedDaysOfgarden == $waterFlowerDays) {		// …‚ ‚°‚ ‚è
			if ($fetch->gd_wt_date > 0) {		// …‚ ‚°‚©‚ç‚ÌŒo‰ß“ú””äŠr
				$sql = "update t_gardening set"
						. " gd_play_date=" . sql_date(date("Y-m-d")) . ","
						. " gd_wt_date=" . sql_date(date("Y-m-d")) .","
						. " gd_wt_count=gd_wt_count + 1"
						. " where gd_mb_seq_no={$_SESSION['ss_seq_no']} ";
				db_exec($sql);
			}
		} else {		// …‚ ‚°‚È‚µ
			$sql = "update t_gardening set gd_play_date=" . sql_date(date("Y-m-d")) . " where gd_mb_seq_no={$_SESSION['ss_seq_no']} ";
			db_exec($sql);
		}
	}
}

redirect('main.php');

?>