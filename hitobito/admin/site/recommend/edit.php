<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 今日のイチオシナビ編集ページ
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
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/edit.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('今日のイチオシナビ管理', BACK_TOP);

// データ処理クラス生成
$data = new recommend_data_class();

// DBからデータ読み込み
$data->read_db($_POST['channel_id']);

// ランキング設定処理クラス生成
$edit1 = new edit_class('更新モード');
$edit1->set_command('チャネル' . html_select('channel_id', select_channel('★トップページ★', $_POST['channel_id']), 1, '', 'submit_self()'));

$position_ary = array(1 => '上段', 2 => '中段', 3 => '下段');
for ($i = 1; $i <= 3; $i++) {
	$edit1->set_item($position_ary[$i], true);
	$edit1->set_radio("selection_mode[$i]", '1', $data->selection_mode[$i], '固定');
	$edit1->set_select("fix_navi_page_id[$i]", select_navi_page_title('', $data->fix_navi_page_id[$i], $_POST['channel_id']));
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '2', $data->selection_mode[$i], 'ランダム(人気記事連動1-5位)');
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '3', $data->selection_mode[$i], 'ランダム');
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '4', $data->selection_mode[$i], 'ループ');
	$edit1->set_br();
	$edit1->set_string('　　');
	$edit1->set_select("loop_navi_page_id[$i][]", select_navi_page_title('', explode(',', $data->loop_navi_page_id[$i]), $_POST['channel_id']), '', 5, 'multiple');
}

// 更新頻度処理クラス生成
$edit2 = new edit_class('更新頻度');

$edit2->set_item('更新頻度', true);
$edit2->set_radio('update_mode', '1', $data->update_mode, '手動更新<br>');
$edit2->set_radio('update_mode', '2', $data->update_mode, '自動更新');
$edit2->set_select('update_time', select_hour('', $data->update_time));
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit2->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	if (!input_check2(f))
		return false;
	return confirm("今日のイチオシナビを更新します。よろしいですか？");
}
function input_check2(f) {
	var a = new Array("", "上段", "中段", "下段");
	for (var i = 1; i <= 3; i++) {
		for (var j = 0; j < 4; j++) {
			if (f["selection_mode[" + i + "]"][j].checked)
				break;
		}
		if (j < 4) {
			if (j == 0) {
				if (f["fix_navi_page_id[" + i  +"]"].selectecIndex == -1) {
					alert("固定モードのナビページを選択してください。");
					f["fix_navi_page_id[" + i  +"]"].focus();
					return false;
				}
			} else if (j == 3) {
				for (var k = 0; k < f["loop_navi_page_id[" + i  +"][]"].length; k++) {
					if (f["loop_navi_page_id[" + i  +"][]"].options[k].selected)
						break;
				}
				if (k == f["loop_navi_page_id[" + i  +"][]"].length) {
					alert("ループモードのナビページを選択してください。");
					f["loop_navi_page_id[" + i  +"][]"].focus();
					return false;
				}
			}
		} else {
			alert(a[i] + "の更新モードを選択してください。");
			f["selection_mode[" + i + "]"][0].focus();
			return false;
		}
	}
	return input_check(f);
}
function submit_self() {
	var f = document.edit;
	f.action = "edit.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="edit" method="post" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit1->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<input type="hidden" name="next_action">
<input type="submit" value="更新する" onclick="document.edit.next_action.value='update'">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
