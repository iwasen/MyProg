<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便りクリックカウンタ設定ページ
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
include("$inc/const.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/edit.php");

function ClickCountURL($ct_id) {
	$url = get_const('user_page_url');
	return "{$url}ct.php?%CTID{$ct_id}%-%MEMBER_ID%";
}

// ページヘッダ情報設定
$header = new header_class('ひとびと便りクリックカウンタ設定', BACK_NONE);

$edit = new edit_class('クリックカウンタ自動設定');

if ($_POST['cc_start_date'])
	sscanf($_POST['cc_start_date'], '%d/%d/%d %d:', &$start_year, &$start_month, &$start_day, &$start_hour);

if ($_POST['cc_end_date'])
	sscanf($_POST['cc_end_date'], '%d/%d/%d %d:', &$end_year, &$end_month, &$end_day, &$end_hour);

$edit->set_item('カウンタ期間(開始)');
$edit->set_select('cc_start_y', select_year('2005', ' ', $start_year));
$edit->set_select('cc_start_m', select_month(' ', $start_month));
$edit->set_select('cc_start_d', select_day(' ', $start_day));
$edit->set_select('cc_start_h', select_hour(' ', $start_hour));
$edit->set_note('（開始を指定しない場合は年をブランクにしてください）');

$edit->set_item('カウンタ期間(終了)');
$edit->set_select('cc_end_y', select_year('2005', ' ', $end_year));
$edit->set_select('cc_end_m', select_month(' ', $end_month));
$edit->set_select('cc_end_d', select_day(' ', $end_day));
$edit->set_select('cc_end_h', select_hour(' ', $end_hour));
$edit->set_note('（終了を指定しない場合は年をブランクにしてください）');
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function SetDate() {
	var f = document.form1;
	var start_date, end_date

	if (f.cc_start_y.value != "" && f.cc_start_m.value != "" && f.cc_start_d.value != "" && f.cc_start_h.value != "")
		start_date = f.cc_start_y.value + "/" + f.cc_start_m.value + "/" + f.cc_start_d.value + " " + f.cc_start_h.value + ":0:0";
	else
		start_date = "";

	if (f.cc_end_y.value != "" && f.cc_end_m.value != "" && f.cc_end_d.value != "" && f.cc_end_h.value != "")
		end_date = f.cc_end_y.value + "/" + f.cc_end_m.value + "/" + f.cc_end_d.value + " " + f.cc_end_h.value + ":0:0";
	else
		end_date = "";

	f = opener.edit;
	f.cc_start_date.value = start_date;
	f.cc_end_date.value = end_date;
}

function SetURL() {
	var f = document.form1;
	var f2 = opener.edit;
	var line = Number(f.line.value);
	var body = f.body.value;
	var m, ct_id, url_name, jump_ur;
	var ct_count = Number(f2.ct_count.value);;

	ct_id = f2.ct_id.value;
	url_name = f2.url_name.value;
	jump_url = f2.jump_url.value;
	for (var i = 1; i <= line; i++) {
		if (f("cc_ct_id" + i)) {
			if (f("cc_ct_id" + i).checked) {
				if (ct_id != "") {
					ct_id += "||";
					url_name += "||";
					jump_url += "||";
				}
				ct_id += f("cc_ct_id" + i).value;
				url_name += f("cc_url_name" + i).value;
				jump_url += f("cc_jump_url" + i).value;
				body = body.replace(f("cc_jump_url" + i).value, f("click_url" + i).value);
			}
			ct_count++;
		}
	}
	f2.body.value = body;
	f2.ct_id.value = ct_id;
	f2.ct_count.value = ct_count;
	f2.url_name.value = url_name;
	f2.jump_url.value = jump_url;
}
function OnClick_OK() {
	SetDate();
	SetURL();
	window.close();
}
//-->
</SCRIPT>
</head>
<body>

<?php $header->page_header(); ?>

<form name="form1">

<?php $edit->disp_edit('100%', '15%') ?>
<br>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>CTID</th>
		<th>URL名称</th>
		<th>飛び先URL</th>
		<th>生成URL</th>
		<th>生成</th>
	</tr>
<?php
$i = 0;
$ct_count = 1 + $_POST['ct_count'];

if (mb_ereg_search_init($_POST['body'], 'https?://[^ 　"\'\r\n]+')) {
	while (mb_ereg_search()) {
		$ary = mb_ereg_search_getregs();
		$jump_url = $ary[0];

		if (!strpos($jump_url, "%MEMBER_ID%")) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$ct_count?></td>
		<td><input type="text" name="cc_url_name<?=$ct_count?>" size=50 maxlength=50 value="URL-<?=$ct_count?>"></td>
		<td>
			<input type="hidden" name="cc_jump_url<?=$ct_count?>" <?=value($jump_url)?>>
			<a href="<?=$jump_url?>"  target='_blank'><?=htmlspecialchars($jump_url)?></a>
		</td>
		<td><input type="hidden" name="click_url<?=$ct_count?>" <?=value(ClickCountURL($ct_count))?>><?=ClickCountURL($ct_count)?></td>
		<td align="center"><input type="checkbox" name="cc_ct_id<?=$ct_count?>" <?=value($ct_count)?> checked></td>
	</tr>
<?php
			$i++;
			$ct_count++;
		}
	}
}
?>
</table>
<br>
<input type="hidden" name="line" <?=value($ct_count)?>>
<input type="hidden" name="body" <?=value($_POST['body'])?>>
<input type="button" value="　OK　" onclick="OnClick_OK()">
<input type="button" value="キャンセル" onclick="window.close()">
</center>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
