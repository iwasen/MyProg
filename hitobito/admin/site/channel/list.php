<?php
/**
 * ひとびと･net 事務局ページ
 *
 * サイト管理／チャネル一覧ページ
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

// ヘッダ情報設定
$header = new header_class('チャネル一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('チャネルID', 'center', 'chn_channel_id,sch_order', true);
$list->set_header('チャネル名', 'left', 'chn_channel_name,sch_order', true);
$list->set_header('サブチャネル名', 'left', 'sch_subchannel_id');
$list->set_header('表示順', 'center', 'chn_order,sch_order', true);
$list->set_header('ステータス', 'center', 'chn_status,sch_order', true);

$sql = "SELECT chn_channel_id, chn_status,chn_channel_name,chn_order,sch_subchannel_id,sch_status,sch_subchannel_name,sch_order"
		. " FROM m_channel"
		. " LEFT JOIN m_sub_channel ON sch_channel_id=chn_channel_id"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->chn_channel_id, "edit.php?channel_id=$fetch->chn_channel_id", 'チャネル情報を参照・修正します');
	$list->set_data($fetch->chn_channel_name);
	$list->set_data($fetch->sch_subchannel_name);
	$list->set_data($fetch->chn_order);
	$list->set_data(decode_channel_status($fetch->chn_status));
}

// チャネルステータス
function decode_channel_status($code, $def = '') {
	switch ($code) {
	case '1':
		return '有効';
	case '9':
		return '無効';
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

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='edit.php'">
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
