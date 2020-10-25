#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:予約開始処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// パラメータが不正の場合は閉じる
if (!is_numeric($shop_id) || !is_numeric($course_id))
	redirect('timeout.php');

// 店舗名とコース名を取得
$shop_name = decode_shop($shop_id);
$course_name = decode_course($course_id);

// 名称が無ければ閉じる
if ($shop_name == '' || $course_name == '')
	redirect('timeout.php');

// セッションに予約情報を保存
session_register('reserve');
session_register('counter');
session_register('start');

if ($start == 0)
	$start = time();

// 予約情報クラスに店舗とコースを保存
$reserve = new reserve_class;
$reserve->shop_id = $shop_id;
$reserve->course_id = $course_id;
$reserve->shop = $shop_name;
$reserve->course = $course_name;

// 初期表示年月をセット
$date = getdate(time() + DAY_SEC * 2);
$reserve->year = $date['year'];
$reserve->month = $date['mon'];

// 予約時間選択画面へ遷移
redirect('sche_form.php');
?>