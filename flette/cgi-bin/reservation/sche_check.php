#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:予約日時チェック処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// エラーリセット
$reserve->error = null;

// 表示年月取得
if ($ym) {
	$ym_ary = explode('/', $ym);
	$reserve->year = $ym_ary[0];
	$reserve->month = $ym_ary[1];
}

// 予約希望取得
$reserve->choice_num = 0;
for ($i = 0; $i < MAX_CHOICE; $i++) {
	if ($choice[$i] != '') {
		$ary = explode(',', $choice[$i]);
		$reserve->choice[$i]->room_id = $ary[0];
		$reserve->choice[$i]->room = decode_room($ary[0]);
		$reserve->choice[$i]->date = $ary[1];
		$reserve->choice[$i]->start_time = $ary[2];
		$reserve->choice[$i]->end_time = $ary[3];
		$reserve->choice_num++;
		$reserve_select = true;
	} else
		$reserve->choice[$i]->room_id = 0;
}

// 予約希望入力チェック
if (!$reserve_select && $next == 'con_form.php') {
	$reserve->error['reserve_select'] = '＜予約希望日時をお選びください＞';
	redirect('sche_form.php');
}

// 次ページへ
redirect($next);
?>