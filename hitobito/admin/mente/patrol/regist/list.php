<?php
/**
 * ひとびと･net 事務局ページ
 *
 * パトロール隊マスタ一覧ページ
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
$header = new header_class('パトロール隊マスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ID', 'center', 'ptl_patrol_id');
$list->set_header('メールアドレス', 'left', 'ptl_mail_addr');
$list->set_header('名前', 'left', 'ptl_name1,ptl_name2');
$list->set_header('ステータス', 'center', 'ptl_status');

// リストデータ設定
$sql = "SELECT ptl_patrol_id, ptl_mail_addr, ptl_name1, ptl_name2, ptl_status"
		. " FROM m_patrol"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->ptl_patrol_id, "edit.php?patrol_id=$fetch->ptl_patrol_id", 'パトロール隊マスタ情報を表示・変更します');
	$list->set_data($fetch->ptl_mail_addr);
	$list->set_data("$fetch->ptl_name1 $fetch->ptl_name2");
	$list->set_data(decode_patrol_status($fetch->ptl_status));
}

// パトロール隊ステータス
function decode_patrol_status($code, $def = '') {
	switch ($code) {
	case '1':
		return  '有効';
	case '9':
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
		<td class="lt">■パトロール隊マスタ一覧</td>
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
