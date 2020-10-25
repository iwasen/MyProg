<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ一覧ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");

// ページヘッダ情報設定
$header = new header_class('読者検索/代理解除', BACK_TOP);

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>
<br>
<div align="center">
	■該当するメールアドレスがありません。
	<br><br>
	<input type="button" value="　戻る　" onclick="history.back()">
</div>
<br>
<?php $header->page_footer(); ?>

</body>
</html>
