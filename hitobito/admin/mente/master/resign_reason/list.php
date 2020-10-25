<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 退会理由マスタ一覧ページ
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
$header = new header_class('退会理由マスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class();

// リストヘッダ設定
$list->set_header('退会理由', 'left', 'rsg_reason_text');
$list->set_header('ステータス', 'center', 'rsg_status');

// リストデータ設定
$sql = "SELECT rsg_resign_id, rsg_status, rsg_reason_text"
		. " FROM m_resign_reason"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rsg_reason_text, "edit.php?resign_id=$fetch->rsg_resign_id", '退会理由マスタ情報を表示・変更します');
	$list->set_data(decode_resign_reason_status($fetch->rsg_status));
}

// 退会理由ステータス
function decode_resign_reason_status($code, $def = '') {
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
		<td class="lt">■退会理由マスタ一覧</td>
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
