<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ登録情報編集ページ
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
include("$inc/decode.php");
include("$inc/list.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('ナビ登録情報編集', BACK_TOP);

// データ処理クラス生成
$data = new navi_data_class();

// DBからデータ読み込み
if (isset($_GET['navi_id']))
	$data->read_db($_GET['navi_id']);

// 基本情報編集処理クラス生成
$edit = new edit_class('基本情報');

if (!$data->new) {
	$edit->set_item('ID', false);
	$edit->set_string($data->navi_id);
}

$edit->set_item('メールアドレス', true);
$edit->set_text('mail_addr', $data->mail_addr, 'alpha', 50, 100);
$edit->set_note('（半角100文字まで）');

$edit->set_item('パスワード', false);
$edit->set_text('password', '', 'alpha', 25, 20);
$edit->set_note('（変更する場合は新しいパスワードを入力、変更しない場合は空欄のままにしてください）');

$edit->set_item('ナビアドレス', true);
$edit->set_text('forward_id', $data->forward_id, 'alpha', 25, 20);
$edit->set_string('@navi.hitobito.net');

$edit->set_item('メール転送', false);
$edit->set_radio('forward_flag', 1, $data->forward_flag, '転送を利用する');
$edit->set_radio('forward_flag', 0, $data->forward_flag, '転送を利用しない');

$edit->set_item('名前', true);
$edit->set_string('姓');
$edit->set_text('name1', $data->name1, 'kanji', 10, 20);
$edit->set_string('名');
$edit->set_text('name2', $data->name2, 'kanji', 10, 20);

$edit->set_item('フリガナ', true);
$edit->set_string('ｾｲ');
$edit->set_text('name1_kana', $data->name1_kana, 'kanji', 10, 20);
$edit->set_string('ﾒｲ');
$edit->set_text('name2_kana', $data->name2_kana, 'kanji', 10, 20);

$edit->set_item('性別', true);
$edit->set_radio('sex_cd', 1, $data->sex_cd, '男性');
$edit->set_radio('sex_cd', 2, $data->sex_cd, '女性');

$edit->set_item('誕生日', true);
$edit->set_select('birthday_y', select_year('1900', '', $data->birthday['y']));
$edit->set_select('birthday_m', select_month('', $data->birthday['m']));
$edit->set_select('birthday_d', select_day('', $data->birthday['d']));

$edit->set_item('郵便番号', true);
$edit->set_text('zip1', substr($data->zip_cd, 0, 3), 'number', 3, 3);
$edit->set_string('-');
$edit->set_text('zip2', substr($data->zip_cd, 3, 4), 'number', 4, 4);

$edit->set_item('電話番号', true);
$edit->set_text('jitaku_tel', $data->jitaku_tel, 'alpha', 20, 20);
$edit->set_note('（半角20文字まで）');

$edit->set_item('都道府県', true);
$edit->set_select('address1', select_todofuken('都道府県', $data->address1));

$edit->set_item('住所', true);
$edit->set_text('address2', $data->address2, 'kanji', 50, 100);

$edit->set_item('マンション/アパート', false);
$edit->set_text('address3', $data->address3, 'kanji', 50, 100);

$edit->set_item('職業', true);
$edit->set_select('shokugyo_id', select_shokugyo('選択しない', $data->shokugyo_id));


// ML情報：リスト処理クラス生成
$list = new list_class(small);

// リストヘッダ設定
$list->set_header_group('ナビページ', 3);
$list->set_header('名称', 'left');
$list->set_header('公開日', 'center');
$list->set_header('ステータス', 'center');
$list->set_header_group('ワイワイ会議室', 3);
$list->set_header('名称', 'left');
$list->set_header('公開日', 'center');
$list->set_header('ステータス', 'center');

// リストデータ設定
$sql = "SELECT blg_blog_id, nvp_title, nvp_open_date, blg_status, rom_room_id, rom_status, rom_open_date, rom_name, nvp_open_flag"
		. " FROM t_navi_page"
		. " LEFT JOIN t_room ON nvp_room_id = rom_room_id"
		. " LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id"
		. " WHERE nvp_navi_id = " . sql_number($data->navi_id)
		. " ORDER BY rom_open_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=$fetch->blg_blog_id", 'Blog情報を表示・変更します');
	$list->set_data(format_datetime($fetch->nvp_open_date, ''));
	$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag, ''));
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=$fetch->rom_room_id", 'ML情報を表示・変更します');
	$list->set_data(format_date($fetch->rom_open_date, ''));
	$list->set_data(decode_room_status($fetch->rom_status, ''));
}

// ML情報編集処理クラス生成
$edit2 = new edit_class('ML情報');
$edit2->set_item($list);


// その他情報編集処理クラス生成
$edit4 = new edit_class('その他情報');

//$edit4->set_item('ステータス', false);
//$edit4->set_select('status', select_navi_status($data->status));

if (!$data->new) {
	$edit4->set_item('登録日時', false);
	$edit4->set_string(format_datetime($data->regist_date));

	$edit4->set_item('最新更新日時', false);
	$edit4->set_string(format_datetime($data->update_date, '-'));
}

// ナビページID,メンバーID取得
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_navi_id=$data->navi_id AND nvp_status=1";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// ナビページURL
	$user_page_url = get_const('user_page_url');
	$profile_page_url = "{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$navi_page_id";
	$regist_edit_url = "{$user_page_url}index.php?module=Member&action=ModifyProfile";
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("ナビ情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("ナビ登録情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("ナビ登録情報を削除します。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 width="85%">
	<tr>
		<td align="right">
			<input type="button" value="自己紹介ページへ" onclick="window.open('<?=$profile_page_url?>', 'profile_page')">
			<input type="button" value="登録情報エディット画面へログイン" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($regist_edit_url)?>', 'regist_edit')">
		</td>
	</tr>
</table>
<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit4->disp_edit() ?>

<div align="center">
	<input type="hidden" name="navi_id" <?=value($data->navi_id)?>>
	<input type="hidden" name="member_id" <?=value($data->member_id)?>>
	<input type="hidden" name="next_action">
	<?php if ($data->new) { ?>
	<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
	<?php } else { ?>
	<input type="submit" value="　登録情報更新　" onclick="document.form1.next_action.value='update'">
<!--	<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">	-->
	<?php } ?>
	<input type="button" value="　戻る　" onclick="history.back()">
</div>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
