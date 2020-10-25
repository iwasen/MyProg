<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンター時間別集計ページ
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
include("$inc/format.php");
include("$inc/list.php");
include("$inc/date.php");
include("$inc/csv.php");

// 入力パラメータ
$recruit_id = $_REQUEST['recruit_id'];

// ページヘッダ情報設定
$header = new header_class('クリックカウンター時間別集計', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('時間', 'center');
$list->set_header('クリック数', 'right');

// リストデータ設定
$sql = "SELECT EXTRACT(hour from rcl_date) AS rcl_hour, COUNT(*) AS rcl_count"
		. " FROM l_recruit_click"
		. " WHERE rcl_recruit_id=$recruit_id"
		. " GROUP BY rcl_hour"
		. " ORDER BY rcl_hour";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$ary[$fetch->rcl_hour] = $fetch->rcl_count;
}

if ($nrow > 0) {
	for ($hour = 0; $hour < 24; $hour++) {
		$list->set_data($hour);
		$list->set_data((int)$ary[$hour]);
	}
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("clickcounter_time_$recruit_id.csv");
	$csv->export_data($list);
	exit;
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
			<input type="submit" name="export" value="エクスポート">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>

<input type="hidden" name="recruit_id" <?=value($recruit_id)?>>

<?php $list->disp_list(); ?>

</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
