<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ログインエラーページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
include("$inc/header.php");
include("$inc/common.php");

// ページヘッダ情報設定
$header = new header_class('ひとびと・net事務局ログイン', BACK_NONE);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body onLoad="document.all.ok.focus()">

<?php $header->page_header(); ?>

<div align="center">
<p class="msg">ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="history.back()"></p>
</div>

<?php $header->page_footer(); ?>

</body>
</html>
