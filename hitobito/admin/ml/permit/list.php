<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 登録アドレス以外の投稿管理ページ
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
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// ページヘッダ情報設定
$header = new header_class('登録アドレス以外の投稿管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ワイワイ会議', 'left', 'rom_name', true);
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 8);

$list->set_header('タイトル', 'left', 'rmp_title');
$list->set_search_text('title', $_REQUEST['title'], 'alpha', 20);

$list->set_header('本文', 'left', 'rmp_content');
$list->set_search_text('content', $_REQUEST['content'], 'kanji', 20);

$list->set_header('from', 'left', 'rmp_mail_addr');
$list->set_search_text('mail_addr', $_REQUEST['mail_addr'], 'kanji', 20);

$list->set_header('投稿日', 'center', 'rmp_date');
$list->set_search_select('reg_date', select_year_month('すべて', $_REQUEST['reg_date']));

$list->set_header('投稿', 'center', 'rmp_status');
$list->set_search_select('status', select_room_public_status('すべて', $_REQUEST['status'], false));

// テストデータ設定
$sql = "SELECT rmp_room_id,rmp_seq_no,rom_name,rmp_title,rmp_content,rmp_mail_addr,rmp_date,rmp_status"
	. " FROM t_room_public LEFT JOIN t_room ON rmp_room_id=rom_room_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rmp_room_id, 'ワイワイ会議室情報を表示・変更します');
	$list->set_data($fetch->rmp_title);
	$list->set_data($fetch->rmp_content);
	$list->set_data($fetch->rmp_mail_addr);
	$list->set_data(format_datetime($fetch->rmp_date));
	$list->set_html('<select name="status_ary['.$fetch->rmp_room_id.']['.$fetch->rmp_seq_no.']">' . select_room_public_status('', $fetch->rmp_status, true) . '</select>');
}


// 投稿公開ステータス
function select_room_public_status($default, $selected, $delete) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '未投稿')
			. option_tag('2', $selected, '投稿済')
			. option_tag('3', $selected, '非承認')
			. option_tag('4', $selected, 'お断り');

	if ($delete)
		$tag .= option_tag('9', $selected, '削除');

	return $tag;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onClick_edit(f) {
	if (confirm("投稿掲載の許可/非許可を更新します。よろしいですか？")) {
		var f = document.list;
		f.action = "change_status.php";
		f.submit();
	}
}
//-->
</script>
</header>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td width="50%" align="right" colspan=2>
		<td class="lb">
			<input type="button" value="　更新　" onclick="onClick_edit()">
			<input type="submit" value="再検索">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</table>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
