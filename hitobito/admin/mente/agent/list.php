<?php
/**
 * ひとびと･net 事務局ページ
 *
 * エージェントマスタ一覧ページ
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

// ページヘッダ情報設定
$header = new header_class('エージェントマスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ID', 'center', 'adm_admin_id');
$list->set_header('メールアドレス', 'left', 'adm_mail_addr');
$list->set_header('名前', 'left', 'adm_name1,adm_name2');
$list->set_header('部署', 'center', 'adm_unit');
$list->set_header('ステータス', 'center', 'adm_status');

// リストデータ設定
$sql = "SELECT adm_admin_id, adm_mail_addr, adm_name1, adm_name2, adm_type, adm_unit, adm_status"
		. " FROM m_admin"
		. " WHERE adm_type=3"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->adm_admin_id, "edit.php?admin_id=$fetch->adm_admin_id", 'エージェントマスタ情報を表示・変更します');
	$list->set_data($fetch->adm_mail_addr);
	$list->set_data("$fetch->adm_name1 $fetch->adm_name2");
	$list->set_data($fetch->adm_unit);
	$list->set_data(decode_admin_status($fetch->adm_status));
}

// エージェントステータス
function decode_admin_status($code, $def = '') {
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
		<td class="lt">■エージェントマスタ一覧</td>
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
