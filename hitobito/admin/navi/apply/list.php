<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ応募管理一覧ページ
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
$header = new header_class('ナビ応募管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('応募No.', 'center', 'nvp_navi_page_id');
$list->set_header('応募者名', 'left', 'nav_name');
$list->set_header('メールアドレス', 'left', 'mbr_mail_addr');
$list->set_header('応募チャネル', 'left', 'nvp_subchannel_id');
$list->set_header('応募ﾀｲﾌﾟ', 'center', 'nvp_type');
$list->set_header('応募タイトル', 'left', 'nvp_theme');
$list->set_header('概要', 'left', 'nvp_outline');
$list->set_header('応募日', 'center', 'nvp_regist_date');
$list->set_header('ｽﾃｰﾀｽ', 'center', 'nvp_status');

// リストデータ設定
$sql = "SELECT nav_member_id, nvp_navi_page_id, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, mbr_mail_addr, nvp_subchannel_id, nvp_theme, nvp_type, nvp_outline, nvp_regist_date, nvp_status"
		. ' FROM t_navi_page LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id LEFT JOIN t_member ON nav_member_id = mbr_member_id'
		. ' WHERE nvp_status = 0 OR nvp_status = 2'
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$navi_page_id = $fetch->nvp_navi_page_id;

	$list->set_data($navi_page_id);
	$list->set_data($fetch->nav_name, "$top/member/member/edit.php?member_id=$fetch->nav_member_id", 'ナビ応募情報を表示・変更します');
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data(decode_sub_channel($fetch->nvp_subchannel_id));
	$list->set_data(decode_navi_page_type($fetch->nvp_type));
	$list->set_data($fetch->nvp_theme);
	$list->set_html(nl2br($fetch->nvp_outline));
	$list->set_data(format_datetime($fetch->nvp_regist_date));

	switch ($fetch->nvp_status) {
	case 0:
		$list->set_data(decode_navi_page_status($fetch->nvp_status), "javascript:change_status0($navi_page_id)", 'このナビ応募を承認またはお断りします');
		break;
	case 2:
		$list->set_data(decode_navi_page_status($fetch->nvp_status), "javascript:change_status2($navi_page_id)", 'このナビ応募を未承認に戻します');
		break;
	}
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
function change_status2(navi_page_id) {
	var params = new Array();
	params['message'] = "ジョブ状態変更";
	params['button1'] = "未承認に戻す"
	params['button2'] = "キャンセル";
	var rc = showModalDialog("<?=$top?>/common/dialog.php", params, "status:off;help:off;scroll:off;dialogWidth:300px;dialogHeight:110px");
	if (rc == 1)
		location.href = "change_status.php?navi_page_id=" + navi_page_id + "&status=0";
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
