<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビランキング編集ページ
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
include("$inc/list.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('ナビランキング管理', BACK_TOP);

// データ処理クラス生成
$data = new ranking_navi_data_class();

// DBからデータ読み込み
$data->read_db();

// ランキング計算
if ($_POST['next_action'] == 'calc') {
	$data->read_form();
	$data->calc_ranking();
}

// ランキングリスト
$list1 = new list_class('small', 'id_rank');
$list1->set_header('順位', 'center');
$list1->set_header('ナビページ', 'left');
$list1->set_header('メンバー数', 'right');
$list1->set_header('増加数', 'right');
$list1->set_header('発言数', 'right');
$list1->set_header('MLPV', 'right');
$list1->set_header('１人当', 'right');
$list1->set_header('投稿数', 'right');
$list1->set_header('記事PV', 'right');
$list1->set_header('TB', 'right');
$list1->set_header('コメント', 'right');
$list1->set_header('指数', 'right');
$list1->set_header('変動', 'center');

$rank_no = 1;
foreach ($data->ranking_ary as $navi_page_id => $ranking) {
	$list1->set_data($rank_no++);
	$list1->set_data($ranking->title, "../../navi/blog/edit.php?blog_id=$ranking->blog_id");
	$list1->set_data(number_format($ranking->ml_member_num));
	$list1->set_data(number_format($ranking->ml_member_up));
	$list1->set_data(number_format($ranking->ml_total_remark));
	$list1->set_data(number_format($ranking->ml_page_view));
	$list1->set_data(number_format($ranking->ml_member_remark, 2));
	$list1->set_data(number_format($ranking->blog_new_post));
	$list1->set_data(number_format($ranking->blog_page_view));
	$list1->set_data(number_format($ranking->blog_trackback));
	$list1->set_data(number_format($ranking->blog_comment));
	$list1->set_data(number_format($ranking->index, 1));
	$list1->set_html(html_input_radio('rank', $navi_page_id, '', 'height:13px'));
}

// ランキング設定処理クラス生成
$edit1 = new edit_class('ランキング');
$edit1->set_command('<input type="button" value="▲" onclick="rank_up()"><input type="button" value="▼" onclick="rank_down()">');
$edit1->set_item($list1);

// 指数設定処理クラス生成
$edit2 = new edit_class('指数設定');

$edit2->set_item('MLメンバー数', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_num', $data->ml_member_num, 'number', 10);

$edit2->set_item('メンバー増加数', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_up', $data->ml_member_up, 'number', 10);

$edit2->set_item('ML全体発言数', true);
$edit2->set_string('x');
$edit2->set_text('ml_total_remark', $data->ml_total_remark, 'number', 10);

$edit2->set_item('MLページビュー', true);
$edit2->set_string('x');
$edit2->set_text('ml_page_view', $data->ml_page_view, 'number', 10);

$edit2->set_item('一人当発言数', true);
$edit2->set_string('x');
$edit2->set_text('ml_member_remark', $data->ml_member_remark, 'number', 10);

$edit2->set_item('新規記事投稿数', true);
$edit2->set_string('x');
$edit2->set_text('blog_new_post', $data->blog_new_post, 'number', 10);

$edit2->set_item('記事ページビュー', true);
$edit2->set_string('x');
$edit2->set_text('blog_page_view', $data->blog_page_view, 'number', 10);

$edit2->set_item('記事トラックバック数', true);
$edit2->set_string('x');
$edit2->set_text('blog_trackback', $data->blog_trackback, 'number', 10);

$edit2->set_item('記事コメント数', true);
$edit2->set_string('x');
$edit2->set_text('blog_comment', $data->blog_comment, 'number', 10);

// 更新頻度処理クラス生成
$edit3 = new edit_class('更新頻度');

$edit3->set_item('更新頻度', true);
$edit3->set_radio('update_mode', '1', $data->update_mode, '月１　毎月1日更新<br>');
$edit3->set_radio('update_mode', '2', $data->update_mode, '月２　毎月1日、16日更新<br>');
$edit3->set_radio('update_mode', '3', $data->update_mode, '毎週　毎週月曜日更新');
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php edit_class::js_input_check_all($edit2, $edit3); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	switch (f.next_action.value) {
	case 'update':
		f.action = 'update.php';
		get_ranking();
		if (input_check(f))
			return confirm("ナビランキングを更新します。よろしいですか？");
		break;
	case 'calc':
		f.action = 'edit.php';
		return input_check(f);
	}
	return false;
}
function rank_up() {
	var f = document.edit;
	var move = check_move();
	if (move <= 1)
		return false;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr1 = table.childNodes[move - 1];
	var tr2 = table.childNodes[move];
	exchange_row(table, tr1, tr2);
	f.rank[move - 2].checked = true;
}
function rank_down() {
	var f = document.edit;
	var move = check_move();
	if (move < 1)
		return false;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr1 = table.childNodes[move];
	var tr2 = table.childNodes[move + 1];
	exchange_row(table, tr1, tr2);
	f.rank[move].checked = true;
}
function check_move() {
	var f = document.edit;
	if (f.rank) {
		if (f.rank.length) {
			for (var i = 0; i < f.rank.length; i++) {
				if (f.rank[i].checked)
					return i + 1;
			}
		} else {
			if (f.rank.checked)
				return 0;
		}
	}
	alert("ランキングを変更する行を選択してください。");
	return 0;
}
function exchange_row(table, tr1, tr2) {
	var td1 = tr1.firxxxhild;
	var td2 = tr2.firxxxhild;
	var text1 = td1.innerText;
	var text2 = td2.innerText;
	var className1 = tr1.getAttribute("className");
	var className2 = tr2.getAttribute("className");
	table.insertBefore(table.removeChild(tr2), tr1);
	td1.innerText = text2;
	td2.innerText = text1;
	tr1.setAttribute("className", className2);
	tr2.setAttribute("className", className1);
}
function get_ranking() {
	var f = document.edit;
	var table = document.getElementById("id_rank").firxxxhild;
	var tr = table.childNodes;
	var rank = "";
	for (var i = 1; i < tr.length; i++) {
		if (rank != "")
			rank += ":";
		rank += tr[i].childNodes[12].firxxxhild.value;
	}
	f.ranking.value = rank;
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
<?php $edit3->disp_edit() ?>
<br>
<input type="hidden" name="ranking">
<input type="hidden" name="next_action">
<input type="submit" value="計算する" onclick="document.edit.next_action.value='calc'">
<input type="submit" value="更新する" onclick="document.edit.next_action.value='update'">
<input type="button" value="リセット" onclick="location.href='edit.php'">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
