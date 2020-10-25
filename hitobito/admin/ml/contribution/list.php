<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ML投稿管理ページ
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
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/ngword.php");
include("$inc/list.php");

// ヘッダ情報設定
$header = new header_class('発言管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('発言ID', 'center', 'rmk_remark_id');
$list->set_search_text('remark_id', $_REQUEST['remark_id'], 'number', 8);

$list->set_header('タイトル', 'left', 'rmk_subject');
$list->set_search_text('subject', $_REQUEST['subject'], 'kanji', 20);

$list->set_header('ワイワイ会議', 'left', 'rom_name');
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 20);

$list->set_header('投稿者', 'left', 'rmm_nickname');
$list->set_search_text('nickname', $_REQUEST['nickname'], 'kanji', 20);

$list->set_header('NGワード', 'left', 'rmk_ng_word');
$list->set_search_select('ng_word', select_ngword('すべて', $_REQUEST['ng_word']));

$list->set_header('登録日', 'center', 'rmk_date');
$list->set_search_select('regist_date', select_year_month('すべて', $_REQUEST['regist_date']));

$list->set_header('Pickup', 'center', 'rmk_pickup');
$list->set_search_select('pickup', select_pickup('すべて', $_REQUEST['pickup']));

$list->set_header('閲覧回数', 'right', 'pvc_pv_count');
$list->set_search_select('view_count', select_over_count('すべて', $_REQUEST['view_count']));

$list->set_header('公開', 'center', 'rmk_open_flag');
$list->set_search_select('open_flag', select_open_status('すべて', $_REQUEST['open_flag']));

$list->set_header('ﾊﾟﾄﾛｰﾙ隊CHK', 'center', 'rmk_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('すべて', $_REQUEST['patrol_check']));

$list->set_header('事務局CHK', 'center', 'rmk_admin_check');
$list->set_search_select('admin_check', select_admin_check('すべて', $_REQUEST['admin_check']));

// リストデータ設定
$sql = "SELECT rmk_remark_id,rmk_subject,rmk_room_id,rom_name,rmk_member_id,rmm_nickname,rmk_ng_word,rmk_date,rmk_pickup,pvc_pv_count,rmk_open_flag,rmk_patrol_check,rmk_admin_check"
	. " FROM l_room_remark"
	. " LEFT JOIN t_room ON rmk_room_id=rom_room_id"
	. " LEFT JOIN t_room_member ON rmk_member_id=rmm_member_id AND rmk_room_id=rmm_room_id"
	. " LEFT JOIN c_page_view ON rmk_pv_id=pvc_pv_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rmk_remark_id);
	$list->set_data($fetch->rmk_subject, "check.php?remark_id=" . $fetch->rmk_remark_id, '発言情報を表示・変更します');
	if ($page_kind == 'admin') {
		$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rmk_room_id, 'ML登録情報を表示・変更します');
		$list->set_data($fetch->rmm_nickname, "$top/member/member/edit.php?member_id=" . $fetch->rmk_member_id, 'メンバー登録情報を表示・変更します');
	} else {
		$list->set_data($fetch->rom_name);
		$list->set_data($fetch->rmm_nickname);
	}
	$list->set_data(decode_ngword($fetch->rmk_ng_word));
	$list->set_data(format_datetime($fetch->rmk_date));
	$list->set_data(decode_pickup($fetch->rmk_pickup));
	$list->set_data(number_format($fetch->pvc_pv_count));
	$list->set_data(decode_open_status($fetch->rmk_open_flag));
	$list->set_data(decode_patrol_check($fetch->rmk_patrol_check));
	$list->set_data(decode_admin_check($fetch->rmk_admin_check));
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
