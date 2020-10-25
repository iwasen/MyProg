<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メンバー登録情報編集ページ
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
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('メンバー登録情報編集', BACK_TOP);

// データ処理クラス生成
$data = new member_data_class();

// DBからデータ読み込み
if (isset($_GET['member_id']))
	$data->read_db($_GET['member_id']);

// 基本情報編集処理クラス生成
$edit = new edit_class('基本情報');

$edit->set_item('ID', false);
$edit->set_string($data->member_id);

$edit->set_item('メールアドレス', true);
$edit->set_text('mail_addr', $data->mail_addr, 'alpha', 50, 100);
$edit->set_note('（半角100文字まで）');

$edit->set_item('パスワード', false);
$edit->set_text('password', '', 'alpha', 25, 20);
$edit->set_note('（変更する場合は新しいパスワードを入力、変更しない場合は空欄のままにしてください）');

$edit->set_item('お名前', false);
$edit->set_text('name', $data->name, 'kanji', 50, '');

$edit->set_item('ふりがな', false);
$edit->set_text('name_kana', $data->name_kana, 'kanji', 50, '');

$edit->set_item('性別', false);
$edit->set_radio('sex_cd', '1', $data->sex_cd, '男性');
$edit->set_radio('sex_cd', '2', $data->sex_cd, '女性');

$edit->set_item('生年月日', false);
$edit->set_select('birthday_y', select_year('1900', ' ', $data->birthday['y']));
$edit->set_select('birthday_m', select_month(' ', $data->birthday['m']));
$edit->set_select('birthday_d', select_day(' ', $data->birthday['d']));

$edit->set_item('郵便番号', false);
$edit->set_text('zip1', substr($data->zip_cd, 0, 3), 'number', 3, 3);
$edit->set_string('-');
$edit->set_text('zip2', substr($data->zip_cd, 3, 4), 'number', 4, 4);

$edit->set_item('都道府県', false);
$edit->set_select('address1', select_todofuken('都道府県', $data->address1));

$edit->set_item('都道府県以下', false);
$edit->set_text('address2', $data->address2, 'kanij', 100, '');

$edit->set_item('電話', false);
$edit->set_text('tel1', $data->tel['0'], 'number', 6, 6);
$edit->set_string('-');
$edit->set_text('tel2', $data->tel['1'], 'number', 6, 6);
$edit->set_string('-');
$edit->set_text('tel3', $data->tel['2'], 'number', 6, 6);

$edit->set_item('自己紹介', false);
$edit->set_textarea('intro', 60, 5, $data->intro);

// ML情報：リスト処理クラス生成
$list = new list_class(small);

// リストヘッダ設定
$list->set_header('ML名称', 'center');
$list->set_header('ニックネーム', 'center');
$list->set_header('仮登録日時', 'center');
$list->set_header('本登録日時', 'center');
$list->set_header('投稿数', 'center');
$list->set_header('ステータス', 'center');

// リストデータ設定
$sql = "SELECT rom_name, rmm_nickname, rmm_kari_date, rmm_regist_date, rmm_status, rom_room_id, (SELECT COUNT(*) FROM l_room_remark WHERE rmk_room_id=rmm_room_id AND rmk_member_id=rmm_member_id) AS rmm_count
"
		. " FROM t_room_member LEFT JOIN t_room ON rom_room_id = rmm_room_id"
		. " WHERE rmm_member_id = " . sql_number($data->member_id)
		. " ORDER BY rmm_kari_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=$fetch->rom_room_id", 'ML情報を表示・変更します');
	$list->set_html(html_input_text("nickname[$fetch->rom_room_id]", $fetch->rmm_nickname, 'kanji', 20));
	$list->set_data(format_datetime($fetch->rmm_kari_date, '-'));
	$list->set_data(format_datetime($fetch->rmm_regist_date, '-'));
	$list->set_data(number_format($fetch->rmm_count));
	$list->set_html('<select name="ml_status[' . $fetch->rom_room_id . ']">' . select_member_ml_status('', $fetch->rmm_status) . '</select>');
}

// ML情報編集処理クラス生成
$edit2 = new edit_class('ML情報');
$edit2->set_item($list);

// その他情報編集処理クラス生成
$edit3 = new edit_class('その他情報');

$edit3->set_item('ひとびと便り', false);
$edit3->set_radio('hitobito_letter', '1', $data->hitobito_letter, '配信する');
$edit3->set_radio('hitobito_letter', '0', $data->hitobito_letter, '配信しない');

$edit3->set_item('不達回数', false);
$edit3->set_string(number_format($data->undeliver) . ' 回');

$edit3->set_item('ステータス', false);
$edit3->set_select('status', select_member_status('', $data->status));

$edit3->set_item('登録日時', false);
$edit3->set_string(format_datetime($data->regist_date, '-'));

$edit3->set_item('最新更新日時', false);
$edit3->set_string(format_datetime($data->update_date, '-'));

// メンバーURL
$user_page_url = get_const('user_page_url');
$member_url = "{$user_page_url}index.php?module=Member";
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
			return confirm("メンバー情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("メンバー登録情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("メンバー登録情報を削除します。よろしいですか？");
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
			<input type="button" value="メンバー登録情報編集画面へログイン" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$data->member_id?>&url=<?=urlencode($member_url)?>', 'member_edit')">
		</td>
	</tr>
</table>

<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit3->disp_edit() ?>

<br>
<input type="hidden" name="member_id" <?=value($data->member_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="　登録情報更新　" onclick="document.form1.next_action.value='update'">
<!--	<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">	-->
<?php } ?>
<input type="button" value="　戻る　" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
