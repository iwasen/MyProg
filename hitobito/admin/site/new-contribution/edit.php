<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ワイワイ会議最新投稿編集ページ
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
include("$inc/format.php");
include("$inc/list.php");

// パラメータ取得
$channel_id = $_POST['channel_id'];

// ページヘッダ情報設定
$header = new header_class('ワイワイ会議最新投稿', BACK_TOP);

// 最新投稿リスト
$list = new list_class('small');
$list->set_header('記事No.', 'center');
$list->set_header('チャネル', 'center');
$list->set_header('ワイワイ会議', 'left');
$list->set_header('タイトル', 'left');
$list->set_header('投稿者', 'left');
$list->set_header('掲載日', 'center');
$list->set_header('掲載', 'center');
$list->set_header('非掲載', 'center');

$new_remark_count = get_const('max_new_remarks');
$remark_id_ary = array();

// 上位20件取得サブクエリ
$sb1 = "SELECT rmk_remark_id AS sb1_remark_id"
		. " FROM l_room_remark"
		. ($channel_id ? " JOIN t_navi_page ON nvp_room_id=rmk_room_id" : "")
		. " WHERE rmk_status=1 AND rmk_open_flag=1" . ($channel_id ? " AND nvp_channel_id=$channel_id" : "")
		. " ORDER BY rmk_remark_id DESC"
		. " LIMIT 20";

$sql = "SELECT rmk_remark_id, chn_channel_name, rom_name, rmk_subject, rmm_nickname, rmk_date, rmk_keisai_flag, rmk_room_id"
		. " FROM ($sb1) sb1"
		. " JOIN l_room_remark ON rmk_remark_id=sb1_remark_id"
		. " JOIN t_room ON rom_room_id=rmk_room_id"
		. " JOIN t_room_member ON rmm_room_id=rmk_room_id AND rmm_member_id=rmk_member_id"
		. " JOIN t_navi_page ON nvp_room_id=rmk_room_id"
		. " JOIN m_channel ON chn_channel_id=nvp_channel_id"
		. " ORDER BY rmk_remark_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rmk_remark_id, "../../ml/contribution/check.php?remark_id=$fetch->rmk_remark_id");
	$list->set_data($fetch->chn_channel_name);
	$list->set_data($fetch->rom_name, "../../navi/ml/edit.php?room_id=$fetch->rmk_room_id");
	$list->set_data($fetch->rmk_subject);
	$list->set_data($fetch->rmm_nickname);
	$list->set_data(format_datetime($fetch->rmk_date));
	$list->set_data($fetch->rmk_keisai_flag == 1 && --$new_remark_count >= 0 ? '掲載中' : '');
	$list->set_html(html_input_checkbox('keisai_flag[]', $fetch->rmk_remark_id, $fetch->rmk_keisai_flag == 0, 'height:13px'));

	$remark_id_ary[] = $fetch->rmk_remark_id;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	f.action = "update.php";
	return confirm("ワイワイ会議最新投稿を更新します。よろしいですか？");
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
<table border="0" cellpadding="1" width="85%">
  <tr>
    <td align="right" valign="top">チャネル
      <select name="channel_id" onchange="submit_self()"><?=select_channel('★トップページ★', $_POST['channel_id'])?></select>
    </td>
  </tr>
	<tr>
		<td>
<?php $list->disp_list('100%') ?>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="remark_id" <?=value(join(':', $remark_id_ary))?>>
<input type="hidden" name="next_action">
<input type="submit" value="更新する" onclick="document.edit.next_action.value='update'">
<input type="reset" value="リセット">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
