#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteユーザページ
' Content:予約開始処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");
include("$inc/decode.php");

// パラメータが不正の場合はトップページへ
if (!is_numeric($shop_id) || !is_numeric($course_id))
	redirect('/index.html');

// 店舗名とコース名を取得
$shop_name = decode_shop($shop_id);
$course_name = decode_course($course_id);

// 名称が無ければトップページへ
if ($shop_name == '' || $course_name == '')
	redirect('/index.html');

// セッションに予約情報を保存
session_register('reserve');

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
redirect('choice.php');
?>