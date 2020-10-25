#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:相談内容入力チェック処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/reserve.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');

// エラーリセット
$reserve->error = null;

// カウンセラーの性別取得
for ($i = 0; $i < count($sex); $i++)
	$reserve->choice[$i]->sex = $sex[$i];

// 希望順位の入れ替え
for ($i = 0; $i < count($choice); $i++)
	$temp[$choice[$i]] = $reserve->choice[$i];
for ($i = 0; $i < count($choice); $i++)
	$reserve->choice[$i] = $temp[$i];

// フォームからデータ取得
$reserve->counseling_id = $counseling_id ? $counseling_id : Array();
$reserve->change = $change;
$reserve->comment = $comment;

// 次ページへ
redirect('pro_form.php');
?>