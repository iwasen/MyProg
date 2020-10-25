<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ゲスト登録管理ページ
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
$header = new header_class('ゲストメンバー検索一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ID', 'center', 'rmm_member_id', true);
$list->set_search_text('member_id', $_REQUEST['member_id'], 'number', 8);

$list->set_header('メールアドレス', 'left', 'mbr_mail_addr', true);
$list->set_search_text('mail_addr', $_REQUEST['mail_addr'], 'alpha', 20);

$list->set_header('ML名称', 'left', 'rom_name');
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 20);

$list->set_header('ニックネーム', 'left', 'rmm_nickname');
$list->set_search_text('nickname', $_REQUEST['nickname'], 'kanji', 20);

$list->set_header('ゲスト登録日', 'center', 'rmm_guest_date');
$list->set_search_select('guest_date', select_year_month('すべて', $_REQUEST['guest_date']));

$list->set_header('一括本登録', 'center', 'rmm_status');

// テストデータ設定
$sql = "SELECT rmm_room_id,rmm_status,rmm_member_id,mbr_mail_addr,rom_name,rmm_nickname,rmm_kari_date,rmm_guest_date"
	. " FROM t_room_member LEFT JOIN t_room ON rmm_room_id=rom_room_id"
	. " LEFT JOIN t_member ON rmm_member_id=mbr_member_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rmm_member_id, "$top/member/member/edit.php?member_id=" . $fetch->rmm_member_id, 'メンバー登録情報を表示・変更します');
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rmm_room_id, 'ワイワイ会議室登録情報を表示・変更します');
	$list->set_data($fetch->rmm_nickname);
	$list->set_data(format_datetime($fetch->rmm_guest_date));
	$list->set_html(set_checkbox($fetch->rmm_status, 'guest', $fetch->rmm_room_id, $fetch->rmm_member_id));
}

function set_checkbox($val, $kind, $room_id, $member_id){
	$data = $room_id . ':' . $member_id;

	if ($kind == 'kari' && $val <= 1)
		return '<input type="checkbox" name="reg_kari[' . $data . ']">';
	else if ($kind == 'guest' && $val == 2)
		return '<input type="checkbox" name="reg_guest[' . $data . ']">';
	else
		return '-';
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onClick_kari() {
	var f = document.list;
	if (confirm("一括ゲスト登録します。よろしいですか？")) {
		f.flag.value = "kari";
		f.action = "change_status.php";
		f.submit();
	}
}
function onClick_guest() {
	var f = document.list;
	if (confirm("一括本登録します。よろしいですか？")) {
		f.flag.value = "guest";
		f.action = "change_status.php";
		f.submit();
	}
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td width="50%" align="right" colspan=2>
		<td class="lb">
			<input type="submit" value="再検索">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</table>
<br>
<div align="center">
	<input type="hidden" name="flag">
	<input type="button" value="一括本登録" name="guest" onclick="onClick_guest()">
</div>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
