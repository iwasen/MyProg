<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 職業マスタ一覧ページ
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
$header = new header_class('職業マスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class();

// リストヘッダ設定
$list->set_header('表示順序', 'center', 'skg_order');
$list->set_header('職業名', 'left', 'skg_shokugyo_name');

// リストデータ設定
$sql = "SELECT skg_shokugyo_id, skg_shokugyo_name, skg_order"
		. " FROM m_shokugyo"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->skg_order, "edit.php?shokugyo_id=$fetch->skg_shokugyo_id", '職業マスタ情報を表示・変更します');
	$list->set_data($fetch->skg_shokugyo_name);
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
		<td class="lt">■職業マスタ一覧</td>
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
