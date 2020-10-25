<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 承認処理ページ
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
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");

// ページヘッダ情報設定
$header = new header_class('承認処理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('ナビID', 'center', 'nvp_navi_page_id');
$list->set_header('ナビ名', 'left', 'nav_name');
$list->set_header('メールアドレス', 'left', 'mbr_mail_addr');
$list->set_header('BlogID', 'center', 'blg_blog_id');
$list->set_header('ナビページ名', 'left', 'nvp_title');
$list->set_header('MLID', 'center', 'rom_room_id');
$list->set_header('ワイワイ会議名', 'left', 'rom_name');
$list->set_header('応募日', 'center', 'nvp_regist_date');
$list->set_header('公開予定日', 'center', 'nvp_open_date');
$list->set_header('基本設定<br>CHK', 'center', 'nvp_status');
$list->set_header('ｺﾝﾃﾝﾂ<br>CHK', 'center', 'nvp_contents_check');
$list->set_header('公開前<br>最終CHK', 'center', 'nvp_open_flag');

// リストデータ設定
$sql = "SELECT nav_member_id, nvp_navi_page_id, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, mbr_mail_addr, rom_name, rom_regist_date, rom_open_date, nav_navi_id, rom_room_id, blg_blog_id, nvp_title, nvp_status, nvp_contents_check, nvp_open_flag"
		. " FROM t_navi_page"
		. " LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id"
		. " LEFT JOIN t_member ON nav_member_id = mbr_member_id"
		. " LEFT JOIN t_room ON nvp_room_id=rom_room_id"
		. " LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id"
		. " WHERE nvp_open_flag = 2"
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$navi_page_id = $fetch->nvp_navi_page_id;

	$list->set_data($navi_page_id, "$top/navi/navi/edit.php?navi_id=$fetch->nav_navi_id", 'ナビ登録情報を表示・変更します');
	$list->set_data($fetch->nav_name);
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data($fetch->blg_blog_id);
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=1", 'Blog登録情報を表示・変更します');
	$list->set_data($fetch->rom_room_id);
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=$fetch->rom_room_id", 'ML登録情報を表示・変更します');
	$list->set_data(format_datetime($fetch->rom_regist_date));
	$list->set_data(format_datetime($fetch->rom_open_date));

	switch ($fetch->nvp_status) {
	case 0:
		$list->set_data(decode_navi_page_status($fetch->nvp_status), "javascript:change_status0($navi_page_id)", 'このナビ応募を承認またはお断りします');
		break;
	case 1:
	case 2:
		$list->set_data(decode_navi_page_status($fetch->nvp_status), "javascript:change_status1($navi_page_id)", 'このナビ応募を未承認に戻します');
		break;
	}

	switch ($fetch->nvp_contents_check) {
	case 0:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check0($navi_page_id)", 'コンテンツチェックを承認します');
		break;
	case 1:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check1($navi_page_id)", 'コンテンツチェックを承認します');
		break;
	case 2:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check2($navi_page_id)", 'コンテンツチェックを承認します');
		break;
	case 3:
		$list->set_data(decode_navi_contents_check($fetch->nvp_contents_check), "javascript:change_contents_check3($navi_page_id)", 'コンテンツチェックを未申請に戻します');
		break;
	}

	$list->set_html(html_input_button('承認', "change_open_flag1($navi_page_id)") . ' ' . html_input_button('解除', "change_open_flag2($navi_page_id)"));
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function change_status0(navi_page_id) {
	var params = new Array();
	params['message'] = "ナビ応募承認処理";
	params['button1'] = "承認する";
	params['button2'] = "お断りする";
	params['button3'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:300px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_status.php?navi_page_id=" + navi_page_id + "&status=1";
	else if (rc == 2)
		location.href = "change_status.php?navi_page_id=" + navi_page_id + "&status=2";
}
function change_status1(navi_page_id) {
	var params = new Array();
	params['message'] = "ジョブ状態変更";
	params['button1'] = "未承認に戻す"
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:300px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_status.php?navi_page_id=" + navi_page_id + "&status=0";
}
function change_contents_check0(navi_page_id) {
	var params = new Array();
	params['message'] = "コンテンツチェック処理";
	params['button1'] = "承認する";
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
}
function change_contents_check1(navi_page_id) {
	var params = new Array();
	params['message'] = "コンテンツチェック処理";
	params['button1'] = "承認する";
	params['button2'] = "差し戻す";
	params['button3'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
	else if (rc == 2)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=2";
}
function change_contents_check2(navi_page_id) {
	var params = new Array();
	params['message'] = "コンテンツチェック処理";
	params['button1'] = "承認する";
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=3";
}
function change_contents_check3(navi_page_id) {
	var params = new Array();
	params['message'] = "コンテンツチェック処理";
	params['button1'] = "未申請に戻す";
	params['button2'] = "差し戻す";
	params['button3'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=0";
	else if (rc == 2)
		location.href = "change_contents_flag.php?navi_page_id=" + navi_page_id + "&flag=2";
}
function change_open_flag1(navi_page_id) {
	var params = new Array();
	params['message'] = "公開前最終チェック処理";
	params['button1'] = "承認する";
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_open_flag.php?navi_page_id=" + navi_page_id + "&flag=1";
}
function change_open_flag2(navi_page_id) {
	var params = new Array();
	params['message'] = "公開前最終チェック処理";
	params['button1'] = "解除する";
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:320px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_open_flag.php?navi_page_id=" + navi_page_id + "&flag=0";
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">

<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
