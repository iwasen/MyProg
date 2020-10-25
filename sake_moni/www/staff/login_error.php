<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログインエラー画面処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/header.php");

?>
<? staff_header('スタッフログイン') ?>

<div align="center">
<p class="msg">ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='index.php'"></p>
</div>

<? staff_footer() ?>
