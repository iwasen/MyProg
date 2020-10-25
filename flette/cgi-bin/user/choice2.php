#!/usr/local/bin/php4
<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('/index.html');

$reserve->year = $year;
$reserve->month = $month;

for ($i = 0; $i < MAX_CHOICE; $i++) {
	if ($choice[$i] != '') {
		$ary = explode(',', $choice[$i]);
		$reserve->choice[$i]->room_id = $ary[0];
		$reserve->choice[$i]->room = decode_room($ary[0]);
		$reserve->choice[$i]->date = $ary[1];
		$reserve->choice[$i]->start_time = $ary[2];
		$reserve->choice[$i]->end_time = $ary[3];
	} else
		$reserve->choice[$i]->room_id = 0;
}

redirect($next);
?>