<?php
/**
 * ひとびと･net 事務局ページ
 *
 * サイトプロファイル表示ページ
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
include("$inc/date.php");
include("$inc/select.php");
include("$inc/csv.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('サイトプロファイル', BACK_TOP);

// 入力パラメータ
$year = $_GET['year'];
$month = $_GET['month'];

// デフォルトの年月を設定
if (!$year) {
	$year = date('Y');
	$month = date('m');
}

// 前月
if (isset($_GET['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// 翌月
if (isset($_GET['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// サイトプロファイルデータ取得
$data = new site_profile_data_class();
$data->read_db(get_date_from_ymd($year, $month, 1));

// 統計情報
$edit = new edit_class();
$edit->set_item('ナビ数');
$edit->set_string(number_format($data->navi_num) . ' 人');

$edit->set_item('メンバー数');
$edit->set_string(number_format($data->member_num) . ' 人');

$edit->set_item('便り登録者数');
$edit->set_string(number_format($data->letter_member) . ' 人');

$edit->set_item('Blog数');
$edit->set_string(number_format($data->blog_num) . ' 個');

$edit->set_item('ワイワイ会議数');
$edit->set_string(number_format($data->room_num) . ' 会議');

$edit->set_item('ナビ記事数／月');
$edit->set_string(number_format($data->article_num) . ' 記事');

$edit->set_item('発言人数／月');
$edit->set_string(number_format($data->remark_num) . ' 人');

$edit->set_item('発言数／月');
$edit->set_string(number_format($data->remark_count) . ' 発言');

$edit->set_item('メール配信数／月');
$edit->set_string(number_format($data->mail_num) . ' 通');

$edit->set_item('RAM率／月');
$edit->set_string(number_format($data->ram_rate) . ' ％');

$edit->set_item('ページビュー数／月');
$edit->set_string(number_format($data->page_view) . ' PV');

$edit->set_item('訪問者数／月');
$edit->set_string(number_format($data->visitor_num) . ' UU');

$edit->set_item('クリック数／月');
$edit->set_string(number_format($data->click_num) . ' クリック');

$edit->set_item('トラックバック数／月');
$edit->set_string(number_format($data->trackback_num) . ' 件');

$edit->set_item('コメント数／月');
$edit->set_string(number_format($data->comment_num) . ' 件');

// チャネル別テーマ数
$list = new list_class('small');
$list->set_header('チャネル');
$list->set_header('テーマ数', 'center');

foreach ($data->navi_page_ary as $navi_page) {
	$list->set_data($navi_page->channel_name);
	$list->set_data($navi_page->navi_page_num);
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("site_profile_{$year}_{$month}.csv");
	$csv->export_data($edit);
	$csv->output_newline();
	$csv->export_data($list);
	exit;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<body>

<?php $header->page_header(); ?>

<form name="form1">
<table width="85%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" align="right" colspan=2>
      <select name="year" onChange="document.form1.submit()">
<?=select_year(min(2005, $year), '', $year)?>
      </select>
      <input type="submit" name="prev_month" value="≪">
      <select name="month" onChange="document.form1.submit()">
<?=select_month('', $month)?>
      </select>
      <input type="submit" name="next_month" value="≫">
      <input type="submit" name="export" value="エクスポート">
    </td>
  </tr>
  <tr>
    <td width="50%" valign="top">■統計情報</td>
    <td width="50%" valign="top">■チャネル別テーマ数</td>
  </tr>
  <tr>
    <td width="50%" valign="top">
<?php $edit->disp_edit('95%', '50%') ?>
    </td>
    <td width="50%" valign="top">
<?php $list->disp_list('100%') ?>
    </td>
  </tr>
</table>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
