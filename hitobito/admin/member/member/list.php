<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メンバー管理/メンバー検索/一覧ページ
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
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");

// ヘッダ情報設定
$header = new header_class('メンバー検索', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ID', 'center', 'mbr_member_id', true);
$list->set_search_text('member_id', $_REQUEST['member_id'], 'number', 8);

$list->set_header('メールアドレス', 'left', 'mbr_mail_addr', true);
$list->set_search_text('mail_addr', $_REQUEST['mail_addr'], 'mail', 20);

$list->set_header_group('メーリングリスト', 4);
$list->set_header('ML名称', 'left', 'rom_name');
$list->set_search_text('ml_name', $_REQUEST['ml_name'], 'kanji', 20);

$list->set_header('ニックネーム', 'left', 'rmm_nickname');
$list->set_search_text('nickanme', $_REQUEST['nickanme'], 'alpha', 10);

$list->set_header('登録日', 'center', 'rmm_regist_date');
$list->set_search_select('ml_regist_date', select_year_month('すべて', $_REQUEST['ml_regist_date']));

$list->set_header('MLステータス', 'center', 'rmm_status');
$list->set_search_select('ml_status', select_member_ml_status('すべて', $_REQUEST['ml_status']));

$list->set_header('便り配信', 'center', 'mbr_hitobito_letter', true);
$list->set_search_select('letter_status', select_hitobito_letter('すべて', $_REQUEST['letter_status']));

$list->set_header('不達回数', 'right', 'und_count', true);
$list->set_search_select('undeliver_count', select_undeliver_count('すべて', $_REQUEST['undeliver_count']));

$list->set_header('登録日', 'center', 'mbr_regist_date', true);
$list->set_search_select('regist_date', select_year_month('すべて', $_REQUEST['regist_date']));

$list->set_header('ステータス', 'center', 'mbr_status', true);
$list->set_search_select('status', select_member_status('すべて', $_REQUEST['status']));

// リストデータ取得
$sql = "SELECT mbr_member_id,mbr_mail_addr,rom_name,rmm_nickname,rmm_regist_date,rmm_status,mbr_hitobito_letter,und_count,mbr_regist_date,mbr_status"
		. " FROM t_member"
		. " LEFT JOIN t_room_member ON rmm_member_id=mbr_member_id"
		. " LEFT JOIN t_room ON rom_room_id=rmm_room_id"
		. " LEFT JOIN c_undeliver ON und_member_id=mbr_member_id"
		. $list->get_where()
		. $list->get_order_by(0, 1)
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mbr_member_id, "../member/edit.php?member_id=$fetch->mbr_member_id", 'メンバー情報を表示・変更します');
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data($fetch->rom_name);
	$list->set_data($fetch->rmm_nickname);
	$list->set_data(format_datetime($fetch->rmm_regist_date));
	$list->set_data(decode_member_ml_status($fetch->rmm_status));
	$list->set_data(decode_hitobito_letter($fetch->mbr_hitobito_letter));
	$list->set_data(number_format($fetch->und_count));
	$list->set_data(format_datetime($fetch->mbr_regist_date));
	$list->set_data(decode_member_status($fetch->mbr_status));
}

function select_undeliver_count($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 9; $i++)
		$tag .= option_tag($i, $selected, $i);

	return $tag;
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
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td class="lc" align="right">
		<input type="submit" value="再検索">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

 <br>

<?php $header->page_footer(); ?>
</body>
</html>
