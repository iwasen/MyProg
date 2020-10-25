<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メールテンプレートマスタ一覧ページ
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
$header = new header_class('メールテンプレートマスタ一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('テンプレート名', 'left', 'mtm_template_id');
$list->set_header('件名', 'left', 'mtm_subject');
$list->set_header('差出人アドレス', 'left', 'mtm_from');

// リストデータ設定
$sql = "SELECT mtm_template_id, mtm_guide, mtm_subject, mtm_from"
		. " FROM m_mail_template"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mtm_guide, "edit.php?template_id=$fetch->mtm_template_id", 'メールテンプレートマスタ情報を表示・変更します');
	$list->set_data($fetch->mtm_subject);
	$list->set_data($fetch->mtm_from);
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
		<td class="lt">■メールテンプレートマスタ一覧</td>
		<td class="lb">
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
