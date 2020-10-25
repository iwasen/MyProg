<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビML登録情報編集ページ
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
include("$inc/const.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");
include("$inc/edit.php");
include("$inc/decode.php");
include("$inc/data.php");
include("$inc/csv.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('ワイワイ会議室（ML）管理', BACK_TOP);

// 戻りページ
if (isset($_REQUEST['ret']))
	$ret = $_REQUEST['ret'];
else
	$ret = $_SERVER['HTTP_REFERER'];

// データ処理クラス生成
$data = new ml_data_class();

// DBからデータ読み込み
if (isset($_GET['room_id']))
	$data->read_db($_GET['room_id']);

// 基本情報編集処理クラス生成
$edit = new edit_class('基本情報');

$edit->set_item('MLID', false);
$edit->set_string($data->room_id);

$edit->set_item('会議室名', true);
$edit->set_text('name', $data->name, 'kanji', 50, 100);

$edit->set_item('ML-NAME', true);
$edit->set_text('room_code', $data->room_code, 'alpha', 20);

$edit->set_item('公開日時', false);
$edit->set_select('open_date_y', select_year('2005', '', $data->open_date['y']));
$edit->set_select('open_date_m', select_month('', $data->open_date['m']));
$edit->set_select('open_date_d', select_day('', $data->open_date['d']));

$edit->set_item('ステータス', true);
$edit->set_radio('status', 1, $data->status, '公開');
$edit->set_radio('status', 2, $data->status, '非公開');
$edit->set_radio('status', 9, $data->status, '閉鎖');

$edit->set_item('バナー画像URL', false);
$edit->set_text('banner_image_url', $data->banner_image_url, 'alpha', 70, 100);
$edit->set_button('表示', 'disp_banner_image()');

$edit->set_item('バナーリンク先URL', false);
$edit->set_text('banner_link_url', $data->banner_link_url, 'alpha', 70, 100);
$edit->set_button('表示', 'disp_banner_link()');

$edit->set_item('登録日時', false);
$edit->set_string(format_datetime($data->regist_date));

$edit->set_item('最新更新日時', false);
$edit->set_string(format_datetime($data->update_date));

$edit->set_item('必須追加属性', false);
$edit->set_checkbox("required_item[]", '1', in_array('1', $data->required_item), '氏名　');
$edit->set_checkbox('required_item[]', '2', in_array('2', $data->required_item), 'ふりがな　');
$edit->set_checkbox('required_item[]', '3', in_array('3', $data->required_item), '性別　');
$edit->set_checkbox('required_item[]', '4', in_array('4', $data->required_item), '生年月日　');
$edit->set_checkbox('required_item[]', '5', in_array('5', $data->required_item), '郵便番号　');
$edit->set_checkbox('required_item[]', '6', in_array('6', $data->required_item), '都道府県　');
$edit->set_checkbox('required_item[]', '7', in_array('7', $data->required_item), '住所（市町村以下の項目）　');
$edit->set_checkbox('required_item[]', '8', in_array('8', $data->required_item), '電話番号　');
$edit->set_checkbox('required_item[]', '9', in_array('9', $data->required_item), '自己紹介　');

// 基本情報編集処理クラス生成
$edit2 = new edit_class('');

$edit2->set_item('会議UU(最新日時)', false);
$edit2->set_string(number_format($data->uu_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('会議PV(最新日時)', false);
$edit2->set_string(number_format($data->pv_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('バナークリック数(最新日時)', false);
$edit2->set_string(number_format($data->click_num) . ' (' . format_datetime($data->click_last_date, '-') . ')');

$edit2->set_item('メンバー数(最新日時)', false);
$edit2->set_string(number_format($data->member_num) . ' (' . format_datetime($data->member_last_date, '-') . ')');

$edit2->set_item('入会者数(最新日時)', false);
$edit2->set_string(number_format($data->regist_num) . ' (' . format_datetime($data->regist_last_date, '-') . ')');

$edit2->set_item('退会者数(最新日時)', false);
$edit2->set_string(number_format($data->resign_num) . ' (' . format_datetime($data->resign_last_date, '-') . ')');

$edit2->set_item('発言者数(最新日時)', false);
$edit2->set_string(number_format($data->remark_member_num) . ' (' . format_datetime($data->remark_member_last_date, '-') . ')');

$edit2->set_item('発言数(最新日時)', false);
$edit2->set_string(number_format($data->remark_num) . ' (' . format_datetime($data->remark_last_date, '-') . ')');

// ナビページID,メンバーID取得
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_room_id=$data->room_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// ナビページURL
	$user_page_url = get_const('user_page_url');
	$waiwai_browse_url = "{$user_page_url}index.php?module=Waiwai&action=Browse&navipage_id=$navi_page_id";
	$waiwai_edit_url = "{$user_page_url}index.php?module=Waiwai&action=EditMenu&navipage_id=$navi_page_id";
}

// エクスポート処理
if ($_REQUEST['next_action'] == 'export') {
	$room_id = $_REQUEST['room_id'];

	$list = new list_class('');
	$csv = new csv_class("room_member_info.csv");

	$list->set_header('メンバーID');
	$list->set_header('メールアドレス');
	$list->set_header('ニックネーム');
	$list->set_header('ひとびと・net登録日時');
	$list->set_header('ワイワイ会議登録日時');

	$list->set_header('ステータス');

	$list->set_header('氏名（漢字）');
	$list->set_header('氏名（かな）');
	$list->set_header('性別');
	$list->set_header('生年月日');
	$list->set_header('郵便番号');
	$list->set_header('都道府県');
	$list->set_header('住所（市町村以下の項目）');
	$list->set_header('電話番号');
	$list->set_header('自己紹介');

	$sql = "SELECT * FROM t_room_member LEFT JOIN t_member ON mbr_member_id=rmm_member_id WHERE rmm_room_id=$room_id ORDER BY mbr_member_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$list->set_data($fetch->mbr_member_id);
		$list->set_data($fetch->mbr_mail_addr);
		$list->set_data($fetch->rmm_nickname);
		$list->set_data($fetch->mbr_regist_date);
		$list->set_data($fetch->rmm_regist_date);

		$list->set_data(decode_member_ml_status($fetch->rmm_status));
		$list->set_data($fetch->mbr_name);
		$list->set_data($fetch->mbr_name_kana);
		$list->set_data(decode_sex_cd($fetch->mbr_sex_cd));
		$list->set_data($fetch->mbr_birthday);
		$list->set_data(decode_zip_cd($fetch->mbr_zip_cd));
		$list->set_data(decode_address1($fetch->mbr_address1));
		$list->set_data($fetch->mbr_address2);
		$list->set_data($fetch->mbr_tel);
		$list->set_data($fetch->mbr_self_introduction);
	}

	$csv->export_data($list);
	exit;
}


?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onSubmit_edit(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f)) {
			f.action = "update.php";
			return confirm("ワイワイ会議室情報を登録します。よろしいですか？");
		}
		break;
	case "update":
		if (input_check(f)) {
			f.action = "update.php";
			return confirm("ワイワイ会議室登録情報を更新します。よろしいですか？");
		}
		break;
	case "delete":
		f.action = "update.php";
		return confirm("ワイワイ会議室登録情報を削除します。よろしいですか？");
	case "export":
		f.action = "";
		f.submit();
	}
	return false;
}

function disp_banner_image() {
	var f = document.edit;
	if (f.banner_image_url.value != "")
		window.open(f.banner_image_url.value, "_blank");
}
//-->

function disp_banner_link() {
	var f = document.edit;
	if (f.banner_link_url.value != "")
		window.open(f.banner_link_url.value, "_blank");
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" onsubmit="return onSubmit_edit(this)">
<table border=0 width="85%">
	<tr>
		<td align="right">
<?php
if ($navi_page_id) {
?>
			<input type="button" value="ワイワイ会議室へ" onclick="window.open('<?=$waiwai_browse_url?>', 'waiwai_browse')">
			<input type="button" value="MLエディット画面へログイン" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($waiwai_edit_url)?>', 'waiwai_edit')">
<?
}
?>
		</td>
	</tr>
</table>
<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<div align="center">
	<input type="hidden" name="room_id" <?=value($data->room_id)?>>
	<input type="hidden" name="banner_id" <?=value($data->banner_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" name="export" value="メンバー一覧CSVエクスポート" onclick="document.edit.next_action.value='export'">
	<?php if ($data->new) { ?>
	<input type="submit" value="　登録　" onclick="document.edit.next_action.value='new'">
	<?php } else { ?>
	<input type="submit" value="　登録情報更新　" onclick="document.edit.next_action.value='update'">
<!--	<input type="submit" value="　削除　" onclick="document.edit.next_action.value='delete'">	-->
	<?php } ?>
	<input type="button" value="　戻る　" onclick="location.href='<?=$ret?>'">
	<input type="hidden" name="ret" <?=value($ret)?>>
</div>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
