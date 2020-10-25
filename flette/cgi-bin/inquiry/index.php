#!/usr/local/bin/php4
<?
/******************************************************
' System :flette問い合わせページ
' Content:問い合わせ開始処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/inquiry.php");

// セッションに予約情報を保存
session_register('inquiry');

// 問い合わせ情報クラスを生成
$inquiry = new inquiry_class;

// 予約時間選択画面へ遷移
redirect('info_form.php');
?>