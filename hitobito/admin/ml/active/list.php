<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ML活性化状況ページ
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
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('ML活性化状況', BACK_TOP);

// 入力パラメータ
$year = $_POST['year'];
$month = $_POST['month'];

// デフォルトの年を設定
if (!$year)
	$year = date('Y');

// デフォルトの月を設定
if (!$month)
	$month = date('m');

// 前年
if (isset($_POST['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// 翌年
if (isset($_POST['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('MLID', 'center', 'rom_room_id');
$list->set_search_text('room_id', $_REQUEST['room_id'], 'number', 5);

$list->set_header('ワイワイ会議', 'left', 'rom_name');
$list->set_search_text('room_name', $_REQUEST['room_name'], alpha, 20);

$list->set_header('メンバー数', 'right', 'member_num');
$list->set_search_select('member_num', select_over_count('すべて', $_REQUEST['member_num']));

$list->set_header('発言者数', 'right', 'remark_member_num');
$list->set_search_select('remark_member_num', select_over_count('すべて', $_REQUEST['remark_member_num']));

$list->set_header('発言', 'right', 'remark_num');
$list->set_search_select('remark_num', select_over_count('すべて', $_REQUEST['remark_num']));

$list->set_header('訪問者数', 'right', 'CASE WHEN uu_num IS NOT NULL THEN uu_num ELSE 0 END');
$list->set_search_select('uu_num', select_over_count('すべて', $_REQUEST['uu_num']));

$list->set_header('ページビュー数', 'right', 'CASE WHEN pv_num IS NOT NULL THEN pv_num ELSE 0 END');
$list->set_search_select('pv_num', select_over_count('すべて', $_REQUEST['pv_num']));

$list->set_header('最終発言日', 'center', 'last_date');
$list->set_search_select('last_date', select_year_month('すべて', $_REQUEST['last_date']));

$list->set_header('ｽﾃｰﾀｽ', 'center', 'rom_status');
$list->set_search_select('room_status', select_room_status('すべて', $_REQUEST['room_status']));

// テストデータ設定
$sql_date = sql_date("$year/$month/1");
$where = "date_trunc('month', rom_regist_date) <= $sql_date";

$sub1 = "SELECT rmm_room_id AS sb1_room_id,COUNT(*) AS member_num FROM t_room_member WHERE date_trunc('month', rmm_regist_date)<=$sql_date GROUP BY rmm_room_id";
$sub2 = "SELECT rmk_room_id AS sb2_room_id,COUNT(DISTINCT rmk_member_id) AS remark_member_num,COUNT(*) AS remark_num FROM l_room_remark WHERE date_trunc('month', rmk_date)=$sql_date GROUP BY rmk_room_id";
$sub3 = "SELECT rmk_room_id AS sb3_room_id,MAX(rmk_date) AS last_date FROM l_room_remark WHERE date_trunc('month', rmk_date)=$sql_date GROUP BY rmk_room_id";
$sub4 = "SELECT rmk_room_id AS sb4_room_id,COUNT(*) AS pv_num,COUNT(DISTINCT pvl_ip_addr) AS uu_num FROM l_page_view JOIN l_room_remark ON rmk_pv_id=pvl_pv_id WHERE date_trunc('month', pvl_date)=$sql_date GROUP BY rmk_room_id";
$sql = "SELECT rom_room_id,rom_name,member_num,remark_member_num,remark_num,uu_num,pv_num,last_date,rom_status"
	. " FROM t_room"
	. " LEFT JOIN ($sub1) sub1 ON sb1_room_id=rom_room_id"
	. " LEFT JOIN ($sub2) sub2 ON sb2_room_id=rom_room_id"
	. " LEFT JOIN ($sub3) sub3 ON sb3_room_id=rom_room_id"
	. " LEFT JOIN ($sub4) sub4 ON sb4_room_id=rom_room_id"
	. $list->get_where($where)
	. $list->get_order_by(0, 0)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rom_room_id);
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rom_room_id, 'ML情報を表示・変更します');
	$list->set_data(number_format($fetch->member_num), "$top/member/member/list.php", 'メンバー登録情報を表示・変更します');
	$list->set_data(number_format($fetch->remark_member_num), "$top/member/member/list.php", 'メンバー登録情報を表示・変更します');
	$list->set_data(number_format($fetch->remark_num), "$top/ml/contribution/list.php", '発言情報を表示・変更します');
	$list->set_data(number_format($fetch->uu_num));
	$list->set_data(number_format($fetch->pv_num));
	$list->set_data(format_datetime($fetch->last_date, '-'));
	$list->set_data(decode_room_status($fetch->rom_status));
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("ml_active_{$year}_{$month}.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td width="50%" align="right" colspan=2>
			<nobr>
			<select name="year" onChange="document.list.submit()">
<?=select_year(min(2005, $year), '', $year)?>
			</select>
			<input type="submit" name="prev_month" value="≪">
			<select name="month" onChange="document.list.submit()">
<?=select_month('', $month)?>
			</select>
			<input type="submit" name="next_month" value="≫">
			<input type="submit" name="export" value="エクスポート">
			</nobr>
		</td>
		<td class="lb">
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
