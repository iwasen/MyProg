<?php
/**
 * ひとびと･net 事務局ページ
 *
 * Pingマスタ一覧ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

// ページヘッダ情報設定
$header = new header_class('Pingマスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class();

// リストヘッダ設定
$list->set_header('送信先', 'left', 'png_name');
$list->set_header('送信先URL', 'left', 'png_url');
$list->set_header('ステータス', 'center', 'png_status');

// リストデータ設定
$sql = "SELECT png_ping_id, png_status, png_name, png_url"
		. " FROM m_ping"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->png_name, "edit.php?ping_id=$fetch->png_ping_id", 'Pingマスタ情報を表示・変更します');
	$list->set_data($fetch->png_url);
	$list->set_data(decode_ping_status($fetch->png_status));
}

// Pingステータス
function decode_ping_status($code, $def = '') {
	switch ($code) {
	case 1:
		return  '有効';
	case 9:
		return  '無効';
	}
	return $def;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■Pingマスタ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='edit.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
