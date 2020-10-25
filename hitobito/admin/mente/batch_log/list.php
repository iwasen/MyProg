<?php
/**
 * ひとびと･net 事務局ページ
 *
 * バッチログ一覧ページ
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
include("$inc/database.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");

// ページヘッダ情報設定
$header = new header_class('バッチログ管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('処理日時', 'center', 'lbt_date');
$list->set_header('バッチ種別', 'left', 'lbt_file_name');
$list->set_header('処理内容', 'left', 'lbt_description');
$list->set_header('処理時間', 'right', 'lbt_processing_time');
$list->set_header('状態', 'center', 'lbt_status');

// リストデータ設定
$sql = "SELECT lbt_status,lbt_date,lbt_file_name,lbt_description,lbt_processing_time"
		. " FROM l_batch"
		. $list->get_order_by(0, 1)
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data(format_datetime($fetch->lbt_date));
	$list->set_data($fetch->lbt_file_name);
	$list->set_data($fetch->lbt_description);
	$list->set_data(round($fetch->lbt_processing_time, 4));

	switch ($fetch->lbt_status) {
	case 0:
		$list->set_data('正常');
		break;
	default:
		$list->set_html('<font color="red">エラー</font>');
		break;
	}
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
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/top.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
